<?php
$pageTitle = "My Applications - Kiyome";
include __DIR__ . '/../../includes/header.php';
requireRole('adopter');

$adopterId = $_SESSION['user_id'];

// Fetch Applications
$stmt = $pdo->prepare("
    SELECT aa.*, p.name as pet_name, p.status as pet_status, s.shelter_name
    FROM adoption_applications aa
    JOIN pets p ON aa.pet_id = p.id
    JOIN shelters s ON p.shelter_id = s.user_id
    WHERE aa.adopter_id = ?
    ORDER BY aa.created_at DESC
");
$stmt->execute([$adopterId]);
$applications = $stmt->fetchAll();

// Handle messages
$msg = $_GET['msg'] ?? '';
$error = $_GET['error'] ?? '';
$displayMsg = '';
$msgClass = '';

if ($msg === 'submitted') {
    $displayMsg = "Application submitted successfully!";
    $msgClass = "background: rgba(16,185,129,0.1); border: 1px solid var(--success); color: var(--success);";
} elseif ($error) {
    $displayMsg = htmlspecialchars($error);
    $msgClass = "background: rgba(239, 68, 68, 0.1); border: 1px solid var(--error); color: var(--error);";
}
?>

<div class="dashboard-header" style="flex-direction: column; align-items: flex-start;">
    <h1 class="gradient-text">My Applications</h1>
    <p style="color: var(--text-muted); margin-top: 0.5rem;">Track the status of your furry friend requests.</p>
</div>

<?php if ($displayMsg): ?>
    <div style="<?php echo $msgClass; ?> padding: 1rem; border-radius: 12px; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.8rem;">
        <i class="fas <?php echo $error ? 'fa-exclamation-circle' : 'fa-check-circle'; ?>"></i>
        <span><?php echo $displayMsg; ?></span>
    </div>
<?php endif; ?>

<div class="glass-container table-responsive" style="padding: 0;">
    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="background: rgba(255,255,255,0.05); border-bottom: 1px solid var(--glass-border);">
                <th style="padding: 1.5rem;">Pet Info</th>
                <th style="padding: 1.5rem;">Shelter</th>
                <th style="padding: 1.5rem;">Application Date</th>
                <th style="padding: 1.5rem;">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($applications)): ?>
                <tr>
                    <td colspan="4" style="padding: 4rem; text-align: center;">
                        <i class="fas fa-heart-broken fa-3x" style="color: var(--text-muted); opacity: 0.3; margin-bottom: 1rem; display: block;"></i>
                        <p style="color: var(--text-muted);">You haven't submitted any applications yet.</p>
                        <a href="<?php echo $routeBase; ?>/pets/browse.php" class="btn btn-primary" style="margin-top: 1rem;">Browse Pets</a>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($applications as $app): ?>
                <tr style="border-bottom: 1px solid var(--glass-border);">
                    <td style="padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                        <img src="<?php echo $routeBase . '/image.php?type=pet&id=' . $app['pet_id']; ?>" style="width: 100px; height: 100px; border-radius: 12px; object-fit: cover;">
                        <div>
                            <strong style="font-size: 1.1rem;"><?php echo e($app['pet_name']); ?></strong><br>
                            <span style="color: var(--text-muted);">Status: <?php echo e(ucfirst($app['pet_status'])); ?></span>
                        </div>
                    </td>
                    <td style="padding: 1.5rem;">
                        <i class="fas fa-building" style="color: var(--secondary); margin-right: 0.5rem;"></i>
                        <?php echo e($app['shelter_name']); ?>
                    </td>
                    <td style="padding: 1.5rem; color: var(--text-muted);">
                        <?php echo date('M d, Y', strtotime($app['created_at'])); ?>
                    </td>
                    <td style="padding: 1.5rem;">
                        <?php
                        $statusClass = '';
                        $statusIcon = '';
                        if ($app['status'] === 'pending') {
                            $statusClass = 'color: var(--primary); background: rgba(255,126,95,0.1);';
                            $statusIcon = 'fa-clock';
                        } elseif ($app['status'] === 'approved') {
                            $statusClass = 'color: var(--success); background: rgba(16,185,129,0.1);';
                            $statusIcon = 'fa-check-circle';
                        } else {
                            $statusClass = 'color: var(--error); background: rgba(239, 68, 68, 0.1);';
                            $statusIcon = 'fa-times-circle';
                        }
                        ?>
                        <span style="<?php echo $statusClass; ?> padding: 8px 16px; border-radius: 50px; font-weight: 700; text-transform: uppercase; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.5rem;">
                            <i class="fas <?php echo $statusIcon; ?>"></i> <?php echo e($app['status']); ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>

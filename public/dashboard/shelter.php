<?php
$pageTitle = "Shelter Dashboard - Kiyome";
include __DIR__ . '/../../includes/header.php';
requireRole('shelter');

$shelterId = $_SESSION['user_id'];

// Fetch Shelter Stats
$stmt = $pdo->prepare("SELECT COUNT(*) FROM pets WHERE shelter_id = ?");
$stmt->execute([$shelterId]);
$totalPets = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM adoption_applications aa JOIN pets p ON aa.pet_id = p.id WHERE p.shelter_id = ? AND aa.status = 'pending'");
$stmt->execute([$shelterId]);
$pendingApps = $stmt->fetchColumn();

// Fetch Pets
$stmt = $pdo->prepare("SELECT * FROM pets WHERE shelter_id = ? ORDER BY created_at DESC");
$stmt->execute([$shelterId]);
$pets = $stmt->fetchAll();

// Fetch Applications
$stmt = $pdo->prepare("
    SELECT aa.*, p.name as pet_name
    FROM adoption_applications aa
    JOIN pets p ON aa.pet_id = p.id
    WHERE p.shelter_id = ?
    ORDER BY aa.created_at DESC
");
$stmt->execute([$shelterId]);
$applications = $stmt->fetchAll();

// Handle messages
$msg = $_GET['msg'] ?? '';
$error = $_GET['error'] ?? '';
$displayMsg = '';
$msgClass = '';

if ($msg === 'deleted') {
    $displayMsg = "Pet listing deleted successfully.";
    $msgClass = "background: rgba(16,185,129,0.1); border: 1px solid var(--success); color: var(--success);";
} elseif ($msg === 'status_updated') {
    $displayMsg = "Application status updated and notification sent.";
    $msgClass = "background: rgba(16,185,129,0.1); border: 1px solid var(--success); color: var(--success);";
} elseif ($msg === 'added') {
    $displayMsg = "New pet listing created successfully!";
    $msgClass = "background: rgba(16,185,129,0.1); border: 1px solid var(--success); color: var(--success);";
} elseif ($msg === 'updated') {
    $displayMsg = "Pet details updated successfully.";
    $msgClass = "background: rgba(16,185,129,0.1); border: 1px solid var(--success); color: var(--success);";
} elseif ($error) {
    $displayMsg = htmlspecialchars($error);
    $msgClass = "background: rgba(239, 68, 68, 0.1); border: 1px solid var(--error); color: var(--error);";
}
?>

<?php if ($displayMsg): ?>
    <div style="<?php echo $msgClass; ?> padding: 1rem; border-radius: 12px; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.8rem;">
        <i class="fas <?php echo $error ? 'fa-exclamation-circle' : 'fa-check-circle'; ?>"></i>
        <span><?php echo $displayMsg; ?></span>
    </div>
<?php endif; ?>

<div class="dashboard-header">
    <h1 class="gradient-text">Shelter Dashboard</h1>
    <a href="<?php echo $routeBase; ?>/pets/add.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Pet</a>
</div>

<!-- Stats Grid -->
<div class="dashboard-stats">
    <div class="glass-container" style="text-align: center; padding: 1.5rem;">
        <h3 style="color: var(--text-muted); font-size: 0.9rem; text-transform: uppercase;">Total Pets</h3>
        <p style="font-size: 2.5rem; font-weight: 700; color: var(--primary);"><?php echo $totalPets; ?></p>
    </div>
    <div class="glass-container" style="text-align: center; padding: 1.5rem;">
        <h3 style="color: var(--text-muted); font-size: 0.9rem; text-transform: uppercase;">Pending Apps</h3>
        <p style="font-size: 2.5rem; font-weight: 700; color: var(--accent);"><?php echo $pendingApps; ?></p>
    </div>
    <div class="glass-container" style="text-align: center; padding: 1.5rem;">
        <h3 style="color: var(--text-muted); font-size: 0.9rem; text-transform: uppercase;">Status</h3>
        <p style="font-size: 1.2rem; font-weight: 600; color: var(--success); margin-top: 1rem;"><i class="fas fa-check-circle"></i> Active Shelter</p>
    </div>
</div>

<div class="dashboard-grid">
    <!-- Manage Pets -->
    <section>
        <h2 style="margin-bottom: 1.5rem;"><i class="fas fa-paw"></i> Your Pet Listings</h2>
        <div class="glass-container table-responsive" style="padding: 0;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background: rgba(255,255,255,0.05); border-bottom: 1px solid var(--glass-border);">
                        <th style="padding: 1rem;">Pet</th>
                        <th style="padding: 1rem;">Species</th>
                        <th style="padding: 1rem;">Status</th>
                        <th style="padding: 1rem;">Date Added</th>
                        <th style="padding: 1rem; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($pets)): ?>
                        <tr><td colspan="5" style="padding: 2rem; text-align: center; color: var(--text-muted);">No pets listed yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($pets as $pet): ?>
                        <tr style="border-bottom: 1px solid var(--glass-border);">
                            <td style="padding: 1rem; display: flex; align-items: center; gap: 1rem;">
                                <img src="<?php echo $routeBase . '/image.php?type=pet&id=' . $pet['id']; ?>" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                                <strong><?php echo e($pet['name']); ?></strong>
                            </td>
                            <td style="padding: 1rem;"><?php echo e(ucfirst($pet['species'])); ?></td>
                            <td style="padding: 1rem;">
                                <span style="background: <?php echo $pet['status'] === 'available' ? 'rgba(16,185,129,0.1)' : 'rgba(255,126,95,0.1)'; ?>; color: <?php echo $pet['status'] === 'available' ? 'var(--success)' : 'var(--primary)'; ?>; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">
                                    <?php echo strtoupper($pet['status']); ?>
                                </span>
                            </td>
                            <td style="padding: 1rem; color: var(--text-muted);"><?php echo date('M d, Y', strtotime($pet['created_at'])); ?></td>
                                <td style="padding: 1rem; text-align: right;">
                                    <a href="<?php echo $routeBase; ?>/pets/edit.php?id=<?php echo $pet['id']; ?>" style="color: var(--accent); margin-right: 1rem;"><i class="fas fa-edit"></i></a>
                                    <a href="<?php echo $routeBase; ?>/pets/delete.php?id=<?php echo $pet['id']; ?>&token=<?php echo generateCsrfToken(); ?>" style="color: var(--error);" onclick="return confirm('Are you sure you want to delete this listing?')"><i class="fas fa-trash"></i></a>
                                </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Manage Applications -->
    <section>
        <h2 style="margin-bottom: 1.5rem;"><i class="fas fa-file-signature"></i> Recent Applications</h2>
        <div class="glass-container table-responsive" style="padding: 0;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background: rgba(255,255,255,0.05); border-bottom: 1px solid var(--glass-border);">
                        <th style="padding: 1rem;">Applicant</th>
                        <th style="padding: 1rem;">Pet</th>
                        <th style="padding: 1rem;">Message</th>
                        <th style="padding: 1rem;">Status</th>
                        <th style="padding: 1rem; text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($applications)): ?>
                        <tr><td colspan="5" style="padding: 2rem; text-align: center; color: var(--text-muted);">No applications received yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($applications as $app): ?>
                        <tr style="border-bottom: 1px solid var(--glass-border);">
                            <td style="padding: 1rem;">
                                <strong><?php echo e($app['applicant_name'] ?: 'N/A'); ?></strong><br>
                                <span style="font-size: 0.8rem; color: var(--text-muted);"><i class="fas fa-envelope"></i> <?php echo e($app['applicant_email'] ?: 'N/A'); ?></span><br>
                                <span style="font-size: 0.8rem; color: var(--text-muted);"><i class="fas fa-phone"></i> <?php echo e($app['applicant_phone'] ?: 'N/A'); ?></span>
                            </td>
                            <td style="padding: 1rem;"><?php echo e($app['pet_name']); ?></td>
                            <td style="padding: 1rem; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?php echo e($app['message']); ?>">
                                <?php echo e($app['message'] ?: 'No message'); ?>
                            </td>
                            <td style="padding: 1rem;">
                                <span style="color: <?php echo $app['status'] === 'pending' ? 'var(--primary)' : ($app['status'] === 'approved' ? 'var(--success)' : 'var(--error)'); ?>; font-weight: 600;">
                                    <?php echo strtoupper($app['status']); ?>
                                </span>
                            </td>
                            <td style="padding: 1rem; text-align: right;">
                                <?php 
                                $appStatus = strtolower(trim($app['status'] ?? ''));
                                if ($appStatus === 'pending'): 
                                ?>
                                    <form action="<?php echo $routeBase; ?>/applications/status.php" method="POST" style="display: flex; gap: 8px; justify-content: flex-end;">
                                        <?php echo csrfInput(); ?>
                                        <input type="hidden" name="app_id" value="<?php echo $app['id']; ?>">
                                        <button type="submit" name="status" value="approved" class="btn btn-approve">Approve</button>
                                        <button type="submit" name="status" value="rejected" class="btn btn-reject">Reject</button>
                                    </form>
                                <?php else: ?>
                                    <span style="color: var(--text-muted); font-size: 0.8rem;">Resolved</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>

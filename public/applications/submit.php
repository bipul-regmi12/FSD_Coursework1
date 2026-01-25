<?php
require_once __DIR__ . '/../../includes/header.php';
requireRole('adopter');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Check
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = "Security validation failed. Please try again.";
    } else {
        $petId = $_POST['pet_id'] ?? null;
        $adopterId = $_SESSION['user_id'];
        $message = trim($_POST['message'] ?? '');
        $applicantName = trim($_POST['applicant_name'] ?? '');
        $applicantEmail = trim($_POST['applicant_email'] ?? '');
        $applicantPhone = trim($_POST['applicant_phone'] ?? '');

        if (!$petId) {
            $error = "Invalid pet selection.";
        } elseif (empty($applicantName) || empty($applicantEmail) || empty($applicantPhone)) {
            $error = "Please fill in all required fields (Name, Email, and Phone).";
        } else {
            // Check if pet exists and is available
            $stmt = $pdo->prepare("SELECT id, name, status FROM pets WHERE id = ?");
            $stmt->execute([$petId]);
            $pet = $stmt->fetch();

            if (!$pet) {
                $error = "Pet not found.";
            } elseif ($pet['status'] !== 'available') {
                $error = "This pet is no longer available for adoption.";
            } else {
                // Check if user already applied for this pet (SQLi prevented by prepared statement)
                $stmt = $pdo->prepare("SELECT id FROM adoption_applications WHERE pet_id = ? AND adopter_id = ?");
                $stmt->execute([$petId, $adopterId]);
                if ($stmt->fetch()) {
                    $error = "You have already applied to adopt this pet.";
                } else {
                    // Create application
                    try {
                        $stmt = $pdo->prepare("INSERT INTO adoption_applications (pet_id, adopter_id, message, applicant_name, applicant_email, applicant_phone, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
                        $stmt->execute([$petId, $adopterId, $message, $applicantName, $applicantEmail, $applicantPhone]);
                        $success = "Your adoption application for " . htmlspecialchars($pet['name']) . " has been submitted! The shelter will review it shortly.";
                    } catch (Exception $e) {
                        $error = "Failed to submit application. Please try again.";
                    }
                }
            }
        }
    }
}
?>

<div style="max-width: 600px; margin: 2rem auto;">
    <a href="<?php echo $routeBase; ?>/pets/browse.php" style="color: var(--text-muted); text-decoration: none; margin-bottom: 2rem; display: inline-block;">
        <i class="fas fa-arrow-left"></i> Back to Browse
    </a>

    <div class="glass-container">
        <?php if ($error): ?>
            <div style="background: rgba(239, 68, 68, 0.2); border: 1px solid var(--error); padding: 1.5rem; border-radius: 12px; text-align: center;">
                <i class="fas fa-times-circle fa-2x" style="color: var(--error); margin-bottom: 1rem;"></i>
                <h3 style="margin-bottom: 0.5rem;">Application Failed</h3>
                <p style="color: #ff9999;"><?php echo e($error); ?></p>
            </div>
        <?php elseif ($success): ?>
            <div style="text-align: center; padding: 2rem;">
                <i class="fas fa-check-circle fa-4x" style="color: var(--success); margin-bottom: 1.5rem;"></i>
                <h2 class="gradient-text" style="margin-bottom: 1rem;">Application Submitted!</h2>
                <p style="color: var(--text-muted); margin-bottom: 2rem;"><?php echo e($success); ?></p>
                <div style="display: flex; gap: 1rem; justify-content: center;">
                    <a href="<?php echo $routeBase; ?>/dashboard/adopter.php" class="btn btn-primary">View My Applications</a>
                    <a href="<?php echo $routeBase; ?>/pets/browse.php" class="btn btn-outline">Browse More Pets</a>
                </div>
            </div>
        <?php else: ?>
            <p style="color: var(--text-muted);">Please use the adopt button on a pet's page to submit an application.</p>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>

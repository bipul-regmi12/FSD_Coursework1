<?php
$pageTitle = "Account Settings";
include __DIR__ . '/../../includes/header.php';

requireLogin();

$user = getCurrentUser();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update_profile') {
        $fullName = trim($_POST['full_name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);

        if (empty($fullName) || empty($email)) {
            $error = "Name and Email are required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format.";
        } else {
            // Check if email is already taken by another user
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
            $stmt->execute([$email, $_SESSION['user_id']]);
            if ($stmt->fetch()) {
                $error = "This email is already in use.";
            } else {
                // Update profile
                $stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ?, phone = ? WHERE id = ?");
                if ($stmt->execute([$fullName, $email, $phone, $_SESSION['user_id']])) {
                    $success = "Profile updated successfully!";
                    $user = getCurrentUser(); // Refresh user data
                } else {
                    $error = "Failed to update profile.";
                }
            }
        }
    } elseif ($action === 'update_password') {
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        // Get actual password hash
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $storedHash = $stmt->fetchColumn();

        if (!password_verify($currentPassword, $storedHash)) {
            $error = "Current password is incorrect.";
        } elseif ($newPassword !== $confirmPassword) {
            $error = "New passwords do not match.";
        } elseif (strlen($newPassword) < 8) {
            $error = "New password must be at least 8 characters.";
        } else {
            $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            if ($stmt->execute([$newHash, $_SESSION['user_id']])) {
                $success = "Password updated successfully!";
            } else {
                $error = "Failed to update password.";
            }
        }
    } elseif ($action === 'update_picture') {
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $fileData = file_get_contents($_FILES['profile_picture']['tmp_name']);
            $stmt = $pdo->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
            if ($stmt->execute([$fileData, $_SESSION['user_id']])) {
                $success = "Profile picture updated!";
                $user = getCurrentUser();
            } else {
                $error = "Failed to upload picture.";
            }
        } else {
            $error = "Please select a valid image file.";
        }
    }
}
?>

<div style="max-width: 800px; margin: 0 auto;">
    <h2 class="gradient-text" style="margin-bottom: 2rem;">Account Settings</h2>

    <?php if ($error): ?>
        <div style="background: rgba(239, 68, 68, 0.1); border-left: 4px solid #ef4444; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; color: #ef4444;">
            <i class="fas fa-exclamation-circle"></i> <?php echo e($error); ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div style="background: rgba(16, 185, 129, 0.1); border-left: 4px solid #10b981; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; color: #10b981;">
            <i class="fas fa-check-circle"></i> <?php echo e($success); ?>
        </div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
        <!-- Left Column: Avatar -->
        <div>
            <div class="glass-container" style="text-align: center; padding: 2rem;">
                <div style="position: relative; display: inline-block;">
                    <?php if ($user && $user['profile_picture']): ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($user['profile_picture']); ?>" 
                             alt="Profile" 
                             style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 4px solid var(--white); box-shadow: var(--shadow-soft);">
                    <?php else: ?>
                        <div style="width: 150px; height: 150px; border-radius: 50%; background: var(--bg-warm); display: flex; align-items: center; justify-content: center; font-size: 4rem; color: var(--primary); border: 4px solid var(--white); box-shadow: var(--shadow-soft);">
                            <i class="fas fa-user"></i>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" enctype="multipart/form-data" style="margin-top: 1.5rem;">
                        <input type="hidden" name="action" value="update_picture">
                        <label class="btn btn-outline" style="padding: 0.5rem 1rem; font-size: 0.85rem; cursor: pointer;">
                            <i class="fas fa-camera"></i> Change Photo
                            <input type="file" name="profile_picture" style="display: none;" onchange="this.form.submit()">
                        </label>
                    </form>
                </div>
                
                <div style="margin-top: 1.5rem;">
                    <h3 style="margin-bottom: 0.25rem;"><?php echo e($user['full_name']); ?></h3>
                    <p style="color: var(--text-muted); font-size: 0.9rem;"><?php echo e(ucfirst($user['role'])); ?></p>
                </div>
            </div>
        </div>

        <!-- Right Column: Forms -->
        <div style="display: flex; flex-direction: column; gap: 2rem;">
            <!-- Profile Info Form -->
            <div class="glass-container">
                <h4 style="margin-bottom: 1.5rem;"><i class="fas fa-user-edit" style="color: var(--primary);"></i> Personal Information</h4>
                <form method="POST">
                    <input type="hidden" name="action" value="update_profile">
                    
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="full_name" value="<?php echo e($user['full_name']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" value="<?php echo e($user['email']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" value="<?php echo e($user['phone'] ?? ''); ?>" placeholder="e.g. +1 234 567 890">
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>

            <!-- Password Change Form -->
            <div class="glass-container">
                <h4 style="margin-bottom: 1.5rem;"><i class="fas fa-key" style="color: var(--primary);"></i> Change Password</h4>
                <form method="POST">
                    <input type="hidden" name="action" value="update_password">
                    
                    <div class="form-group">
                        <label>Current Password</label>
                        <input type="password" name="current_password" required>
                    </div>
                    
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="new_password" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <input type="password" name="confirm_password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-outline" style="border-color: var(--accent); color: var(--accent);">
                        Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>

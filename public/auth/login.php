<?php
$pageTitle = "Login - Kiyome";
include __DIR__ . '/../../includes/header.php';

if (isLoggedIn()) {
    redirect('/');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = "CSRF token validation failed.";
    } else {
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $role = $_POST['role'] ?? '';

        if (empty($email) || empty($password) || empty($role)) {
            $error = "Please enter email, password, and select your role.";
        } else {
            $stmt = $pdo->prepare("SELECT id, password, role, full_name FROM users WHERE email = ? AND role = ?");
            $stmt->execute([$email, $role]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['user_name'] = $user['full_name'];

                if ($user['role'] === 'shelter') {
                    redirect('/dashboard/shelter.php');
                } else {
                    redirect('/dashboard/adopter.php');
                }
            } else {
                $error = "Invalid email, password, or role combination.";
            }
        }
    }
}
?>

<div style="max-width: 500px; margin: 6rem auto;">
    <div class="glass-container" style="padding: 4rem;">
        <h2 style="text-align: center; font-size: 2.2rem; margin-bottom: 3rem;">Welcome <span style="color: var(--primary);">Back</span></h2>
        
        <?php if ($error): ?>
            <div style="background: #FFEBEE; border: 1px solid #FFCDD2; padding: 1rem; border-radius: 10px; margin-bottom: 2rem; color: #B71C1C; font-size: 0.9rem; text-align: center;">
                <i class="fas fa-exclamation-circle"></i> <?php echo e($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <?php echo csrfInput(); ?>
            <div class="form-group">
                <label>Identify yourself as</label>
                <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
                    <label style="flex: 1; text-align: center; display: block; padding: 1.5rem; border: 2px solid #EEE; border-radius: 20px; cursor: pointer; transition: all 0.3s;" class="role-option">
                        <input type="radio" name="role" value="adopter" required onchange="updateRoleSelection(this)" style="margin-bottom: 0.5rem;"><br>
                        <i class="fas fa-heart" style="color: var(--accent); font-size: 1.2rem;"></i><br>
                        <strong>Adopter</strong>
                    </label>
                    <label style="flex: 1; text-align: center; display: block; padding: 1.5rem; border: 2px solid #EEE; border-radius: 20px; cursor: pointer; transition: all 0.3s;" class="role-option">
                        <input type="radio" name="role" value="shelter" required onchange="updateRoleSelection(this)" style="margin-bottom: 0.5rem;"><br>
                        <i class="fas fa-home" style="color: var(--primary); font-size: 1.2rem;"></i><br>
                        <strong>Shelter</strong>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="name@email.com">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="••••••••">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 1.2rem; font-size: 1.1rem; border-radius: 20px;">
                Sign In
            </button>
        </form>
        
        <p style="text-align: center; margin-top: 2.5rem; color: var(--text-muted); font-size: 0.95rem;">
            New here? <a href="<?php echo $routeBase; ?>/auth/register.php" style="color: var(--primary); font-weight: 700; text-decoration: none;">Create an account</a>
        </p>
    </div>
</div>

<script src="<?php echo $assetBase; ?>/assets/js/auth.js" defer></script>
<?php include __DIR__ . '/../../includes/footer.php'; ?>

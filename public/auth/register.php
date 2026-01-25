<?php
$pageTitle = "Join Kiyome - Registration";
include __DIR__ . '/../../includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $fullName = trim($_POST['full_name']);
    $role = $_POST['role'];

    // Basic validation
    if (empty($email) || empty($password) || empty($fullName)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND role = ?");
        $stmt->execute([$email, $role]);
        if ($stmt->fetch()) {
            $error = "This email is already registered as " . $role . ". Try a different role or login.";
        } else {
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            try {
                $pdo->beginTransaction();

                // Insert into users
                $stmt = $pdo->prepare("INSERT INTO users (email, password, role, full_name) VALUES (?, ?, ?, ?)");
                $stmt->execute([$email, $hashedPassword, $role, $fullName]);
                $userId = $pdo->lastInsertId();

                // If role is shelter, insert into shelters table
                if ($role === 'shelter') {
                    $shelterName = trim($_POST['shelter_name']);
                    $city = trim($_POST['city']);
                    $state = trim($_POST['state']);
                    $description = trim($_POST['description']);
                    
                    $stmt = $pdo->prepare("INSERT INTO shelters (user_id, shelter_name, city, state, description) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$userId, $shelterName, $city, $state, $description]);
                }

                $pdo->commit();
                $success = "Registration successful! You can now login.";
            } catch (Exception $e) {
                $pdo->rollBack();
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<div style="max-width: 600px; margin: 0 auto;">
    <div class="glass-container">
        <h2 class="gradient-text" style="margin-bottom: 2rem; text-align: center;">Create Your Account</h2>
        
        <?php if ($error): ?>
            <div style="background: rgba(239, 68, 68, 0.2); border: 1px solid var(--error); padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; color: #ff9999;">
                <?php echo e($error); ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div style="background: rgba(16, 185, 129, 0.2); border: 1px solid var(--success); padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; color: #99ffd6;">
                <?php echo e($success); ?> <a href="<?php echo $routeBase; ?>/auth/login.php" style="color: white; font-weight: bold;">Login here</a>
            </div>
        <?php endif; ?>

        <form method="POST" action=""><?php echo csrfInput(); ?>
            <div class="form-group">
                <label>Join as</label>
                <select name="role" id="roleSelect" onchange="toggleShelterFields()">
                    <option value="adopter">I want to adopt a pet</option>
                    <option value="shelter">I am a shelter/rescue</option>
                </select>
            </div>

            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" required placeholder="John Doe">
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" required placeholder="john@example.com">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="••••••••">
            </div>

            <div id="shelterFields" style="display: none; border-top: 1px solid var(--glass-border); padding-top: 1.5rem; margin-top: 1.5rem;">
                <h4 style="margin-bottom: 1rem; color: var(--primary);">Shelter Information</h4>
                <div class="form-group">
                    <label>Shelter Name</label>
                    <input type="text" name="shelter_name" placeholder="Happy Tails Rescue">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>City</label>
                        <input type="text" name="city">
                    </div>
                    <div class="form-group">
                        <label>State</label>
                        <input type="text" name="state">
                    </div>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3"></textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 1rem;">Register</button>
        </form>
        
        <p style="text-align: center; margin-top: 1.5rem; color: var(--text-muted);">
            Already have an account? <a href="<?php echo $routeBase; ?>/auth/login.php" style="color: var(--primary); text-decoration: none;">Login</a>
        </p>
    </div>
</div>

<script src="<?php echo $assetBase; ?>/assets/js/auth.js" defer></script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>

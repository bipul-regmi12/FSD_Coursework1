<?php
$pageTitle = "Add New Pet - Kiyome";
include __DIR__ . '/../../includes/header.php';
requireRole('shelter');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Check
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = "Security validation failed. Please try again.";
    } else {
        $name = trim($_POST['name']);
    $species = $_POST['species'];
    $breed = trim($_POST['breed']);
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $size = $_POST['size'];
    $city = trim($_POST['city']);
    $state = trim($_POST['state']);
    $fee = (float)$_POST['fee'];
    $description = trim($_POST['description']);
    $shelterId = $_SESSION['user_id'];

    try {
        $pdo->beginTransaction();

        // Handle Main Image Upload
        $mainImageBlob = null;
        if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            $ext = strtolower(pathinfo($_FILES['main_image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, $allowed)) {
                $mainImageBlob = file_get_contents($_FILES['main_image']['tmp_name']);
            } else {
                throw new Exception("Invalid file type for main image.");
            }
        }

        // Insert Pet
        $stmt = $pdo->prepare("
            INSERT INTO pets (shelter_id, name, species, breed, age_range, gender, size, location_city, location_state, adoption_fee, description, main_image)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$shelterId, $name, $species, $breed, $age, $gender, $size, $city, $state, $fee, $description, $mainImageBlob]);
        
        $pdo->commit();
        redirect('/dashboard/shelter.php?msg=added'); // Redirect with message
    } catch (Exception $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        $error = $e->getMessage();
        }
    }
}
?>

<div style="max-width: 800px; margin: 0 auto;">
    <a href="<?php echo $routeBase; ?>/dashboard/shelter.php" style="color: var(--text-muted); text-decoration: none; margin-bottom: 2rem; display: inline-block;">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>
    
    <div class="glass-container">
        <h2 class="gradient-text" style="margin-bottom: 2rem;">Add a New Pet</h2>

        <?php if ($error): ?>
            <div style="background: rgba(239, 68, 68, 0.2); border: 1px solid var(--error); padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; color: #ff9999;">
                <?php echo e($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data"><?php echo csrfInput(); ?>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label>Pet Name</label>
                    <input type="text" name="name" required placeholder="Buddy">
                </div>
                <div class="form-group">
                    <label>Species</label>
                    <select name="species" required>
                        <option value="dog">Dog</option>
                        <option value="cat">Cat</option>
                        <option value="rabbit">Rabbit</option>
                        <option value="bird">Bird</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label>Breed (Optional)</label>
                    <input type="text" name="breed" placeholder="Golden Retriever">
                </div>
                <div class="form-group">
                    <label>Age Range</label>
                    <select name="age" required>
                        <option value="baby">Baby</option>
                        <option value="young">Young</option>
                        <option value="adult">Adult</option>
                        <option value="senior">Senior</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender" required>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label>Size</label>
                    <select name="size" required>
                        <option value="small">Small</option>
                        <option value="medium">Medium</option>
                        <option value="large">Large</option>
                        <option value="extra_large">Extra Large</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Adoption Fee ($)</label>
                    <input type="number" step="0.01" name="fee" value="0.00">
                </div>
                <div class="form-group">
                    <label>City</label>
                    <input type="text" name="city" required>
                </div>
            </div>

            <div class="form-group">
                <label>State</label>
                <input type="text" name="state" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="5" required placeholder="Tell potential adopters about this pet's personality, health, and history..."></textarea>
            </div>

            <div class="form-group">
                <label>Main Photo (Shows in search results)</label>
                <input type="file" name="main_image" accept="image/*" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 2rem;">List Pet for Adoption</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>

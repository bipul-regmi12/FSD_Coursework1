<?php
$pageTitle = "Edit Pet - Kiyome";
include __DIR__ . '/../../includes/header.php';
requireRole('shelter');

$petId = isset($_GET['id']) ? (int)$_GET['id'] : null;
$shelterId = $_SESSION['user_id'];

if (!$petId) {
    redirect('/dashboard/shelter.php');
}

// Fetch current pet data + IDOR check (ownership verification)
$stmt = $pdo->prepare("SELECT * FROM pets WHERE id = ? AND shelter_id = ?");
$stmt->execute([$petId, $shelterId]);
$pet = $stmt->fetch();

if (!$pet) {
    redirect('/dashboard/shelter.php?error=pet_not_found_or_unauthorized');
}

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
        $status = $_POST['status'];

        try {
            $pdo->beginTransaction();

            // Handle Main Image Update
            $mainImageBlob = $pet['main_image']; 
            if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] === 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
                $ext = strtolower(pathinfo($_FILES['main_image']['name'], PATHINFO_EXTENSION));
                if (in_array($ext, $allowed)) {
                    $mainImageBlob = file_get_contents($_FILES['main_image']['tmp_name']);
                } else {
                    throw new Exception("Invalid file type for image.");
                }
            }

            // Update Pet (SQL Injection prevented by prepared statement)
            $stmt = $pdo->prepare("
                UPDATE pets SET 
                    name = ?, species = ?, breed = ?, age_range = ?, gender = ?, 
                    size = ?, location_city = ?, location_state = ?, adoption_fee = ?, 
                    description = ?, main_image = ?, status = ?
                WHERE id = ? AND shelter_id = ?
            ");
            $stmt->execute([
                $name, $species, $breed, $age, $gender, 
                $size, $city, $state, $fee, 
                $description, $mainImageBlob, $status,
                $petId, $shelterId
            ]);

            $pdo->commit();
            $success = "Pet updated successfully!";
            $pet['main_image'] = $mainImageBlob;
            $pet['name'] = $name;
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
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 2rem;">
            <h2 class="gradient-text">Edit <?php echo e($pet['name']); ?></h2>
            <img src="<?php echo $routeBase . '/image.php?type=pet&id=' . $pet['id']; ?>&t=<?php echo time(); ?>" style="width: 80px; height: 80px; border-radius: 12px; object-fit: cover; border: 2px solid var(--primary);">
        </div>

        <?php if ($error): ?>
            <div style="background: rgba(239, 68, 68, 0.2); border: 1px solid var(--error); padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; color: #ff9999;">
                <?php echo e($error); ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div style="background: rgba(16, 185, 129, 0.2); border: 1px solid var(--success); padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; color: #99ffd6;">
                <?php echo e($success); ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <?php echo csrfInput(); ?>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label>Pet Name</label>
                    <input type="text" name="name" required value="<?php echo e($pet['name']); ?>">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" required>
                        <option value="available" <?php echo $pet['status'] === 'available' ? 'selected' : ''; ?>>Available</option>
                        <option value="pending" <?php echo $pet['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="adopted" <?php echo $pet['status'] === 'adopted' ? 'selected' : ''; ?>>Adopted</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label>Species</label>
                    <select name="species" required>
                        <option value="dog" <?php echo $pet['species'] === 'dog' ? 'selected' : ''; ?>>Dog</option>
                        <option value="cat" <?php echo $pet['species'] === 'cat' ? 'selected' : ''; ?>>Cat</option>
                        <option value="rabbit" <?php echo $pet['species'] === 'rabbit' ? 'selected' : ''; ?>>Rabbit</option>
                        <option value="bird" <?php echo $pet['species'] === 'bird' ? 'selected' : ''; ?>>Bird</option>
                        <option value="other" <?php echo $pet['species'] === 'other' ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Breed (Optional)</label>
                    <input type="text" name="breed" value="<?php echo e($pet['breed']); ?>">
                </div>
                <div class="form-group">
                    <label>Age Range</label>
                    <select name="age" required>
                        <option value="baby" <?php echo $pet['age_range'] === 'baby' ? 'selected' : ''; ?>>Baby</option>
                        <option value="young" <?php echo $pet['age_range'] === 'young' ? 'selected' : ''; ?>>Young</option>
                        <option value="adult" <?php echo $pet['age_range'] === 'adult' ? 'selected' : ''; ?>>Adult</option>
                        <option value="senior" <?php echo $pet['age_range'] === 'senior' ? 'selected' : ''; ?>>Senior</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender" required>
                        <option value="male" <?php echo $pet['gender'] === 'male' ? 'selected' : ''; ?>>Male</option>
                        <option value="female" <?php echo $pet['gender'] === 'female' ? 'selected' : ''; ?>>Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Size</label>
                    <select name="size" required>
                        <option value="small" <?php echo $pet['size'] === 'small' ? 'selected' : ''; ?>>Small</option>
                        <option value="medium" <?php echo $pet['size'] === 'medium' ? 'selected' : ''; ?>>Medium</option>
                        <option value="large" <?php echo $pet['size'] === 'large' ? 'selected' : ''; ?>>Large</option>
                        <option value="extra_large" <?php echo $pet['size'] === 'extra_large' ? 'selected' : ''; ?>>Extra Large</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Adoption Fee ($)</label>
                    <input type="number" step="0.01" name="fee" value="<?php echo $pet['adoption_fee']; ?>">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label>City</label>
                    <input type="text" name="city" required value="<?php echo e($pet['location_city']); ?>">
                </div>
                <div class="form-group">
                    <label>State</label>
                    <input type="text" name="state" required value="<?php echo e($pet['location_state']); ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="5" required><?php echo e($pet['description']); ?></textarea>
            </div>

            <div class="form-group">
                <label>Update Main Photo (Leave blank to keep current)</label>
                <input type="file" name="main_image" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 2rem;">Update Details</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>

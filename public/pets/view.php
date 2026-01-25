<?php
$pageTitle = "Pet Details";
include __DIR__ . '/../../includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
if (!$id) {
    echo '<div class="container" style="padding: 4rem; text-align: center;"><h1>Pet Not Found</h1></div>';
    include __DIR__ . '/../../includes/footer.php';
    exit;
}

$stmt = $pdo->prepare("
    SELECT p.*, s.shelter_name, s.city as shelter_city, s.state as shelter_state
    FROM pets p
    JOIN shelters s ON p.shelter_id = s.user_id
    WHERE p.id = ?
");
$stmt->execute([$id]);
$pet = $stmt->fetch();

if (!$pet) {
    echo '<div class="container" style="padding: 4rem; text-align: center;"><h1>Pet Not Found</h1></div>';
    include __DIR__ . '/../../includes/footer.php';
    exit;
}

$img = $routeBase . '/image.php?type=pet&id=' . $pet['id'];
$userName = $_SESSION['user_name'] ?? '';
$userEmail = '';
if (isLoggedIn()) {
    $stmt = $pdo->prepare("SELECT email FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $userEmail = $stmt->fetchColumn();
}
?>

<div class="container" style="padding-bottom: 6rem; padding-top: 2rem;">
    <a href="<?php echo $routeBase; ?>/pets/browse.php" class="btn btn-outline" style="margin-bottom: 3rem; border-radius: 12px; font-size: 0.9rem;">
        <i class="fas fa-arrow-left"></i> Back to Browse
    </a>

    <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 4rem; align-items: start;">
        <div>
            <div style="position: relative; border-radius: 30px; overflow: hidden; box-shadow: var(--shadow-hover);">
                <img src="<?php echo e($img); ?>" alt="<?php echo e($pet['name']); ?>" style="width: 100%; display: block;">
                <div style="position: absolute; bottom: 20px; left: 20px;">
                    <span style="background: var(--primary); color: white; padding: 8px 20px; border-radius: 50px; font-weight: 700; text-transform: uppercase; font-size: 0.8rem; box-shadow: 0 4px 10px rgba(0,0,0,0.2);">
                        <?php echo e($pet['status']); ?>
                    </span>
                </div>
            </div>
            
            <div style="margin-top: 3rem; background: white; padding: 3rem; border-radius: 30px; box-shadow: var(--shadow-soft);">
                <h3 style="margin-bottom: 1.5rem; font-size: 1.8rem; border-bottom: 2px solid var(--bg-warm); padding-bottom: 1rem;">About <?php echo e($pet['name']); ?></h3>
                <p style="color: var(--text-main); font-size: 1.1rem; line-height: 1.8;">
                    <?php echo nl2br(e($pet['description'])); ?>
                </p>
            </div>
        </div>
        
        <div style="position: sticky; top: 120px;">
            <div style="background: white; padding: 3rem; border-radius: 30px; box-shadow: var(--shadow-soft); margin-bottom: 2.5rem;">
                <h1 style="font-size: 3.5rem; margin-bottom: 0.5rem; color: var(--secondary);"><?php echo e($pet['name']); ?></h1>
                <p style="font-size: 1.25rem; color: var(--text-muted); margin-bottom: 2rem;">
                    <i class="fas fa-map-marker-alt" style="color: var(--primary);"></i> 
                    <?php echo e($pet['location_city']); ?>, <?php echo e($pet['location_state']); ?>
                </p>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2.5rem;">
                    <div style="background: #F9F9F9; padding: 1.25rem; border-radius: 15px; border: 1px solid #F0F0F0;">
                        <span style="display: block; color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">Breed</span>
                        <strong style="font-size: 1.1rem;"><?php echo e($pet['breed'] ?: 'Mixed'); ?></strong>
                    </div>
                    <div style="background: #F9F9F9; padding: 1.25rem; border-radius: 15px; border: 1px solid #F0F0F0;">
                        <span style="display: block; color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">Age</span>
                        <strong style="font-size: 1.1rem;"><?php echo e(ucfirst($pet['age_range'])); ?></strong>
                    </div>
                    <div style="background: #F9F9F9; padding: 1.25rem; border-radius: 15px; border: 1px solid #F0F0F0;">
                        <span style="display: block; color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">Gender</span>
                        <strong style="font-size: 1.1rem;"><?php echo e(ucfirst($pet['gender'])); ?></strong>
                    </div>
                    <div style="background: #F9F9F9; padding: 1.25rem; border-radius: 15px; border: 1px solid #F0F0F0;">
                        <span style="display: block; color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">Size</span>
                        <strong style="font-size: 1.1rem;"><?php echo e(ucfirst($pet['size'])); ?></strong>
                    </div>
                </div>

                <div style="background: #E8F5E9; padding: 2rem; border-radius: 20px; text-align: center; margin-bottom: 2.5rem;">
                    <span style="color: var(--primary-dark); font-weight: 600;">Adoption Fee</span>
                    <h2 style="font-size: 2.5rem; color: var(--primary-dark); margin: 0;"><?php echo formatPrice($pet['adoption_fee']); ?></h2>
                </div>

                <?php if (isLoggedIn()): ?>
                    <?php if (getUserRole() === 'adopter'): ?>
                        <div style="margin-top: 2rem;">
                            <h3 style="margin-bottom: 1.5rem; text-align: center;">Ready to meet <?php echo e($pet['name']); ?>?</h3>
                            <form action="<?php echo $routeBase; ?>/applications/submit.php" method="POST">
                                <?php echo csrfInput(); ?>
                                <input type="hidden" name="pet_id" value="<?php echo $pet['id']; ?>">
                                
                                <div class="form-group">
                                    <label>Full Name</label>
                                    <input type="text" name="applicant_name" required value="<?php echo e($userName); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input type="email" name="applicant_email" required value="<?php echo e($userEmail); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="tel" name="applicant_phone" required placeholder="+1 (555) 000-0000">
                                </div>
                                <div class="form-group">
                                    <label>Your Message</label>
                                    <textarea name="message" rows="4" placeholder="Tell us why you'd be a great match for <?php echo e($pet['name']); ?>..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 1.25rem; font-size: 1.1rem; border-radius: 15px;">
                                    Meet <?php echo e($pet['name']); ?> <i class="fas fa-heart"></i>
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="<?php echo $routeBase; ?>/auth/login.php" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 1.25rem;">Login to Adopt</a>
                <?php endif; ?>
            </div>

            <div style="background: #FFF8E1; padding: 2rem; border-radius: 25px; border: 1px solid #FFE082; display: flex; gap: 1.5rem; align-items: center;">
                <div style="width: 60px; height: 60px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #FFA000; flex-shrink: 0;">
                    <i class="fas fa-home"></i>
                </div>
                <div>
                    <h4 style="margin: 0; color: #795548;">Listed by <?php echo e($pet['shelter_name']); ?></h4>
                    <p style="margin: 5px 0 0 0; font-size: 0.9rem; color: #8D6E63;">Verified Rescue Organization</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>

<?php
$pageTitle = "Kiyome - Find Your Forever Friend";
include_once __DIR__ . '/../includes/header.php';

// Fetch featured pets (randomly pick 4 available)
$stmt = $pdo->query("SELECT * FROM pets WHERE status = 'available' ORDER BY RAND() LIMIT 4");
$featuredPets = $stmt->fetchAll();
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-slider-container">
        <div class="hero-slider" id="heroSlider">
            <div class="slider-item active"
                style="background-image: url('<?php echo $assetBase; ?>/assets/img/slider1.jpg');"></div>
            <div class="slider-item" style="background-image: url('<?php echo $assetBase; ?>/assets/img/slider2.jpg');">
            </div>
            <div class="slider-item" style="background-image: url('<?php echo $assetBase; ?>/assets/img/slider3.jpg');">
            </div>
            <div class="slider-item" style="background-image: url('<?php echo $assetBase; ?>/assets/img/slider4.jpg');">
            </div>
            <div class="slider-item" style="background-image: url('<?php echo $assetBase; ?>/assets/img/slider5.jpeg');">
            </div>
        </div>
        <div class="slider-dots">
            <span class="dot active" data-index="0"></span>
            <span class="dot" data-index="1"></span>
            <span class="dot" data-index="2"></span>
            <span class="dot" data-index="3"></span>
        </div>
    </div>

    <div class="container hero-content">
        <h1 class="hero-title fade-in">Find Your <br><span class="gradient-text">Forever Friend</span></h1>
        <p class="hero-subtitle fade-in">
            Every pet deserves a loving home. Join our community of life-savers and find your perfect companion today.
        </p>
        <div class="hero-buttons fade-in">
            <?php if (isLoggedIn() && getUserRole() === 'shelter'): ?>
                <a href="<?php echo $routeBase; ?>/pets/add.php" class="btn btn-primary"
                    style="padding: 1rem 3rem; font-size: 1.2rem;">
                    List a Pet <i class="fas fa-plus"></i>
                </a>
            <?php else: ?>
                <a href="<?php echo $routeBase; ?>/pets/browse.php" class="btn btn-primary"
                    style="padding: 1.2rem 3.5rem; font-size: 1.3rem;">
                    Adopt Now <i class="fas fa-heart"></i>
                </a>
                <?php if (!isLoggedIn()): ?>
                    <a href="<?php echo $routeBase; ?>/pets/add.php" class="btn btn-secondary"
                        style="padding: 1.2rem 3.5rem; font-size: 1.3rem;">
                        List Now <i class="fas fa-plus"></i>
                    </a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Stats Bar -->
<div class="container" style="margin-top: -5rem; position: relative; z-index: 50;">
    <div class="glass-container"
        style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; text-align: center; border-radius: 30px; border: 1px solid #FFF;">
        <div>
            <h2 style="color: var(--primary); font-size: 2.8rem; margin-bottom: 0.5rem;">5,000+</h2>
            <p
                style="color: var(--text-muted); font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px;">
                Happy Families</p>
        </div>
        <div style="border-left: 2px solid var(--bg-warm); border-right: 2px solid var(--bg-warm);">
            <h2 style="color: var(--primary); font-size: 2.8rem; margin-bottom: 0.5rem;">120+</h2>
            <p
                style="color: var(--text-muted); font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px;">
                Verified Shelters</p>
        </div>
        <div>
            <h2 style="color: var(--primary); font-size: 2.8rem; margin-bottom: 0.5rem;">24/7</h2>
            <p
                style="color: var(--text-muted); font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px;">
                Expert Support</p>
        </div>
    </div>
</div>

<!-- Featured Pets Section -->
<section style="padding: 10rem 0;">
    <div class="container">
        <div style="text-align: center; margin-bottom: 5rem;">
            <span
                style="color: var(--primary); font-weight: 800; text-transform: uppercase; font-size: 0.9rem; letter-spacing: 2px;">Recently
                Added</span>
            <h2 style="font-size: 3rem; margin-top: 1rem; color: var(--secondary);">Meet Our <span
                    style="color: var(--primary);">Newest Members</span></h2>
        </div>

        <div class="pets-grid"
            style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 3rem;">
            <?php empty($featuredPets) ? print ('<p style="grid-column: 1/-1; text-align: center; color: var(--text-muted);">No pets available at the moment. Check back soon!</p>') : ''; ?>
            <?php foreach ($featuredPets as $pet): ?>
                <div class="pet-card">
                    <img src="<?php echo $routeBase . '/image.php?type=pet&id=' . $pet['id']; ?>" class="pet-image">
                    <div class="pet-info">
                        <div
                            style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                            <h3 style="margin: 0; font-size: 1.5rem;"><?php echo e($pet['name']); ?></h3>
                            <span class="pet-tag"><?php echo e(ucfirst($pet['species'])); ?></span>
                        </div>
                        <p style="color: var(--text-muted); font-size: 1rem; margin-bottom: 2rem;">
                            <i class="fas fa-map-marker-alt" style="color: var(--primary); margin-right: 0.5rem;"></i>
                            <?php echo e($pet['location_city']); ?>, <?php echo e($pet['location_state']); ?>
                        </p>
                        <a href="<?php echo $routeBase; ?>/pets/view.php?id=<?php echo $pet['id']; ?>"
                            class="btn btn-secondary"
                            style="width: 100%; justify-content: center; font-size: 1.1rem; border-radius: 15px;">View
                            Details</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="text-align: center; margin-top: 6rem;">
            <a href="<?php echo $routeBase; ?>/pets/browse.php" class="btn btn-outline"
                style="padding: 1rem 3rem;">Explore All Pets <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</section>

<!-- Success Stories -->
<section class="success-stories">
    <div class="container">
        <div style="text-align: center; margin-bottom: 6rem;">
            <h2 style="font-size: 3rem; margin-bottom: 1.5rem;">Family <span style="color: var(--primary);">Success
                    Stories</span></h2>
            <p style="color: var(--text-muted); font-size: 1.2rem; max-width: 600px; margin: 0 auto;">Nothing makes us
                happier than seeing our pets find their perfect forever homes.</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 4rem;">
            <div class="testimonial-card">
                <i class="fas fa-quote-left"
                    style="font-size: 3rem; color: #F1F8F1; margin-bottom: 2rem; display: block;"></i>
                <p
                    style="font-style: italic; color: var(--text-main); font-size: 1.2rem; margin-bottom: 2.5rem; line-height: 1.8;">
                    "Finding Bella through Kiyome was the best decision we ever made. The process was so transparent and
                    full of care. Bella is now the heart of our home!"</p>
                <div style="display: flex; align-items: center; justify-content: center; gap: 1.5rem;">
                    <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100"
                        class="testimonial-img">
                    <div style="text-align: left;">
                        <h4 style="margin: 0; font-size: 1.1rem;">Sarah Johnson</h4>
                        <p style="margin: 0; font-size: 0.9rem; color: var(--text-muted);">Adopted Bella (Golden
                            Retriever)</p>
                    </div>
                </div>
            </div>

            <div class="testimonial-card">
                <i class="fas fa-quote-left"
                    style="font-size: 3rem; color: #F1F8F1; margin-bottom: 2rem; display: block;"></i>
                <p
                    style="font-style: italic; color: var(--text-main); font-size: 1.2rem; margin-bottom: 2.5rem; line-height: 1.8;">
                    "Kiyome made us feel at ease from the first click. The verified shelter system gave us confidence,
                    and Cooper has been a perfect addition to our family."</p>
                <div style="display: flex; align-items: center; justify-content: center; gap: 1.5rem;">
                    <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100"
                        class="testimonial-img">
                    <div style="text-align: left;">
                        <h4 style="margin: 0; font-size: 1.1rem;">Mike Thompson</h4>
                        <p style="margin: 0; font-size: 0.9rem; color: var(--text-muted);">Adopted Cooper (Labrador)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
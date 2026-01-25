<?php
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: strict-origin-when-cross-origin");

require_once __DIR__ . '/functions.php';

// Derive route and asset base
$rawDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
if (preg_match('#^(.*/public)(?:/.*)?$#', $rawDir, $m)) {
    $routeBase = rtrim($m[1], '/');                 
    $assetBase = preg_replace('#/public$#', '', $routeBase); 
} else {
    $routeBase = rtrim($rawDir, '/');
    $assetBase = $routeBase;
}

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/../config/db.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="<?php echo $assetBase; ?>/assets/img/favicon.png">
    <title><?php echo $pageTitle ?? 'Kiyome - Pet Adoption'; ?></title>
    <script>
        const routeBase = "<?php echo $routeBase; ?>";
        const assetBase = "<?php echo $assetBase; ?>";
    </script>
    <script src="<?php echo $assetBase; ?>/assets/js/main.js" defer></script>
    <meta name="description" content="Find your perfect furry companion today on Kiyome.">
    <link rel="stylesheet" href="<?php echo $assetBase; ?>/assets/css/index.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <header>
        <div class="container">
            <nav>
                <a href="<?php echo $routeBase; ?>/" class="logo">
                    <i class="fas fa-paw gradient-text"></i>
                    <span class="gradient-text">Kiyome</span>
                </a>
                
                <!-- Mobile Menu Button -->
                <button id="mobileMenuBtn" class="mobile-menu-btn" aria-label="Toggle Menu">
                    <i class="fas fa-bars"></i>
                </button>

                <ul class="nav-links">
                    <li><a href="<?php echo $routeBase; ?>/pets/browse.php">Browse Pets</a></li>
                    <?php if (isLoggedIn()): ?>
                        <?php if (getUserRole() === 'shelter'): ?>
                            <li><a href="<?php echo $routeBase; ?>/dashboard/shelter.php">Dashboard</a></li>
                        <?php else: ?>
                            <li><a href="<?php echo $routeBase; ?>/dashboard/adopter.php">My Applications</a></li>
                        <?php endif; ?>
                        <li><a href="<?php echo $routeBase; ?>/auth/logout.php" class="btn btn-outline ">Logout</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo $routeBase; ?>/auth/login.php">Login</a></li>
                        <li><a href="<?php echo $routeBase; ?>/auth/register.php" class="btn btn-outline ">Join Us</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main class="container fade-in" style="padding-top: 2rem; padding-bottom: 4rem;">

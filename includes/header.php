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
                    <?php if (isLoggedIn()): 
                        $currentUser = getCurrentUser();
                    ?>
                        <?php if (getUserRole() === 'shelter'): ?>
                            <li><a href="<?php echo $routeBase; ?>/dashboard/shelter.php">Dashboard</a></li>
                        <?php else: ?>
                            <li><a href="<?php echo $routeBase; ?>/dashboard/adopter.php">My Applications</a></li>
                        <?php endif; ?>
                        
                        <li class="user-profile-dropdown">
                            <div class="user-profile-trigger" id="userProfileTrigger">
                                <?php if ($currentUser && $currentUser['profile_picture']): ?>
                                    <img src="data:image/jpeg;base64,<?php echo base64_encode($currentUser['profile_picture']); ?>" alt="Profile" class="user-avatar">
                                <?php else: ?>
                                    <div class="user-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>
                                <?php endif; ?>
                                <span class="user-name"><?php echo e($currentUser['full_name'] ?? 'User'); ?></span>
                                <i class="fas fa-chevron-down" style="font-size: 0.7rem; color: var(--text-muted);"></i>
                            </div>
                            
                            <div class="dropdown-menu" id="userDropdown">
                                <a href="<?php echo $routeBase; ?>/auth/settings.php" class="dropdown-item">
                                    <i class="fas fa-cog"></i>
                                    Settings
                                </a>
                                <div class="dropdown-divider"></div>
                                <a href="<?php echo $routeBase; ?>/auth/logout.php" class="dropdown-item logout">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    <?php else: ?>
                        <li><a href="<?php echo $routeBase; ?>/auth/login.php">Login</a></li>
                        <li><a href="<?php echo $routeBase; ?>/auth/register.php" class="btn btn-outline ">Join Us</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main class="container fade-in" style="padding-top: 2rem; padding-bottom: 4rem;">

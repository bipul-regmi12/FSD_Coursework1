<?php
// includes/footer.php
?>
</main>
<footer>
    <div class="container">
        <div class="footer-grid">
            <div>
                <h4 style="font-size: 1.5rem; margin-bottom: 1.5rem;"><i class="fas fa-paw"></i> Kiyome</h4>
                <p style="color: rgba(255,255,255,0.7); line-height: 1.8;">
                    We believe every pet deserves a warm home and every family deserves a best friend. We connect
                    verified shelters with compassionate adopters.
                </p>
            </div>
            <div>
                <h4 style="margin-bottom: 1.5rem;">Explore</h4>
                <ul style="list-style: none;">
                    <li style="margin-bottom: 1rem;"><a href="<?php echo $routeBase; ?>/"
                            style="text-decoration: none; color: rgba(255,255,255,0.7);"><i class="fas fa-home"
                                style="width: 20px;"></i> Home</a></li>
                    <li style="margin-bottom: 1rem;"><a href="<?php echo $routeBase; ?>/pets/browse.php"
                            style="text-decoration: none; color: rgba(255,255,255,0.7);"><i class="fas fa-search"
                                style="width: 20px;"></i> Adopt a Pet</a></li>
                    <li style="margin-bottom: 1rem;"><a href="<?php echo $routeBase; ?>/#how-it-works"
                            style="text-decoration: none; color: rgba(255,255,255,0.7);"><i class="fas fa-info-circle"
                                style="width: 20px;"></i> How it Works</a></li>
                    <li style="margin-bottom: 1rem;"><a href="<?php echo $routeBase; ?>/#success-stories"
                            style="text-decoration: none; color: rgba(255,255,255,0.7);"><i class="fas fa-heart"
                                style="width: 20px;"></i> Success Stories</a></li>
                </ul>
            </div>
            <div>
                <h4 style="margin-bottom: 1.5rem;">Support Us</h4>
                <ul style="list-style: none;">
                    <li style="margin-bottom: 1rem;"><a href="<?php echo $routeBase; ?>/pages/donate.php"
                            style="text-decoration: none; color: rgba(255,255,255,0.7);"><i
                                class="fas fa-hand-holding-heart" style="width: 20px;"></i> Donate</a></li>
                    <li style="margin-bottom: 1rem;"><a href="<?php echo $routeBase; ?>/pages/volunteer.php"
                            style="text-decoration: none; color: rgba(255,255,255,0.7);"><i class="fas fa-hands-helping"
                                style="width: 20px;"></i> Volunteer</a></li>
                    <li style="margin-bottom: 1rem;"><a href="<?php echo $routeBase; ?>/pages/contact.php"
                            style="text-decoration: none; color: rgba(255,255,255,0.7);"><i class="fas fa-envelope"
                                style="width: 20px;"></i> Contact Us</a></li>
                </ul>
            </div>
            <div>
                <h4 style="margin-bottom: 1.5rem;">Connect</h4>
                <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
                    <a href="https://facebook.com" target="_blank" rel="noopener noreferrer"
                        style="width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; color: white; transition: background 0.3s;"
                        onmouseover="this.style.background='#1877F2'"
                        onmouseout="this.style.background='rgba(255,255,255,0.1)'"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://instagram.com" target="_blank" rel="noopener noreferrer"
                        style="width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; color: white; transition: background 0.3s;"
                        onmouseover="this.style.background='#E4405F'"
                        onmouseout="this.style.background='rgba(255,255,255,0.1)'"><i class="fab fa-instagram"></i></a>
                    <a href="https://twitter.com" target="_blank" rel="noopener noreferrer"
                        style="width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; color: white; transition: background 0.3s;"
                        onmouseover="this.style.background='#1DA1F2'"
                        onmouseout="this.style.background='rgba(255,255,255,0.1)'"><i class="fab fa-twitter"></i></a>
                </div>
                <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;">
                    <i class="fas fa-envelope"></i> support@kiyome.com<br>
                    <i class="fas fa-phone"></i> +1 (555) 123-4567
                </p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy;
                <?php echo date('Y'); ?> Kiyome || All rights reserved
            </p>
        </div>
    </div>
</footer>
</body>

</html>
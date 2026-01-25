<?php
$pageTitle = "Contact Us - Kiyome";
include __DIR__ . '/../../includes/header.php';
?>

<div style="max-width: 900px; margin: 2rem auto;">
    <div class="glass-container">
        <h1 class="gradient-text" style="margin-bottom: 1rem; text-align: center;"><i class="fas fa-envelope"></i>
            Contact Us</h1>
        <p style="color: var(--text-muted); margin-bottom: 3rem; text-align: center; font-size: 1.1rem;">
            Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.
        </p>

        <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 3rem;">
            <!-- Contact Info -->
            <div>
                <h3 style="margin-bottom: 1.5rem;"><i class="fas fa-info-circle"></i> Get in Touch</h3>

                <div style="margin-bottom: 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <div
                            style="width: 45px; height: 45px; background: rgba(76, 175, 80, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-map-marker-alt" style="color: var(--primary);"></i>
                        </div>
                        <div>
                            <strong>Address</strong>
                            <p style="color: var(--text-muted); font-size: 0.9rem; margin: 0;">123 Pet Lane, Animal
                                City, AC 12345</p>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <div
                            style="width: 45px; height: 45px; background: rgba(76, 175, 80, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-phone" style="color: var(--primary);"></i>
                        </div>
                        <div>
                            <strong>Phone</strong>
                            <p style="color: var(--text-muted); font-size: 0.9rem; margin: 0;">+1 (555) 123-4567</p>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <div
                            style="width: 45px; height: 45px; background: rgba(76, 175, 80, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-envelope" style="color: var(--primary);"></i>
                        </div>
                        <div>
                            <strong>Email</strong>
                            <p style="color: var(--text-muted); font-size: 0.9rem; margin: 0;">support@kiyome.com</p>
                        </div>
                    </div>
                </div>

                <h3 style="margin-bottom: 1rem;"><i class="fas fa-clock"></i> Hours</h3>
                <ul style="list-style: none; color: var(--text-muted); font-size: 0.95rem;">
                    <li style="padding: 0.3rem 0;"><strong>Mon-Fri:</strong> 9:00 AM - 6:00 PM</li>
                    <li style="padding: 0.3rem 0;"><strong>Saturday:</strong> 10:00 AM - 4:00 PM</li>
                    <li style="padding: 0.3rem 0;"><strong>Sunday:</strong> Closed</li>
                </ul>

                <div style="margin-top: 1.5rem;">
                    <h3 style="margin-bottom: 1rem;"><i class="fas fa-share-alt"></i> Follow Us</h3>
                    <div style="display: flex; gap: 0.75rem;">
                        <a href="https://facebook.com" target="_blank"
                            style="width: 40px; height: 40px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;"><i
                                class="fab fa-facebook-f"></i></a>
                        <a href="https://instagram.com" target="_blank"
                            style="width: 40px; height: 40px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;"><i
                                class="fab fa-instagram"></i></a>
                        <a href="https://twitter.com" target="_blank"
                            style="width: 40px; height: 40px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;"><i
                                class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div style="background: var(--bg-warm); padding: 2rem; border-radius: 16px;">
                <h3 style="margin-bottom: 1.5rem;"><i class="fas fa-paper-plane"></i> Send a Message</h3>
                <form action="#" method="POST">
                    <div class="form-group">
                        <label>Your Name</label>
                        <input type="text" name="name" required placeholder="John Doe">
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" required placeholder="john@example.com">
                    </div>
                    <div class="form-group">
                        <label>Subject</label>
                        <select name="subject">
                            <option value="general">General Inquiry</option>
                            <option value="adoption">Adoption Question</option>
                            <option value="shelter">Shelter Partnership</option>
                            <option value="volunteer">Volunteering</option>
                            <option value="donation">Donations</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Message</label>
                        <textarea name="message" rows="5" required placeholder="How can we help you?"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
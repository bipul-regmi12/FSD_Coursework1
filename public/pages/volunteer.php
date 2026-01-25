<?php
$pageTitle = "Volunteer - Kiyome";
include __DIR__ . '/../../includes/header.php';
?>

<div style="max-width: 900px; margin: 2rem auto;">
    <div class="glass-container">
        <h1 class="gradient-text" style="margin-bottom: 1rem; text-align: center;"><i class="fas fa-hands-helping"></i>
            Become a Volunteer</h1>
        <p style="color: var(--text-muted); margin-bottom: 3rem; text-align: center; font-size: 1.1rem;">
            Join our community of passionate animal lovers and make a real difference in the lives of pets waiting for
            their forever homes.
        </p>

        <div
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-bottom: 3rem;">
            <div style="text-align: center; padding: 1.5rem;">
                <div
                    style="width: 70px; height: 70px; background: rgba(76, 175, 80, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                    <i class="fas fa-dog" style="font-size: 1.8rem; color: var(--primary);"></i>
                </div>
                <h3 style="margin-bottom: 0.5rem;">Animal Care</h3>
                <p style="color: var(--text-muted); font-size: 0.95rem;">Help with feeding, grooming, and socializing
                    shelter animals.</p>
            </div>
            <div style="text-align: center; padding: 1.5rem;">
                <div
                    style="width: 70px; height: 70px; background: rgba(76, 175, 80, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                    <i class="fas fa-camera" style="font-size: 1.8rem; color: var(--primary);"></i>
                </div>
                <h3 style="margin-bottom: 0.5rem;">Photography</h3>
                <p style="color: var(--text-muted); font-size: 0.95rem;">Take beautiful photos to help pets get adopted
                    faster.</p>
            </div>
            <div style="text-align: center; padding: 1.5rem;">
                <div
                    style="width: 70px; height: 70px; background: rgba(76, 175, 80, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                    <i class="fas fa-home" style="font-size: 1.8rem; color: var(--primary);"></i>
                </div>
                <h3 style="margin-bottom: 0.5rem;">Foster Care</h3>
                <p style="color: var(--text-muted); font-size: 0.95rem;">Provide temporary homes for pets until they
                    find forever families.</p>
            </div>
            <div style="text-align: center; padding: 1.5rem;">
                <div
                    style="width: 70px; height: 70px; background: rgba(76, 175, 80, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                    <i class="fas fa-bullhorn" style="font-size: 1.8rem; color: var(--primary);"></i>
                </div>
                <h3 style="margin-bottom: 0.5rem;">Events & Outreach</h3>
                <p style="color: var(--text-muted); font-size: 0.95rem;">Help organize adoption events and spread
                    awareness.</p>
            </div>
        </div>

        <div style="background: var(--bg-warm); padding: 2rem; border-radius: 16px;">
            <h3 style="margin-bottom: 1.5rem; text-align: center;"><i class="fas fa-clipboard-list"></i> Sign Up to
                Volunteer</h3>
            <form action="#" method="POST">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" required placeholder="Your name">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" required placeholder="your@email.com">
                    </div>
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="tel" name="phone" placeholder="(555) 123-4567">
                </div>
                <div class="form-group">
                    <label>Areas of Interest</label>
                    <select name="interest">
                        <option value="animal_care">Animal Care</option>
                        <option value="photography">Photography</option>
                        <option value="foster">Foster Care</option>
                        <option value="events">Events & Outreach</option>
                        <option value="admin">Administrative Support</option>
                        <option value="any">Anywhere I'm Needed!</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tell us about yourself</label>
                    <textarea name="message" rows="4"
                        placeholder="Share your experience with animals and why you'd like to volunteer..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">Submit
                    Application</button>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
<?php
$pageTitle = "Donate - Kiyome";
include __DIR__ . '/../../includes/header.php';
?>

<div style="max-width: 800px; margin: 2rem auto;">
    <div class="glass-container" style="text-align: center;">
        <h1 class="gradient-text" style="margin-bottom: 1.5rem;"><i class="fas fa-hand-holding-heart"></i> Support Our
            Mission</h1>
        <p style="color: var(--text-muted); margin-bottom: 2rem; font-size: 1.1rem;">
            Your generous donations help us rescue, shelter, and find loving homes for pets in need. Every contribution
            makes a difference!
        </p>

        <div
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
            <div class="glass-container"
                style="padding: 2rem; border: 2px solid transparent; cursor: pointer; transition: all 0.3s;"
                onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='transparent'">
                <h3 style="color: var(--primary); font-size: 2rem;">$25</h3>
                <p style="color: var(--text-muted);">Feeds a pet for a week</p>
            </div>
            <div class="glass-container"
                style="padding: 2rem; border: 2px solid var(--primary); cursor: pointer; transition: all 0.3s;">
                <h3 style="color: var(--primary); font-size: 2rem;">$50</h3>
                <p style="color: var(--text-muted);">Covers vaccinations</p>
            </div>
            <div class="glass-container"
                style="padding: 2rem; border: 2px solid transparent; cursor: pointer; transition: all 0.3s;"
                onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='transparent'">
                <h3 style="color: var(--primary); font-size: 2rem;">$100</h3>
                <p style="color: var(--text-muted);">Sponsors a rescue</p>
            </div>
        </div>

        <div style="background: rgba(76, 175, 80, 0.1); padding: 2rem; border-radius: 16px; margin-bottom: 2rem;">
            <h3 style="margin-bottom: 1rem;"><i class="fas fa-gift"></i> Other Ways to Help</h3>
            <ul style="list-style: none; text-align: left; max-width: 400px; margin: 0 auto;">
                <li style="padding: 0.5rem 0; color: var(--text-muted);"><i class="fas fa-check"
                        style="color: var(--primary); margin-right: 10px;"></i> Donate pet food and supplies</li>
                <li style="padding: 0.5rem 0; color: var(--text-muted);"><i class="fas fa-check"
                        style="color: var(--primary); margin-right: 10px;"></i> Share our mission on social media</li>
                <li style="padding: 0.5rem 0; color: var(--text-muted);"><i class="fas fa-check"
                        style="color: var(--primary); margin-right: 10px;"></i> Become a foster parent</li>
                <li style="padding: 0.5rem 0; color: var(--text-muted);"><i class="fas fa-check"
                        style="color: var(--primary); margin-right: 10px;"></i> Volunteer your time</li>
            </ul>
        </div>

        <p style="color: var(--text-muted); font-size: 0.9rem;">
            <i class="fas fa-lock"></i> All donations are secure and tax-deductible.
        </p>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
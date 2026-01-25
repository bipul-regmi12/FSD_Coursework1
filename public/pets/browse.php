<?php
$pageTitle = "Adopt a Pet - Browse All Listings";
include __DIR__ . '/../../includes/header.php';
?>

<div class="browse-layout">
    <!-- Filter Sidebar -->
    <aside class="search-container">
        <h3 style="margin-bottom: 2rem; border-bottom: 2px solid var(--bg-warm); padding-bottom: 1rem;">
            <i class="fas fa-filter" style="color: var(--primary);"></i> Filters
        </h3>
        
        <form id="filterForm">
            <div class="form-group">
                <label>Animal Type</label>
                <select name="species">
                    <option value="">All Animals</option>
                    <option value="dog">Dogs</option>
                    <option value="cat">Cats</option>
                    <option value="rabbit">Rabbits</option>
                    <option value="bird">Birds</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Age Range</label>
                <select name="age">
                    <option value="">Any Age</option>
                    <option value="baby">Baby</option>
                    <option value="young">Young</option>
                    <option value="adult">Adult</option>
                    <option value="senior">Senior</option>
                </select>
            </div>

            <div class="form-group">
                <label>Size</label>
                <select name="size">
                    <option value="">Any Size</option>
                    <option value="small">Small</option>
                    <option value="medium">Medium</option>
                    <option value="large">Large</option>
                </select>
            </div>

            <div class="form-group">
                <label>Search Location</label>
                <input type="text" name="city" placeholder="Enter city name...">
            </div>

            <button type="reset" class="btn btn-outline" style="width: 100%; justify-content: center; margin-top: 1rem; border-radius: 10px;">
                Reset Filters
            </button>
        </form>
    </aside>

    <!-- Results Grid -->
    <section>
        <div style="margin-bottom: 2.5rem; display: flex; justify-content: space-between; align-items: center;">
            <h2 style="font-size: 2rem; margin: 0;">Find Your <span style="color: var(--primary);">Match</span></h2>
            <p style="color: var(--text-muted);" id="resultCount">Searching for furry friends...</p>
        </div>
        
        <div id="petGrid" class="pets-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 2.5rem;">
            <!-- Rendered by AJAX -->
        </div>
    </section>
</div>

<script src="<?php echo $assetBase; ?>/assets/js/browse.js" defer></script>
<?php include __DIR__ . '/../../includes/footer.php'; ?>

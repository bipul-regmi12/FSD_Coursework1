document.addEventListener('DOMContentLoaded', () => {
    const filterForm = document.getElementById('filterForm');
    const petGrid = document.getElementById('petGrid');
    const resultCount = document.getElementById('resultCount');

    if (!filterForm || !petGrid) return;

    const fetchPets = async () => {
        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData);

        try {
            petGrid.innerHTML = `<div style="grid-column: 1/-1; text-align: center; padding: 4rem;">
                <i class="fas fa-spinner fa-spin fa-3x" style="color: var(--primary);"></i>
                <p style="margin-top: 1rem; color: var(--text-muted);">Finding your matches...</p>
            </div>`;

            const response = await fetch(`${routeBase}/pets/filter.php?${params.toString()}`);
            const pets = await response.json();

            renderPets(pets);
            resultCount.textContent = `Found ${pets.length} wonderful pets`;
        } catch (error) {
            petGrid.innerHTML = `<p style="color: var(--error); text-align: center; grid-column: 1/-1;">Error loading pets. Please try again.</p>`;
        }
    };

    const renderPets = (pets) => {
        if (pets.length === 0) {
            petGrid.innerHTML = `<div style="text-align: center; grid-column: 1/-1; padding: 5rem; background: white; border-radius: 20px;">
                <i class="fas fa-paw fa-4x" style="color: #E8F5E9; margin-bottom: 1.5rem;"></i>
                <h3 style="color: var(--secondary);">No matches found today</h3>
                <p style="color: var(--text-muted);">Try adjusting your filters to see more animals.</p>
            </div>`;
            return;
        }

        petGrid.innerHTML = pets.map((pet, idx) => `
            <div class="pet-card fade-in" style="animation-delay: ${idx * 0.1}s">
                <img src="${pet.main_image}" alt="${pet.name}" class="pet-image">
                <div class="pet-info">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <h3 style="margin: 0; color: var(--secondary);">${pet.name}</h3>
                        <span class="pet-tag">${pet.species.charAt(0).toUpperCase() + pet.species.slice(1)}</span>
                    </div>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">
                        <i class="fas fa-map-marker-alt" style="color: var(--primary);"></i> ${pet.location_city}, ${pet.location_state}
                    </p>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                         <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 1rem;">
                            <strong>Breed:</strong> ${pet.breed || 'Mixed'} <br>
                            <strong>Age:</strong> ${pet.age_range.charAt(0).toUpperCase() + pet.age_range.slice(1)}
                         </p>
                         <a href="${routeBase}/pets/view.php?id=${pet.id}" class="btn btn-secondary" style="width: 100%; justify-content: center; border-radius: 12px;">View Details</a>
                    </div>
                </div>
            </div>
        `).join('');
    };

    filterForm.querySelectorAll('input, select').forEach(el => {
        el.addEventListener('input', fetchPets);
    });

    filterForm.addEventListener('reset', () => {
        setTimeout(fetchPets, 10);
    });

    fetchPets();
});

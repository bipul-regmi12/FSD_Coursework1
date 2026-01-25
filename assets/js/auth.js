function updateRoleSelection(radio) {
    document.querySelectorAll('.role-option').forEach(opt => {
        opt.style.borderColor = '#EEE';
        opt.style.background = 'transparent';
        opt.style.boxShadow = 'none';
    });
    
    const parent = radio.closest('.role-option');
    if (radio.value === 'adopter') {
        parent.style.borderColor = '#FF8A65';
        parent.style.background = '#FFF5F2';
        parent.style.boxShadow = '0 10px 20px rgba(255, 138, 101, 0.1)';
    } else {
        parent.style.borderColor = '#4CAF50';
        parent.style.background = '#F1F8F1';
        parent.style.boxShadow = '0 10px 20px rgba(76, 175, 80, 0.1)';
    }
}

function toggleShelterFields() {
    const roleSelect = document.getElementById('roleSelect');
    if (!roleSelect) return;
    const role = roleSelect.value;
    const fields = document.getElementById('shelterFields');
    if (fields) {
        fields.style.display = role === 'shelter' ? 'block' : 'none';
    }
}

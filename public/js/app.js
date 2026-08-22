document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.btn-terminer').forEach((button) => {
        button.addEventListener('click', () => handleTerminer(button));
    });
});

async function handleTerminer(button) {
    const chantierId = button.dataset.id;

    button.disabled = true;
    button.textContent = 'Mise à jour...';

    try {
        const response = await fetch(`/chantier/${chantierId}/terminer`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        const data = await response.json();

        if (!response.ok || !data.success) {
            showAlert(data.message || 'Une erreur est survenue.', 'danger');
            button.disabled = false;
            button.textContent = 'Marquer comme terminé';
            return;
        }

        updateChantierUI(chantierId, data);
        showAlert(data.message, 'success');
    } catch (error) {
        showAlert('Impossible de contacter le serveur. Vérifiez votre connexion.', 'danger');
        button.disabled = false;
        button.textContent = 'Marquer comme terminé';
    }
}

function updateChantierUI(chantierId, data) {
    const badge = document.querySelector(`[data-statut-badge="${chantierId}"]`);
    if (badge) {
        badge.textContent = data.statut;
        badge.classList.remove('bg-warning', 'text-dark', 'bg-primary');
        badge.classList.add('bg-success');
    }

    const button = document.querySelector(`.btn-terminer[data-id="${chantierId}"]`);
    if (button) {
        button.remove();
    }
}

function showAlert(message, type) {
    const zone = document.getElementById('alert-zone');
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show`;
    alert.role = 'alert';
    alert.textContent = message;

    const closeBtn = document.createElement('button');
    closeBtn.type = 'button';
    closeBtn.className = 'btn-close';
    closeBtn.setAttribute('data-bs-dismiss', 'alert');
    alert.appendChild(closeBtn);

    zone.appendChild(alert);

    setTimeout(() => alert.remove(), 5000);
}

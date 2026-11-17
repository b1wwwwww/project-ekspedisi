// Filter status paket
const filterStatus = document.getElementById('filter-status');

if (filterStatus) {
    filterStatus.addEventListener('change', function () {
        this.form.submit();
    });
}


// Modal Konfirmasi Transit
let formTransitAktif = null;

window.bukaModalTransit = function (formId) {
    formTransitAktif = document.getElementById(formId);

    const modal = document.getElementById('modal-transit');

    modal.classList.remove('hidden');
    modal.classList.add('flex');
};

window.tutupModalTransit = function () {
    const modal = document.getElementById('modal-transit');

    modal.classList.add('hidden');
    modal.classList.remove('flex');

    formTransitAktif = null;
};

window.konfirmasiTransit = function () {
    if (formTransitAktif) {
        formTransitAktif.submit();
    }
};

// Menghilangkan notifikasi secara otomatis.
const alerts = [
    document.getElementById('alert-success'),
    document.getElementById('alert-error')
];

alerts.forEach((alert) => {
    if (alert) {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';

            setTimeout(() => {
                alert.remove();
            }, 500);

        }, 3500);
    }
});
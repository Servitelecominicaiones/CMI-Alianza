

/* ================= INACTIVAR DOCUMENTO ================= */

document.addEventListener('click', function(e) {

    if (e.target.classList.contains('inactivar')) {

        let id = e.target.dataset.id;

        if (!confirm('¿Desea inactivar este documento?')) return;

        fetch(`/documentos/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(r => r.json())
        .then(resp => {
            if (resp.ok) {
                e.target.closest('.col-md-3').remove();
            }
        });
    }

});


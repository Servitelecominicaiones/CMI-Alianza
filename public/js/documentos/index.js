let page = 1;
let loading = false;


window.addEventListener('scroll', () => {

    if (loading || noMoreData) return;

    if ((window.innerHeight + window.scrollY) >= (document.body.offsetHeight - 200)) {

        loading = true;
        page++;

        fetch(`${window.location.pathname}?page=${page}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {

            // Si no viene nada, ya no hay más páginas
            if (html.trim() === '') {
                noMoreData = true;
                return;
            }

            document
                .getElementById('contenedor-documentos')
                .insertAdjacentHTML('beforeend', html);

            loading = false;
        })
        .catch(() => {
            loading = false;
        });
    }
});

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


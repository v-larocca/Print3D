// ================================================
// PRINT3D — Script condiviso
// ================================================

document.addEventListener('DOMContentLoaded', function () {

    // --- Burger menu Bulma (mobile) ---
    const burger = document.querySelector('.navbar-burger');
    if (burger) {
        burger.addEventListener('click', function () {
            const target = document.getElementById(burger.dataset.target);
            burger.classList.toggle('is-active');
            target.classList.toggle('is-active');
        });
    }

    // --- Aggiorna il nome del file mostrato nell'input Bulma "file" ---
    document.querySelectorAll('.file-input').forEach(function (input) {
        input.addEventListener('change', function () {
            const fileNameSpan = input.closest('.file').querySelector('.file-name');
            if (fileNameSpan) {
                fileNameSpan.textContent = input.files.length > 0
                    ? input.files[0].name
                    : 'Nessun file selezionato';
            }
        });
    });

    // --- Toggle Progetti/Utenti nella ricerca ---
    const tipoToggle = document.getElementById('tipoToggle');
    if (tipoToggle) {
        const tipoInput      = document.getElementById('tipoInput');
        const orderLikesOpt  = document.getElementById('orderLikesOption');
        const orderDateOpt   = document.getElementById('orderDateOption');
        const orderSelect    = document.getElementById('orderSelect');

        // Per la ricerca utenti esiste solo l'ordinamento alfabetico —
        // nasconde "Più recenti" e "Più like", lasciando solo "Nome A-Z"
        function aggiornaOpzioneLikes(tipo) {
            const soloAlfabetico = (tipo === 'utenti');

            if (orderLikesOpt) orderLikesOpt.hidden = soloAlfabetico;
            if (orderDateOpt)  orderDateOpt.hidden  = soloAlfabetico;

            if (soloAlfabetico) {
                orderSelect.value = 'name';
            }
        }

        // Stato iniziale al caricamento della pagina
        aggiornaOpzioneLikes(tipoInput.value);

        tipoToggle.querySelectorAll('button').forEach(function (btn) {
            btn.addEventListener('click', function () {
                tipoToggle.querySelectorAll('button').forEach(function (b) {
                    b.classList.remove('is-link', 'is-selected');
                });
                btn.classList.add('is-link', 'is-selected');
                tipoInput.value = btn.dataset.value;
                aggiornaOpzioneLikes(btn.dataset.value);
            });
        });
    }

    // --- Modal generici (Bulma) ---
    document.querySelectorAll('.js-modal-trigger').forEach(function (trigger) {
        trigger.addEventListener('click', function () {
            const modal = document.getElementById(trigger.dataset.target);
            if (modal) modal.classList.add('is-active');
        });
    });

    document.querySelectorAll('.modal-background, .modal-close-btn').forEach(function (closer) {
        closer.addEventListener('click', function () {
            closer.closest('.modal').classList.remove('is-active');
        });
    });

    // Chiude il modal attivo premendo Esc
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal.is-active').forEach(function (modal) {
                modal.classList.remove('is-active');
            });
        }
    });

    // --- Like dinamico con animazione, senza ricaricare la pagina ---
    document.querySelectorAll('.like-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();

            const url      = btn.getAttribute('href');
            const isLiked  = btn.classList.contains('is-liked');
            const idProj   = btn.dataset.id;
            const countEl  = document.getElementById('like-count-' + idProj);

            fetch(url).then(function (response) {
                if (!response.ok) return;

                // Aggiorna stato visivo
                btn.classList.toggle('is-liked');
                btn.classList.add('animate');
                setTimeout(function () { btn.classList.remove('animate'); }, 400);

                // Aggiorna il contatore e l'href per il prossimo click
                if (countEl) {
                    let count = parseInt(countEl.textContent, 10) || 0;
                    count = isLiked ? count - 1 : count + 1;
                    countEl.textContent = count;
                }

                btn.setAttribute('href', isLiked
                    ? url.replace('rimuoviLike', 'aggiungiLike')
                    : url.replace('aggiungiLike', 'rimuoviLike')
                );
            });
        });
    });
});

<?php

class CHome {

    // Mostra la homepage con tutti i progetti
    public static function homePage(): void {
        $progetti = CProject::getAllProgetti('date');

        // Trasforma ogni EProject in un array associativo
        // con i dati pronti per il template (autore, like inclusi)
        $datiProgetti = [];
        foreach ($progetti as $p) {
            $autore = CUser::getProfilo($p->getIdAutore());

            $datiProgetti[] = [
                'id'          => $p->getId(),
                'titolo'      => $p->getTitolo(),
                'descrizione' => $p->getDescrizioneBreve(100),
                'categoria'   => $p->getCategoria(),
                'username'    => $autore ? $autore->getUsername() : 'Utente eliminato',
                'likes'       => CLike::contaLike($p->getId())
            ];
        }

        $view = new VHome();
        $view->homePage(
            $datiProgetti,
            CUser::isLoggato(),
            CUser::isLoggato() ? USession::getSessionElement('id') : null,
            CUser::isAdmin()
        );
    }
}

<?php

class CLike {

    // ================================================
    // LIKE
    // ================================================

    // Aggiunge un like al progetto — logica pura, usata anche dai test
    // L'id utente viene letto dalla sessione
    public static function creaLike(int $idProject): bool {
        if (!CUser::isLoggato()) return false;

        $idUser = USession::getSessionElement('id');

        // Controlla che il like non esista già
        if (FLike::exists($idUser, $idProject)) return false;

        $like = new ELike($idUser, $idProject);
        return FLike::createObject($like);
    }

    // Rimuove un like dal progetto — logica pura, usata anche dai test
    public static function eliminaLike(int $idProject): bool {
        if (!CUser::isLoggato()) return false;

        $idUser = USession::getSessionElement('id');

        // Controlla che il like esista prima di eliminarlo
        if (!FLike::exists($idUser, $idProject)) return false;

        $like = new ELike($idUser, $idProject);
        return FLike::deleteObject($like->getId());
    }

    // Azione web — aggiunge il like e torna alla pagina del progetto
    public static function aggiungiLike(int $idProject): void {
        self::creaLike($idProject);
        header('Location: /print3d/Project/dettaglio/' . $idProject);
        exit;
    }

    // Azione web — rimuove il like e torna alla pagina del progetto
    public static function rimuoviLike(int $idProject): void {
        self::eliminaLike($idProject);
        header('Location: /print3d/Project/dettaglio/' . $idProject);
        exit;
    }

    // ================================================
    // VERIFICA E CONTEGGIO
    // ================================================

    // Controlla se l'utente in sessione ha già messo like
    // Restituisce false anche se l'utente non è loggato
    /** @internal */
    public static function hasLike(int $idProject): bool {
        if (!CUser::isLoggato()) return false;

        $idUser = USession::getSessionElement('id');
        return FLike::exists($idUser, $idProject);
    }

    // Conta i like di un progetto — accessibile a tutti
    /** @internal */
    public static function contaLike(int $idProject): int {
        return FLike::countByProject($idProject);
    }

    // ================================================
    // RECUPERO
    // ================================================

    // Progetti piaciuti da un utente — per il tab profilo
    /** @internal */
    public static function getLikedByUser(int $idUser): array {
        return FProject::retrieveLikedByUser($idUser);
    }
}

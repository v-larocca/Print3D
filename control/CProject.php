<?php

class CProject {

    // ================================================
    // CREAZIONE E GESTIONE
    // ================================================

    // Crea un nuovo progetto e carica i file associati — logica pura, usata anche dai test
    // Usa una transazione per garantire atomicità — o tutto o niente
    public static function salvaProgetto(
        string $titolo,
        string $descrizione,
        string $categoria,
        array  $immagine,   // $_FILES['immagine']
        array  $zip         // $_FILES['zip']
    ): bool {

        // Controlla che l'utente sia loggato
        if (!CUser::isLoggato()) return false;

        // Controlla campi vuoti
        if (empty($titolo) || empty($descrizione) || empty($categoria))
            return false;

        $pdo = FPersistentManager::getInstance()->getPdo();

        try {
            $pdo->beginTransaction();

            $idAutore = USession::getSessionElement('id');

            $project = new EProject(0, $titolo, $descrizione, $categoria, '', $idAutore);

            // Tutte e tre le operazioni devono riuscire
            $success = FPersistentManager::createObj($project)
                    && CFile::uploadImmagine($immagine, $project->getId())
                    && CFile::uploadZip($zip, $project->getId());

            if (!$success) {
                $pdo->rollBack();
                return false;
            }

            $pdo->commit();
            return true;

        } catch (Exception $e) {
            $pdo->rollBack();
            error_log("Errore salvaProgetto(): " . $e->getMessage());
            return false;
        }
    }

    // Azione web — chiamata dal FrontController, interagisce con VProject
    public static function creaProgetto(
        string $titolo,
        string $descrizione,
        string $categoria,
        array  $immagine,
        array  $zip
    ): void {
        $view = new VProject();

        if (!CUser::isLoggato()) {
            header('Location: /print3d/User/showLoginForm');
            exit;
        }

        $idProject = null;
        if (self::salvaProgetto($titolo, $descrizione, $categoria, $immagine, $zip)) {
            // Recupera l'ultimo progetto creato dall'utente per il redirect
            $idAutore  = USession::getSessionElement('id');
            $progetti  = self::getProgettiByAutore($idAutore);
            $idProject = !empty($progetti) ? $progetti[0]->getId() : null;

            header('Location: /print3d/Project/dettaglio/' . $idProject);
            exit;
        } else {
            $view->formCreazione('Errore durante il caricamento — controlla i campi e i file inseriti');
        }
    }

    // Mostra il form di creazione progetto — chiamato dal FrontController
    public static function formCreazione(): void {
        if (!CUser::isLoggato()) {
            header('Location: /print3d/User/showLoginForm');
            exit;
        }
        $view = new VProject();
        $view->formCreazione();
    }

    // Recupera un singolo progetto per id
    public static function getProgetto(int $id): ?EProject {
        return FPersistentManager::retrieveObj("EProject", $id);
    }

    // Mostra il dettaglio di un progetto — chiamato dal FrontController
    public static function dettaglio(int $id): void {
        $project = self::getProgetto($id);
        if (!$project) {
            $view = new VError();
            $view->show404();
            return;
        }

        $autore   = CUser::getProfilo($project->getIdAutore());
        $loggato  = CUser::isLoggato();
        $idUtente = $loggato ? USession::getSessionElement('id') : null;

        // Può eliminare se è l'autore OPPURE se è admin
        $puoEliminare = self::isAutore($id) || CUser::isAdmin();

        $view = new VProject();
        $view->dettaglio(
            $project->getId(),
            $project->getTitolo(),
            $project->getDescrizione(),
            $project->getCategoria(),
            $project->getDataUpload(),
            $project->getIdAutore(),
            $autore ? $autore->getUsername() : 'Utente eliminato',
            CLike::contaLike($id),
            CLike::hasLike($id),
            $loggato,
            $idUtente,
            $puoEliminare
        );
    }

    // Elimina un progetto e i suoi file — logica pura, usata anche dai test
    // Solo l'autore o l'admin possono farlo
    public static function rimuoviProgetto(int $id): bool {
        if (!CUser::isLoggato()) return false;

        $project = self::getProgetto($id);
        if (!$project) return false;

        // Controlla i permessi — solo autore o admin
        if (!self::isAutore($id) && !CUser::isAdmin())
            return false;

        // I file vengono eliminati automaticamente dal DB grazie a ON DELETE CASCADE
        return FPersistentManager::deleteObj($project);
    }

    // Azione web — chiamata dal FrontController, poi reindirizza alla home
    public static function eliminaProgetto(int $id): void {
        self::rimuoviProgetto($id);
        header('Location: /print3d/');
        exit;
    }

    // ================================================
    // VISUALIZZAZIONE
    // ================================================

    // Tutti i progetti per la home con ordinamento
    // $order: 'date' (default) | 'likes' | 'name'
    public static function getAllProgetti(string $order = 'date'): array {
        return FProject::retrieveAll($order);
    }

    // Progetti caricati da un utente — tab profilo "caricati"
    public static function getProgettiByAutore(int $idAutore): array {
        return FProject::retrieveByAutore($idAutore);
    }

    // ================================================
    // CONTROLLO PERMESSI
    // ================================================

    // Controlla se l'utente in sessione è l'autore del progetto
    public static function isAutore(int $idProgetto): bool {
        if (!CUser::isLoggato()) return false;

        $project  = self::getProgetto($idProgetto);
        if (!$project) return false;

        $idUtente = USession::getSessionElement('id');
        return $project->getIdAutore() === $idUtente;
    }
}

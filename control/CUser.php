<?php

class CUser {

    // Mostra il form di login — chiamato dal FrontController
    public static function showLoginForm(): void {
        $view = new VUser();
        $view->showLoginForm();
    }

    // Mostra il form di registrazione — chiamato dal FrontController
    public static function showRegistrationForm(): void {
        $view = new VUser();
        $view->showRegistrationForm();
    }

    // ================================================
    // AUTENTICAZIONE
    // ================================================

    // Crea l'utente nel DB — logica pura, usata anche dai test
    public static function creaUtente(string $username, string $email, string $password): bool {
        // Controlla campi vuoti
        if (empty($username) || empty($email) || empty($password))
            return false;

        // Controlla se email o username sono già in uso
        if (self::verificaEmail($email))
            return false;

        if (self::verificaUsername($username))
            return false;

        $user = new EUser(
            0,
            $username,
            $email,
            password_hash($password, PASSWORD_DEFAULT)
        );

        return FPersistentManager::createObj($user);
    }

    // Azione web — chiamata dal FrontController, interagisce con VUser
    public static function registra(string $username, string $email, string $password): void {
        $view = new VUser();

        if (self::creaUtente($username, $email, $password)) {
            header('Location: /print3d/User/showLoginForm');
            exit;
        } else {
            $view->showRegistrationForm('Username o email già in uso, oppure campi non validi');
        }
    }

    // Verifica le credenziali e avvia la sessione — logica pura, usata anche dai test
    public static function verificaCredenziali(string $emailOrUsername, string $password): bool {
        // Controlla campi vuoti
        if (empty($emailOrUsername) || empty($password))
            return false;

        // Prova prima per email, poi per username
        $user = FUser::retrieveByEmail($emailOrUsername);
        if (!$user)
            $user = FUser::retrieveByUsername($emailOrUsername);

        // Utente non trovato né per email né per username
        if (!$user)
            return false;

        // Password errata
        if (!password_verify($password, $user->getPassword()))
            return false;

        // Avvia sessione solo se non è già attiva
        if (USession::getSessionStatus() == PHP_SESSION_NONE)
            USession::getInstance();

        USession::setSessionElement('id',       $user->getId());
        USession::setSessionElement('username', $user->getUsername());
        USession::setSessionElement('ruolo',    $user->getRuolo());

        return true;
    }

    // Azione web — chiamata dal FrontController, interagisce con VUser
    public static function login(string $emailOrUsername, string $password): void {
        $view = new VUser();

        if (self::verificaCredenziali($emailOrUsername, $password)) {
            header('Location: /print3d/');
            exit;
        } else {
            $view->showLoginForm('Credenziali non valide');
        }
    }

    // Azione web — chiamata dal FrontController, poi reindirizza alla home
    public static function logout(): void {
        USession::getInstance();
        USession::unsetSession();
        USession::destroySession();
        UCookie::destroyCookie('PHPSESSID');
        header('Location: /print3d/');
        exit;
    }

    // Mostra il profilo di un utente — chiamato dal FrontController
    // $tab: 'uploaded' (default) | 'liked'
    public static function profile(int $idUser, string $tab = 'uploaded'): void {
        $user = self::getProfilo($idUser);
        if (!$user) {
            $view = new VError();
            $view->show404();
            return;
        }

        $loggato          = self::isLoggato();
        $idCorrente       = $loggato ? USession::getSessionElement('id') : null;
        $isProfiloProprio = $loggato && $idCorrente === $idUser;

        // Il tab "piaciuti" è visibile solo sul proprio profilo
        // Se qualcuno prova ad accedervi via URL su un profilo altrui, forza "uploaded"
        if ($tab === 'liked' && !$isProfiloProprio)
            $tab = 'uploaded';

        // Sceglie quale lista di progetti mostrare in base al tab
        $progetti = $tab === 'liked'
            ? CLike::getLikedByUser($idUser)
            : CProject::getProgettiByAutore($idUser);

        // Trasforma ogni EProject in un array pronto per il template
        $datiProgetti = [];
        foreach ($progetti as $p) {
            $datiProgetti[] = [
                'id'        => $p->getId(),
                'titolo'    => $p->getTitolo(),
                'categoria' => $p->getCategoria(),
                'likes'     => CLike::contaLike($p->getId())
            ];
        }

        // Costruisce le liste di follower e seguiti con username per i modal
        $followerList = [];
        foreach (CFollow::getFollower($idUser) as $f) {
            $u = self::getProfilo($f->getIdFollower());
            if ($u) $followerList[] = ['id' => $u->getId(), 'username' => $u->getUsername()];
        }

        $followingList = [];
        foreach (CFollow::getFollowed($idUser) as $f) {
            $u = self::getProfilo($f->getIdFollowed());
            if ($u) $followingList[] = ['id' => $u->getId(), 'username' => $u->getUsername()];
        }

        $view = new VUser();
        $view->profile(
            $user->getUsername(),
            $user->isAdmin(),
            $idUser,
            $loggato,
            $isProfiloProprio,
            $loggato ? CFollow::staSeguendo($idUser) : false,
            count($followerList),
            count($followingList),
            $datiProgetti,
            $followerList,
            $followingList
        );
    }

    // ================================================
    // VERIFICA
    // ================================================

    // Controlla se una email è già registrata
    public static function verificaEmail(string $email): bool {
        return FUser::retrieveByEmail($email) !== null;
    }

    // Controlla se uno username è già registrato
    public static function verificaUsername(string $username): bool {
        return FUser::retrieveByUsername($username) !== null;
    }

    // Controlla se l'utente in sessione è loggato
    public static function isLoggato(): bool {
        if (UCookie::isSet('PHPSESSID')) {
            if (USession::getSessionStatus() == PHP_SESSION_NONE)
                USession::getInstance();
        }
        return USession::isSetSessionElement('id');
    }

    // Controlla se l'utente in sessione è admin
    public static function isAdmin(): bool {
        // Assicura che la sessione sia avviata prima di leggerla
        if (UCookie::isSet('PHPSESSID')) {
            if (USession::getSessionStatus() == PHP_SESSION_NONE)
                USession::getInstance();
        }
        return USession::getSessionElement('ruolo') === 'admin';
    }

    // ================================================
    // PROFILO
    // ================================================

    public static function getProfilo(int $id): ?EUser {
        return FPersistentManager::retrieveObj("EUser", $id);
    }

    public static function getProfiloCorrente(): ?EUser {
        if (!self::isLoggato()) return null;
        return self::getProfilo(USession::getSessionElement('id'));
    }

    public static function aggiornaUsername(int $id, string $username): bool {
        if (empty($username)) return false;
        // Controlla che il nuovo username non sia già in uso
        if (self::verificaUsername($username)) return false;
        $user = self::getProfilo($id);
        if (!$user) return false;
        return FPersistentManager::updateObj($user, 'username', $username);
    }

    public static function aggiornaPassword(int $id, string $vecchiaPassword, string $nuovaPassword): bool {
        if (empty($vecchiaPassword) || empty($nuovaPassword)) return false;
        $user = self::getProfilo($id);
        if (!$user) return false;
        if (!password_verify($vecchiaPassword, $user->getPassword())) return false;
        return FPersistentManager::updateObj($user, 'password', password_hash($nuovaPassword, PASSWORD_DEFAULT));
    }

    public static function eliminaAccount(int $id): bool {
        $user = self::getProfilo($id);
        if (!$user) return false;
        $result = FPersistentManager::deleteObj($user);
        if ($result) self::logout();
        return $result;
    }

    // ================================================
    // FUNZIONI ADMIN
    // ================================================

    public static function getAllUtenti(): array {
        if (!self::isAdmin()) return [];
        return FUser::retrieveAll();
    }

    public static function eliminaUtente(int $id): bool {
        if (!self::isAdmin()) return false;
        $user = self::getProfilo($id);
        if (!$user) return false;
        return FPersistentManager::deleteObj($user);
    }

    public static function cambiaRuolo(int $id, string $ruolo): bool {
        if (!self::isAdmin()) return false;
        $user = self::getProfilo($id);
        if (!$user) return false;
        return FPersistentManager::updateObj($user, 'ruolo', $ruolo);
    }

    // Mostra il pannello admin — chiamato dal FrontController
    public static function dashboard(): void {
        if (!self::isAdmin()) {
            header('Location: /print3d/');
            exit;
        }

        $utenti = self::getAllUtenti();
        $datiUtenti = [];
        foreach ($utenti as $u) {
            $datiUtenti[] = [
                'id'       => $u->getId(),
                'username' => $u->getUsername(),
                'email'    => $u->getEmail(),
                'ruolo'    => $u->getRuolo()
            ];
        }

        $progetti = CProject::getAllProgetti('date');
        $datiProgetti = [];
        foreach ($progetti as $p) {
            $autore = self::getProfilo($p->getIdAutore());
            $datiProgetti[] = [
                'id'        => $p->getId(),
                'titolo'    => $p->getTitolo(),
                'categoria' => $p->getCategoria(),
                'username'  => $autore ? $autore->getUsername() : 'Utente eliminato'
            ];
        }

        $view = new VAdmin();
        $view->dashboard($datiUtenti, $datiProgetti);
    }

    // Azione web — cambia il ruolo e torna al pannello admin
    public static function cambiaRuoloAzione(int $id, string $ruolo): void {
        self::cambiaRuolo($id, $ruolo);
        header('Location: /print3d/User/dashboard');
        exit;
    }

    // Azione web — elimina l'utente e torna al pannello admin
    public static function eliminaUtenteAzione(int $id): void {
        self::eliminaUtente($id);
        header('Location: /print3d/User/dashboard');
        exit;
    }
}

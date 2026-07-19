<?php
require_once 'StartSmarty.php';

class VUser {

    private $smarty;

    public function __construct() {
        $this->smarty = StartSmarty::configuration();
    }

    // Assegna i dati necessari alla navbar condivisa su ogni pagina
    private function assignNavData() {
        $loggato = CUser::isLoggato();
        $this->smarty->assign('navLoggato', $loggato);
        $this->smarty->assign('navIdUtente', $loggato ? USession::getSessionElement('id') : null);
        $this->smarty->assign('navIsAdmin', CUser::isAdmin());
    }

    // Mostra il form di login, con eventuale messaggio di errore
    public function showLoginForm($errore = null) {
        $this->assignNavData();
        $this->smarty->assign('errore', $errore);
        $this->smarty->display('Smarty/templates/login.tpl');
    }

    // Mostra il form di registrazione, con eventuale messaggio di errore
    public function showRegistrationForm($errore = null) {
        $this->assignNavData();
        $this->smarty->assign('errore', $errore);
        $this->smarty->display('Smarty/templates/registration.tpl');
    }

    // Mostra un errore generico
    public function showError($string) {
        $this->smarty->assign('messaggio', $string);
        $this->smarty->display('Smarty/templates/error.tpl');
    }

    // Mostra il profilo di un utente, con tab progetti caricati/piaciuti
    public function profile(
        $username, $isAdmin, $idUser, $loggato,
        $isProfiloProprio, $staSeguendo,
        $numFollower, $numSeguiti, $progetti,
        $followerList, $followingList
    ) {
        $this->assignNavData();
        $this->smarty->assign('username', $username);
        $this->smarty->assign('isAdmin', $isAdmin);
        $this->smarty->assign('idUser', $idUser);
        $this->smarty->assign('loggato', $loggato);
        $this->smarty->assign('isProfiloProprio', $isProfiloProprio);
        $this->smarty->assign('staSeguendo', $staSeguendo);
        $this->smarty->assign('numFollower', $numFollower);
        $this->smarty->assign('numSeguiti', $numSeguiti);
        $this->smarty->assign('progetti', $progetti);
        $this->smarty->assign('followerList', $followerList);
        $this->smarty->assign('followingList', $followingList);
        $this->smarty->display('Smarty/templates/profile.tpl');
    }
}

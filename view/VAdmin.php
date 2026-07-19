<?php
require_once 'StartSmarty.php';

class VAdmin {

    private $smarty;

    public function __construct() {
        $this->smarty = StartSmarty::configuration();
    }

    // Mostra il pannello admin con utenti e progetti
    public function dashboard($utenti, $progetti) {
        // Chi vede questa pagina è sempre admin loggato (controllato dal Control)
        $this->smarty->assign('navLoggato', true);
        $this->smarty->assign('navIdUtente', USession::getSessionElement('id'));
        $this->smarty->assign('navIsAdmin', true);
        $this->smarty->assign('utenti', $utenti);
        $this->smarty->assign('progetti', $progetti);
        $this->smarty->display('Smarty/templates/admin.tpl');
    }
}

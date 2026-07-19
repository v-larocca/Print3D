<?php
require_once 'StartSmarty.php';

class VHome {

    private $smarty;

    public function __construct() {
        $this->smarty = StartSmarty::configuration();
    }

    // Mostra la homepage con la lista dei progetti
    public function homePage($progetti, $loggato, $idUtente, $isAdmin) {
        $this->smarty->assign('navLoggato', $loggato);
        $this->smarty->assign('navIdUtente', $idUtente);
        $this->smarty->assign('navIsAdmin', $isAdmin);
        $this->smarty->assign('progetti', $progetti);
        $this->smarty->display('Smarty/templates/home.tpl');
    }
}

<?php
require_once 'StartSmarty.php';

class VError {

    private $smarty;

    public function __construct() {
        $this->smarty = StartSmarty::configuration();
    }

    // Mostra la pagina 404 — pagina non trovata
    public function show404() {
        http_response_code(404);
        $this->smarty->display('Smarty/templates/error404.tpl');
    }

    // Mostra un errore generico con messaggio personalizzato
    public function showError($messaggio) {
        $this->smarty->assign('messaggio', $messaggio);
        $this->smarty->display('Smarty/templates/error.tpl');
    }
}

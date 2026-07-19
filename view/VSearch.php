<?php
require_once 'StartSmarty.php';

class VSearch {

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

    // Mostra il form vuoto (prima ricerca)
    public function form() {
        $this->assignNavData();
        $this->smarty->assign('tipo', null);
        $this->smarty->assign('query', '');
        $this->smarty->assign('order', 'date');
        $this->smarty->assign('risultatiProgetti', []);
        $this->smarty->assign('risultatiUtenti', []);
        $this->smarty->display('Smarty/templates/search.tpl');
    }

    // Mostra i risultati della ricerca
    public function risultati($tipo, $query, $order, $risultatiProgetti, $risultatiUtenti) {
        $this->assignNavData();
        $this->smarty->assign('tipo', $tipo);
        $this->smarty->assign('query', $query);
        $this->smarty->assign('order', $order);
        $this->smarty->assign('risultatiProgetti', $risultatiProgetti);
        $this->smarty->assign('risultatiUtenti', $risultatiUtenti);
        $this->smarty->display('Smarty/templates/search.tpl');
    }
}

<?php
require_once 'StartSmarty.php';

class VProject {

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

    // Mostra il dettaglio di un progetto
    public function dettaglio(
        $id, $titolo, $descrizione, $categoria, $dataUpload,
        $idAutore, $username, $likes, $hasLike,
        $loggato, $idUtente, $puoEliminare
    ) {
        $this->assignNavData();
        $this->smarty->assign('id', $id);
        $this->smarty->assign('titolo', $titolo);
        $this->smarty->assign('descrizione', $descrizione);
        $this->smarty->assign('categoria', $categoria);
        $this->smarty->assign('dataUpload', $dataUpload);
        $this->smarty->assign('idAutore', $idAutore);
        $this->smarty->assign('username', $username);
        $this->smarty->assign('likes', $likes);
        $this->smarty->assign('hasLike', $hasLike);
        $this->smarty->assign('loggato', $loggato);
        $this->smarty->assign('idUtente', $idUtente);
        $this->smarty->assign('puoEliminare', $puoEliminare);
        $this->smarty->display('Smarty/templates/project_detail.tpl');
    }

    // Mostra il form di creazione progetto, con eventuale messaggio di errore
    public function formCreazione($errore = null) {
        $this->assignNavData();
        $this->smarty->assign('errore', $errore);
        $this->smarty->display('Smarty/templates/project_form.tpl');
    }
}

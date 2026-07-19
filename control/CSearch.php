<?php

class CSearch {

    // ================================================
    // FORM E AZIONE WEB — chiamate dal FrontController
    // ================================================

    // Mostra il form di ricerca vuoto
    public static function formRicerca(): void {
        $view = new VSearch();
        $view->form();
    }

    // Esegue la ricerca e mostra i risultati
    public static function cerca(string $tipo, string $query, string $order = 'date'): void {
        $risultatiProgetti = [];
        $risultatiUtenti    = [];

        if ($tipo === 'utenti') {
            $utenti = self::cercaUtenti($query);
            foreach ($utenti as $u) {
                $risultatiUtenti[] = [
                    'id'       => $u->getId(),
                    'username' => $u->getUsername(),
                    'isAdmin'  => $u->isAdmin()
                ];
            }
        } else {
            $progetti = self::cercaProgetti($query, $order);
            foreach ($progetti as $p) {
                $risultatiProgetti[] = [
                    'id'        => $p->getId(),
                    'titolo'    => $p->getTitolo(),
                    'categoria' => $p->getCategoria(),
                    'likes'     => CLike::contaLike($p->getId())
                ];
            }
        }

        $view = new VSearch();
        $view->risultati($tipo, $query, $order, $risultatiProgetti, $risultatiUtenti);
    }

    // ================================================
    // RICERCA — logica pura, usata anche dai test
    // ================================================

    // Cerca progetti per titolo, con ordinamento opzionale
    // Se la query è vuota, restituisce tutti i progetti
    // $order: 'date' (default) | 'likes' | 'name'
    public static function cercaProgetti(string $query, string $order = 'date'): array {
        if (empty($query))
            return FProject::retrieveAll($order);
        return FProject::searchByTitolo($query, $order);
    }

    // Cerca utenti per username
    // Se la query è vuota, restituisce tutti gli utenti registrati
    public static function cercaUtenti(string $query): array {
        if (empty($query))
            return FUser::retrieveAll();
        return FUser::searchByUsername($query);
    }
}

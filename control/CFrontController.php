<?php

/**
 * Front Controller — unico punto di ingresso dell'applicazione
 * Legge l'URL e chiama il controller e metodo corrispondente
 * URL formato: /Print3D/NomeController/nomeMetodo/param1/param2
 */
class CFrontController {

    public function run(string $url): void {
        $result = explode("/", $url);

        // Rimuove i primi due elementi (/Print3D e stringa vuota)
        array_shift($result);
        array_shift($result);

        // URL vuota o root → mostra la home
        if ($result[0] == "" || $result[0] == "index.php") {
            CHome::homePage();
            return;
        }

        // Costruisce il nome del controller dalla URL
        // es. "Project" → "CProject"
        $controller = "C" . $result[0];
        $directory  = "control";
        $scanDir    = scandir($directory);

        // Controlla se il controller esiste nella cartella control/
        if (in_array($controller . ".php", $scanDir)) {

            // Controller senza metodo → 404
            if (!isset($result[1])) {
                $view = new VError();
                $view->show404();
                return;
            }

            $method = $result[1];

            // Controlla se il metodo esiste nel controller
            if (method_exists($controller, $method)) {

                // Parametri dall'URL (dopo controller/metodo)
                $params = array_slice($result, 2);

                // Se la richiesta è POST, aggiunge i dati del form
                // ATTENZIONE: i campi del form devono essere nello stesso ordine
                // dei parametri del metodo nel controller
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $params = array_merge($params, array_values($_POST));

                    // Aggiunge eventuali file caricati, sempre dopo i campi POST
                    if (!empty($_FILES)) {
                        $params = array_merge($params, array_values($_FILES));
                    }
                }

                call_user_func_array([$controller, $method], $params);

            } else {
                // Metodo non trovato → 404
                $view = new VError();
                $view->show404();
            }

        } else {
            // Controller non trovato → 404
            $view = new VError();
            $view->show404();
        }
    }
}

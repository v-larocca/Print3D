<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pannello Admin - Print3D</title>
    <link rel="stylesheet" href="/print3d/css/bulma.css">
    <link rel="stylesheet" href="/print3d/css/custom.css">
</head>
<body>

{include file="partials/navbar.tpl"}

<section class="section">
    <div class="container">
        <h1 class="title">Gestione Utenti</h1>

        <table class="table is-fullwidth is-striped is-hoverable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Ruolo</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                {foreach $utenti as $u}
                    <tr>
                        <td>{$u.id}</td>
                        <td>{$u.username}</td>
                        <td>{$u.email}</td>
                        <td>
                            <span class="tag {if $u.ruolo == 'admin'}is-warning{else}is-light{/if}">
                                {$u.ruolo}
                            </span>
                        </td>
                        <td>
                            <div class="buttons are-small">
                                {if $u.ruolo == 'admin'}
                                    <a href="/print3d/User/cambiaRuoloAzione/{$u.id}/user" class="button is-light">
                                        Rendi utente
                                    </a>
                                {else}
                                    <a href="/print3d/User/cambiaRuoloAzione/{$u.id}/admin" class="button is-warning">
                                        Rendi admin
                                    </a>
                                {/if}
                                <a href="/print3d/User/eliminaUtenteAzione/{$u.id}" class="button is-danger"
                                   onclick="return confirm('Eliminare questo utente?')">
                                    Elimina
                                </a>
                            </div>
                        </td>
                    </tr>
                {foreachelse}
                    <tr><td colspan="5">Nessun utente trovato.</td></tr>
                {/foreach}
            </tbody>
        </table>

        <h1 class="title mt-6">Gestione Progetti</h1>

        <table class="table is-fullwidth is-striped is-hoverable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titolo</th>
                    <th>Categoria</th>
                    <th>Autore</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                {foreach $progetti as $p}
                    <tr>
                        <td>{$p.id}</td>
                        <td><a href="/print3d/Project/dettaglio/{$p.id}">{$p.titolo}</a></td>
                        <td><span class="tag is-info">{$p.categoria}</span></td>
                        <td>{$p.username}</td>
                        <td>
                            <form method="POST" action="/print3d/Project/eliminaProgetto/{$p.id}">
                                <button type="submit" class="button is-danger is-small"
                                        onclick="return confirm('Eliminare questo progetto?')">
                                    Elimina
                                </button>
                            </form>
                        </td>
                    </tr>
                {foreachelse}
                    <tr><td colspan="5">Nessun progetto trovato.</td></tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</section>

<script src="/print3d/js/main.js"></script>
</body>
</html>

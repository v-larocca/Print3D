<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cerca - Print3D</title>
    <link rel="stylesheet" href="/print3d/css/bulma.css">
    <link rel="stylesheet" href="/print3d/css/custom.css">
</head>
<body>

{include file="partials/navbar.tpl"}

<section class="section">
    <div class="container">
        <h1 class="title">Cerca</h1>

        <form method="POST" action="/print3d/Search/cerca" class="box">
            <div class="field">
                <div class="buttons has-addons tipo-toggle" id="tipoToggle">
                    <button type="button"
                            class="button {if $tipo == 'progetti' || !$tipo}is-link is-selected{/if}"
                            data-value="progetti">
                        📦 Progetti
                    </button>
                    <button type="button"
                            class="button {if $tipo == 'utenti'}is-link is-selected{/if}"
                            data-value="utenti">
                        👤 Utenti
                    </button>
                </div>
                <input type="hidden" name="tipo" id="tipoInput" value="{if $tipo}{$tipo}{else}progetti{/if}">
            </div>

            <div class="field has-addons">
                <div class="control is-expanded">
                    <input class="input" type="text" name="query" value="{$query}" placeholder="Cerca...">
                </div>
                <div class="control">
                    <div class="select">
                        <select name="order" id="orderSelect">
                            <option value="date" id="orderDateOption" {if $order == 'date'}selected{/if}>Più recenti</option>
                            <option value="likes" id="orderLikesOption" {if $order == 'likes'}selected{/if}>Più like</option>
                            <option value="name"  {if $order == 'name'}selected{/if}>Nome A-Z</option>
                        </select>
                    </div>
                </div>
                <div class="control">
                    <button type="submit" class="button is-warning">Cerca</button>
                </div>
            </div>
        </form>

        {if $tipo == 'utenti'}
            <h2 class="subtitle">Utenti trovati</h2>
            <div class="columns is-multiline">
                {foreach $risultatiUtenti as $u}
                    <div class="column is-3">
                        <div class="card">
                            <div class="card-content">
                                <p class="title is-6">
                                    <a href="/print3d/User/profile/{$u.id}">{$u.username}</a>
                                </p>
                                {if $u.isAdmin}<span class="tag is-warning">Admin</span>{/if}
                            </div>
                        </div>
                    </div>
                {foreachelse}
                    <div class="column is-12">
                        <div class="notification is-light">Nessun utente trovato.</div>
                    </div>
                {/foreach}
            </div>
        {elseif $tipo == 'progetti'}
            <h2 class="subtitle">Progetti trovati</h2>
            <div class="columns is-multiline">
                {foreach $risultatiProgetti as $p}
                    <div class="column is-3">
                        <a href="/print3d/Project/dettaglio/{$p.id}" class="project-card-link">
                            <div class="card project-card">
                                <div class="card-image">
                                    <figure class="image is-4by3">
                                        <img src="/print3d/File/streamImmagine/{$p.id}" alt="{$p.titolo}">
                                    </figure>
                                </div>
                                <div class="card-content">
                                    <p class="title is-6">{$p.titolo}</p>
                                    <div class="tags">
                                        <span class="tag is-info">{$p.categoria}</span>
                                        <span class="tag is-light">❤ {$p.likes}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                {foreachelse}
                    <div class="column is-12">
                        <div class="notification is-light">Nessun progetto trovato.</div>
                    </div>
                {/foreach}
            </div>
        {/if}
    </div>
</section>

<script src="/print3d/js/main.js"></script>
</body>
</html>

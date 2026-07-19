<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Print3D - Home</title>
    <link rel="stylesheet" href="/print3d/css/bulma.css">
    <link rel="stylesheet" href="/print3d/css/custom.css">
</head>
<body>

{include file="partials/navbar.tpl"}

<section class="section">
    <div class="container">

        <form method="POST" action="/print3d/Search/cerca" class="box mb-6">
            <div class="field">
                <div class="buttons has-addons tipo-toggle" id="tipoToggle">
                    <button type="button" class="button is-link is-selected" data-value="progetti">
                        📦 Progetti
                    </button>
                    <button type="button" class="button" data-value="utenti">
                        👤 Utenti
                    </button>
                </div>
                <input type="hidden" name="tipo" id="tipoInput" value="progetti">
            </div>

            <div class="field has-addons">
                <div class="control is-expanded">
                    <input class="input" type="text" name="query" placeholder="Cerca progetti o utenti...">
                </div>
                <div class="control">
                    <div class="select">
                        <select name="order" id="orderSelect">
                            <option value="date" id="orderDateOption">Più recenti</option>
                            <option value="likes" id="orderLikesOption">Più like</option>
                            <option value="name">Nome A-Z</option>
                        </select>
                    </div>
                </div>
                <div class="control">
                    <button type="submit" class="button is-warning">Cerca</button>
                </div>
            </div>
        </form>

        <h1 class="title">Progetti recenti</h1>

        <div class="columns is-multiline">
            {foreach $progetti as $p}
                <div class="column is-3">
                    <a href="/print3d/Project/dettaglio/{$p.id}" class="project-card-link">
                        <div class="card project-card">
                            <div class="card-image">
                                <figure class="image is-4by3">
                                    <img src="/print3d/File/streamImmagine/{$p.id}" alt="{$p.titolo}">
                                </figure>
                            </div>
                            <div class="card-content">
                                <p class="title is-5">{$p.titolo}</p>
                                <p class="subtitle is-6">{$p.descrizione}</p>
                                <div class="tags">
                                    <span class="tag is-info">{$p.categoria}</span>
                                    <span class="tag is-light">di {$p.username}</span>
                                </div>
                                <span class="icon-text">
                                    <span class="icon has-text-danger">❤</span>
                                    <span>{$p.likes}</span>
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            {foreachelse}
                <div class="column is-12">
                    <div class="notification is-light">
                        Nessun progetto disponibile al momento.
                    </div>
                </div>
            {/foreach}
        </div>
    </div>
</section>

<script src="/print3d/js/main.js"></script>
</body>
</html>

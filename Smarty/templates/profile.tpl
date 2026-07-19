<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$username} - Print3D</title>
    <link rel="stylesheet" href="/print3d/css/bulma.css">
    <link rel="stylesheet" href="/print3d/css/custom.css">
</head>
<body>

{include file="partials/navbar.tpl"}

<section class="section">
    <div class="container">
        <h1 class="title">
            {$username}
            {if $isAdmin}<span class="tag is-warning">Admin</span>{/if}
        </h1>

        <div class="buttons">
            <a class="button is-white js-modal-trigger" data-target="modalFollower">
                Follower: {$numFollower}
            </a>
            <a class="button is-white js-modal-trigger" data-target="modalSeguiti">
                Seguiti: {$numSeguiti}
            </a>
        </div>

        {if $navLoggato && !$isProfiloProprio}
            <div class="block">
                {if $staSeguendo}
                    <a href="/print3d/Follow/smettiDiSeguire/{$idUser}" class="button is-light">Smetti di seguire</a>
                {else}
                    <a href="/print3d/Follow/segui/{$idUser}" class="button is-warning">Segui</a>
                {/if}
            </div>
        {/if}

        <div class="tabs is-boxed">
            <ul>
                <li><a href="/print3d/User/profile/{$idUser}/uploaded">Progetti caricati</a></li>
                {if $isProfiloProprio}
                    <li><a href="/print3d/User/profile/{$idUser}/liked">Progetti piaciuti</a></li>
                {/if}
            </ul>
        </div>

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
                    <div class="notification is-light">Nessun progetto in questa sezione.</div>
                </div>
            {/foreach}
        </div>
    </div>
</section>

<!-- Modal Follower -->
<div class="modal" id="modalFollower">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Follower</p>
            <button class="delete modal-close-btn" aria-label="close"></button>
        </header>
        <section class="modal-card-body">
            <ul>
                {foreach $followerList as $f}
                    <li class="py-1">
                        <a href="/print3d/User/profile/{$f.id}">{$f.username}</a>
                    </li>
                {foreachelse}
                    <li>Nessun follower.</li>
                {/foreach}
            </ul>
        </section>
    </div>
</div>

<!-- Modal Seguiti -->
<div class="modal" id="modalSeguiti">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Seguiti</p>
            <button class="delete modal-close-btn" aria-label="close"></button>
        </header>
        <section class="modal-card-body">
            <ul>
                {foreach $followingList as $f}
                    <li class="py-1">
                        <a href="/print3d/User/profile/{$f.id}">{$f.username}</a>
                    </li>
                {foreachelse}
                    <li>Non segue ancora nessuno.</li>
                {/foreach}
            </ul>
        </section>
    </div>
</div>

<script src="/print3d/js/main.js"></script>
</body>
</html>

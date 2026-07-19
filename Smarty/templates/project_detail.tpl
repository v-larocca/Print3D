<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$titolo} - Print3D</title>
    <link rel="stylesheet" href="/print3d/css/bulma.css">
    <link rel="stylesheet" href="/print3d/css/custom.css">
</head>
<body>

{include file="partials/navbar.tpl"}

<section class="section">
    <div class="container">
        <div class="columns">
            <div class="column is-6">
                <figure class="image is-4by3">
                    <img src="/print3d/File/streamImmagine/{$id}" alt="{$titolo}">
                </figure>

                <div class="buttons mt-4">
                    {if $loggato}
                        <a href="/print3d/Like/{if $hasLike}rimuoviLike{else}aggiungiLike{/if}/{$id}"
                           class="like-btn button {if $hasLike}is-liked{/if}"
                           data-id="{$id}">
                            <span class="icon heart">❤</span>
                            <span>Mi piace (<span id="like-count-{$id}">{$likes}</span>)</span>
                        </a>
                    {else}
                        <span class="button" disabled>
                            <span class="icon has-text-danger">❤</span>
                            <span>{$likes}</span>
                        </span>
                    {/if}

                    <a href="/print3d/File/downloadZip/{$id}" class="button is-warning">
                        Scarica il progetto (.zip)
                    </a>
                </div>
            </div>

            <div class="column is-6">
                <h1 class="title">{$titolo}</h1>
                <p class="box mb-4">{$descrizione}</p>

                <div class="tags">
                    <span class="tag is-info">{$categoria}</span>
                </div>

                <p class="is-family-monospace is-italic has-text-weight-bold is-size-5 mb-4">
                    Caricato da
                    <a href="/print3d/User/profile/{$idAutore}">{$username}</a>
                </p>

                {if $puoEliminare}
                    <form method="POST" action="/print3d/Project/eliminaProgetto/{$id}" class="mt-4">
                        <button type="submit" class="button is-danger"
                                onclick="return confirm('Sei sicuro di voler eliminare questo progetto?')">
                            Elimina progetto
                        </button>
                    </form>
                {/if}
            </div>
        </div>
    </div>
</section>

<script src="/print3d/js/main.js"></script>
</body>
</html>

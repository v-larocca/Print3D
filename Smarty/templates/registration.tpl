<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrati - Print3D</title>
    <link rel="stylesheet" href="/print3d/css/bulma.css">
    <link rel="stylesheet" href="/print3d/css/custom.css">
</head>
<body>

{include file="partials/navbar.tpl"}

<section class="section">
    <div class="container">
        <div class="columns is-centered">
            <div class="column is-4">
                <div class="box">
                    <h1 class="title is-4 has-text-centered">Crea un account</h1>

                    {if $errore}
                        <div class="notification is-danger is-light">{$errore}</div>
                    {/if}

                    <form method="POST" action="/print3d/User/registra">
                        <div class="field">
                            <label class="label">Username</label>
                            <div class="control">
                                <input class="input" type="text" name="username" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Email</label>
                            <div class="control">
                                <input class="input" type="email" name="email" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Password</label>
                            <div class="control">
                                <input class="input" type="password" name="password" required>
                            </div>
                        </div>

                        <div class="field">
                            <div class="control">
                                <button type="submit" class="button is-warning is-fullwidth">Registrati</button>
                            </div>
                        </div>
                    </form>

                    <p class="has-text-centered mt-4">
                        Hai già un account? <a href="/print3d/User/showLoginForm">Accedi</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="/print3d/js/main.js"></script>
</body>
</html>

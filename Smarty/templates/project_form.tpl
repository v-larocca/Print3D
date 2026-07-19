<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Carica Progetto - Print3D</title>
    <link rel="stylesheet" href="/print3d/css/bulma.css">
    <link rel="stylesheet" href="/print3d/css/custom.css">
</head>
<body>

{include file="partials/navbar.tpl"}

<section class="section">
    <div class="container">
        <div class="columns is-centered">
            <div class="column is-6">
                <div class="box">
                    <h1 class="title is-4">Carica un nuovo progetto</h1>

                    {if $errore}
                        <div class="notification is-danger is-light">{$errore}</div>
                    {/if}

                    <form method="POST" action="/print3d/Project/creaProgetto" enctype="multipart/form-data">
                        <div class="field">
                            <label class="label">Titolo</label>
                            <div class="control">
                                <input class="input" type="text" name="titolo" required>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Descrizione (max 150 caratteri)</label>
                            <div class="control">
                                <textarea class="textarea" name="descrizione" maxlength="150" required></textarea>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Categoria</label>
                            <div class="control">
                                <div class="select is-fullwidth">
                                    <select name="categoria">
                                        <option value="Architettura">Architettura</option>
                                        <option value="Automotive">Automotive</option>
                                        <option value="Education">Education</option>
                                        <option value="Fashion">Fashion</option>
                                        <option value="Gaming">Gaming</option>
                                        <option value="Gioielleria">Gioielleria</option>
                                        <option value="Hobby">Hobby</option>
                                        <option value="Miniature">Miniature</option>
                                        <option value="Robotica">Robotica</option>
                                        <option value="Strumenti">Strumenti</option>
                                        <option value="Arte">Arte</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">Immagine di anteprima</label>
                            <div class="file has-name is-fullwidth">
                                <label class="file-label">
                                    <input class="file-input" type="file" name="immagine" accept="image/*" required>
                                    <span class="file-cta">
                                        <span class="file-label">Scegli un file...</span>
                                    </span>
                                    <span class="file-name">Nessun file selezionato</span>
                                </label>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label">File ZIP del progetto</label>
                            <div class="file has-name is-fullwidth">
                                <label class="file-label">
                                    <input class="file-input" type="file" name="zip" accept=".zip" required>
                                    <span class="file-cta">
                                        <span class="file-label">Scegli un file...</span>
                                    </span>
                                    <span class="file-name">Nessun file selezionato</span>
                                </label>
                            </div>
                        </div>

                        <div class="field">
                            <div class="control">
                                <button type="submit" class="button is-warning is-fullwidth">Carica</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="/print3d/js/main.js"></script>
</body>
</html>

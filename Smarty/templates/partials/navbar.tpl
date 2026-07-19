{* ================================================
   PARTIAL — Navbar condivisa
   Variabili richieste: $navLoggato, $navIdUtente, $navIsAdmin
   ================================================ *}

<nav class="navbar has-background-white has-text-dark" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
        <a class="navbar-item" href="/print3d/">
            <strong class="has-text-link is-size-3">Print3D</strong>
        </a>

        <a role="button" class="navbar-burger" data-target="navMenu" aria-label="menu" aria-expanded="false">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </a>
    </div>

    <div id="navMenu" class="navbar-menu">
        <div class="navbar-start">
            {if $navLoggato}
                <a class="navbar-item has-text-dark" href="/print3d/Project/formCreazione">Carica progetto</a>
            {/if}
        </div>

        <div class="navbar-end">
            {if $navLoggato}
                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link has-text-dark">Account</a>
                    <div class="navbar-dropdown is-right">
                        <a class="navbar-item" href="/print3d/User/profile/{$navIdUtente}">Il mio profilo</a>
                        {if $navIsAdmin}
                            <a class="navbar-item" href="/print3d/User/dashboard">Pannello Admin</a>
                        {/if}
                        <hr class="navbar-divider">
                        <a class="navbar-item" href="/print3d/User/logout">Logout</a>
                    </div>
                </div>
            {else}
                <div class="navbar-item">
                    <div class="buttons">
                        <a class="button is-warning" href="/print3d/User/showRegistrationForm">
                            <strong>Registrati</strong>
                        </a>
                        <a class="button is-light" href="/print3d/User/showLoginForm">
                            Login
                        </a>
                    </div>
                </div>
            {/if}
        </div>
    </div>
</nav>
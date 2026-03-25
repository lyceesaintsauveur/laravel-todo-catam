<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title')</title>

        <!-- Styles et Scripts : Utilisation SASS --> 
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
        
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light ">
    <a class="navbar-brand" href="/">Ma Todo List</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse d-flex" id="navbarSupportedContent">
            @auth
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">              
                    <!--Lien vers la page d'accueil-->
                    <li class="nav-item active">
                        <a class="nav-link" href="/"><i class="bi bi-card-checklist"></i>Todos</a>
                    </li>
                    <!--Lien vers la page du compteur-->
                    <li class="nav-item active">
                            <a class="nav-link" href="compteur"><i class="bi bi-speedometer2"></i>Compteur</a>
                    </li>
                    <!--Lien vers la page Création d'une liste-->
                    <li class="nav-item active">
                        <a class="nav-link" href="liste"><i class="bi bi-plus-circle"></i>Gestion des listes</a>
                    </li>
                    <!--Lien vers la page de planning-->
                    <li class="nav-item active">
                        <a class="nav-link" href="planning"><i class="bi bi-calendar"></i>Planning</a>
                    </li>
                    <li>
                        <!-- Profil -->
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNavDropdown">
                            <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div><i class="bi bi-info-circle"></i>{{ Auth::user()->name }}</div>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    <a class="dropdown-item" href="/profile">Mon profil</a>
                                    <!-- Correction de l'appel de la route logout -->
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Déconnexion</button>
                                        </form>    
                                    </li>
                                </ul>
                            </li>
                            </ul>
                        </div>
                        <!-- Fin Profil -->
                    </li>
        </ul>
        @endauth
        <form class="d-flex" method="POST" action="{{ route('todos.search.submit') }}">
        @csrf
            <input class="form-control me-2" type="search" name="mot"
                    value="{{ old('mot') }}" placeholder="Rechercher des todos…" aria-label="Search">
            <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
    </div>
</div>
</nav>

        @yield('content')
        @stack('scripts')
    </body>
</html>
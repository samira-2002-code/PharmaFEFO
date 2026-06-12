
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PharmaFEFO – <?= $pageTitle ?? 'Gestion de stock' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Couleur accent principale */
        :root { --accent: #0f6cbf; }
        .nav-link { @apply flex items-center gap-2 px-3 py-2 rounded-lg text-slate-300 hover:bg-slate-700 hover:text-white transition-colors text-sm font-medium; }
        .nav-link.active { @apply bg-blue-600 text-white; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen flex">

<!-- Sidebar -->
<aside class="w-60 min-h-screen bg-slate-900 flex flex-col shrink-0">
    <!-- Logo -->
    <div class="px-5 py-5 border-b border-slate-700">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
            </div>
            <div>
                <div class="text-white font-bold text-sm leading-none">PharmaFEFO</div>
                <div class="text-slate-400 text-xs">Gestion de stock</div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-4 flex flex-col gap-1">
        <p class="text-xs text-slate-500 uppercase tracking-wider px-2 mb-2 font-semibold">Menu</p>

        <a href="index.php?page=dashboard"
            class="nav-link <?= (($page ?? $_GET['page'] ?? 'dashboard') === 'dashboard') ? 'active' : '' ?> ">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Tableau de bord
        </a>

        <a href="index.php?page=lots"
            class="nav-link <?= (($page ?? $_GET['page'] ?? '') === 'lots') ? 'active' : '' ?>">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            Gestion des lots
        </a>

        <a href="index.php?page=rapport"
            class="nav-link <?= (($page ?? $_GET['page'] ?? '') === 'rapport') ? 'active' : '' ?>">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Rapport des pertes
        </a>


        
        <a href="index.php?action=logout"
            class="nav-link <?= (($page ?? $_GET['page'] ?? '') === 'lots') ? 'active' : '' ?>">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            Logout
        </a>
    </nav>

    <!-- Footer sidebar -->
    <div class="px-4 py-3 border-t border-slate-700">
        <p class="text-xs text-slate-500">Méthode FEFO · PHP 8 MVC</p>
    </div>
</aside>

<!-- Contenu principal -->
<main class="flex-1 flex flex-col min-h-screen">
    <!-- Topbar -->
    <header class="bg-white border-b border-slate-200 px-6 py-3 flex items-center justify-between shrink-0">
        <h1 class="text-lg font-semibold text-slate-800"><?= htmlspecialchars($pageTitle ?? 'Tableau de bord') ?></h1>
        <div class="text-sm text-slate-500"><?= date('d/m/Y') ?></div>
    </header>

    <!-- Zone de contenu -->
    <div class="flex-1 p-6">

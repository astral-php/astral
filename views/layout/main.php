<!DOCTYPE html>
<html lang="fr" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Astral MVC', ENT_QUOTES, 'UTF-8') ?> — Astral MVC</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="h-full font-sans antialiased text-gray-800">

    <!-- Barre de navigation -->
    <nav class="bg-indigo-700 shadow-lg">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                <!-- Logo -->
                <a href="/" class="flex items-center gap-2 text-white font-bold text-xl tracking-tight">
                    <span class="bg-white/20 rounded-lg px-2 py-0.5 text-sm font-mono">✦</span>
                    Astral MVC
                </a>

                <!-- Navigation -->
                <ul class="flex items-center gap-5 text-sm font-medium text-indigo-100">

                    <li><a href="/" class="hover:text-white transition">Accueil</a></li>
                    <li><a href="/docs" class="hover:text-white transition">Documentation</a></li>

                    <?php if ($auth->check()): ?>

                        <?php if ($auth->is(\Core\Auth\Role::ADMIN)): ?>
                            <li><a href="/users" class="hover:text-white transition">Utilisateurs</a></li>
                            <li>
                                <a href="/admin/users"
                                   class="inline-flex items-center gap-1 bg-amber-400/20 hover:bg-amber-400/35 text-amber-200 text-xs font-semibold px-2 py-0.5 rounded-full border border-amber-400/30 transition"
                                   title="Gestion des rôles">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    Admin
                                </a>
                            </li>
                        <?php endif ?>

                        <li>
                            <a href="/profile" class="hover:text-white transition">
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center text-xs font-bold">
                                        <?= mb_strtoupper(mb_substr($auth->name(), 0, 1)) ?>
                                    </span>
                                    <?= htmlspecialchars($auth->name(), ENT_QUOTES, 'UTF-8') ?>
                                </span>
                            </a>
                        </li>

                        <li>
                            <form method="POST" action="/logout" class="inline">
                                <?= $csrf->field() ?>
                                <button type="submit" class="hover:text-white transition text-indigo-300 text-xs">
                                    Déconnexion
                                </button>
                            </form>
                        </li>

                    <?php else: ?>
                        <li><a href="/login" class="hover:text-white transition">Connexion</a></li>
                        <li>
                            <a href="/register"
                               class="bg-white/15 hover:bg-white/25 text-white px-3 py-1.5 rounded-lg transition text-xs font-semibold">
                                Inscription
                            </a>
                        </li>
                    <?php endif ?>

                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <?= $viewEngine->partial('partials/flash') ?>

        <?= $content ?>

    </main>

    <!-- Pied de page -->
    <footer class="mt-16 border-t border-gray-200 py-6 text-center text-xs text-gray-400">
        <span class="font-medium text-indigo-400">✦ Astral MVC</span>
        &mdash; PHP <?= PHP_VERSION ?> &mdash; Tailwind CSS
        &mdash; <a href="/docs" class="hover:text-indigo-400 transition">Documentation</a>
    </footer>

</body>
</html>

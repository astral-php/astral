<!-- Vue : home/index -->

<?php if (!$hasUsers): ?>
<!-- Bannière installation initiale -->
<div class="mb-8 flex items-start gap-4 bg-amber-50 border border-amber-200 rounded-2xl px-6 py-5">
    <div class="shrink-0 flex items-center justify-center w-10 h-10 rounded-xl bg-amber-100 mt-0.5">
        <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z"/>
        </svg>
    </div>
    <div class="flex-1">
        <p class="font-semibold text-amber-800">Installation initiale</p>
        <p class="text-sm text-amber-700 mt-0.5">
            Aucun compte n'existe encore. Le premier compte créé sera automatiquement
            <strong>administrateur</strong> de l'application.
        </p>
    </div>
    <a href="/register"
       class="shrink-0 self-center inline-flex items-center gap-1.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold px-4 py-2 rounded-xl transition shadow-sm">
        Créer l'administrateur
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
        </svg>
    </a>
</div>
<?php endif ?>

<!-- Hero -->
<div class="text-center py-14">
    <div class="inline-flex items-center gap-2 bg-indigo-50 text-indigo-700 text-xs font-semibold px-3 py-1.5 rounded-full mb-6 border border-indigo-100">
        v<?= htmlspecialchars($version, ENT_QUOTES, 'UTF-8') ?> &bull; PSR-4 &bull; Tailwind CSS
    </div>
    <h1 class="text-5xl font-extrabold text-gray-900 tracking-tight mb-4">
        <?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>
    </h1>
    <p class="text-xl text-gray-500 max-w-2xl mx-auto mb-10">
        <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
    </p>

    <!-- CTA — 3 états -->
    <div class="flex flex-wrap justify-center gap-4">
        <?php if (!$hasUsers): ?>
            <a href="/register"
               class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold px-6 py-3 rounded-xl transition shadow">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                </svg>
                Créer le compte administrateur
            </a>
        <?php elseif ($auth->check()): ?>
            <?php if ($auth->is(\Core\Auth\Role::ADMIN)): ?>
                <a href="/admin/users"
                   class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-3 rounded-xl transition shadow">
                    Ouvrir l’administration
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
                <a href="/users"
                   class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 font-semibold px-6 py-3 rounded-xl border border-gray-200 transition shadow-sm">
                    Gérer les utilisateurs
                </a>
            <?php else: ?>
                <a href="/users"
                   class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-3 rounded-xl transition shadow">
                    Gérer les utilisateurs
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
            <?php endif ?>
        <?php else: ?>
            <a href="/login"
               class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-3 rounded-xl transition shadow">
                Se connecter
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                </svg>
            </a>
            <a href="/register"
               class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 font-semibold px-6 py-3 rounded-xl border border-gray-200 transition shadow-sm">
                Créer un compte
            </a>
        <?php endif ?>
    </div>
</div>

<!-- Grille de fonctionnalités -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mt-4">

    <!-- Routeur -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
        <div class="flex items-center gap-3 mb-3">
            <span class="flex items-center justify-center w-9 h-9 rounded-xl bg-indigo-50">
                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497z"/>
                </svg>
            </span>
            <div>
                <div class="text-xs font-semibold text-indigo-600 uppercase tracking-wide">Routeur</div>
                <h3 class="font-bold text-gray-800 text-sm">Routes & Groupes</h3>
            </div>
        </div>
        <p class="text-gray-500 text-sm leading-relaxed">
            Routes statiques & dynamiques (<code class="bg-gray-100 px-1 rounded text-xs">:param</code>),
            verbes complets GET / POST / PUT / PATCH / DELETE, verb spoofing, groupes avec préfixe.
        </p>
    </div>

    <!-- DI Container -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
        <div class="flex items-center gap-3 mb-3">
            <span class="flex items-center justify-center w-9 h-9 rounded-xl bg-violet-50">
                <svg class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.25 6.087c0-.355.186-.676.401-.959.221-.29.349-.634.349-1.003 0-1.036-1.007-1.875-2.25-1.875s-2.25.84-2.25 1.875c0 .369.128.713.349 1.003.215.283.401.604.401.959v0a.64.64 0 01-.657.643 48.39 48.39 0 01-4.163-.3c.186 1.613.293 3.25.315 4.907a.656.656 0 01-.658.663v0c-.355 0-.676-.186-.959-.401a1.647 1.647 0 00-1.003-.349c-1.036 0-1.875 1.007-1.875 2.25s.84 2.25 1.875 2.25c.369 0 .713-.128 1.003-.349.283-.215.604-.401.959-.401v0c.31 0 .555.26.532.57a48.039 48.039 0 01-.642 5.056c1.518.19 3.058.309 4.616.354a.64.64 0 00.657-.643v0c0-.355-.186-.676-.401-.959a1.647 1.647 0 01-.349-1.003c0-1.035 1.008-1.875 2.25-1.875 1.243 0 2.25.84 2.25 1.875 0 .369-.128.713-.349 1.003-.215.283-.401.604-.401.959v0c0 .31.26.555.57.532a48.073 48.073 0 005.054-.642.75.75 0 00.423-1.019 48.034 48.034 0 00-4.87-9.35.75.75 0 00-1.228 0z"/>
                </svg>
            </span>
            <div>
                <div class="text-xs font-semibold text-violet-600 uppercase tracking-wide">Conteneur DI</div>
                <h3 class="font-bold text-gray-800 text-sm">Autowiring & Providers</h3>
            </div>
        </div>
        <p class="text-gray-500 text-sm leading-relaxed">
            Injection de dépendances par réflexion, singletons, Service Providers organisés
            (<code class="bg-gray-100 px-1 rounded text-xs">FrameworkServiceProvider</code>,
            <code class="bg-gray-100 px-1 rounded text-xs">AppServiceProvider</code>…).
        </p>
    </div>

    <!-- Auth -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
        <div class="flex items-center gap-3 mb-3">
            <span class="flex items-center justify-center w-9 h-9 rounded-xl bg-emerald-50">
                <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                </svg>
            </span>
            <div>
                <div class="text-xs font-semibold text-emerald-600 uppercase tracking-wide">Authentification</div>
                <h3 class="font-bold text-gray-800 text-sm">Auth & Rôles</h3>
            </div>
        </div>
        <p class="text-gray-500 text-sm leading-relaxed">
            Connexion / inscription, rôles <code class="bg-gray-100 px-1 rounded text-xs">admin</code> /
            <code class="bg-gray-100 px-1 rounded text-xs">user</code>, middleware
            <code class="bg-gray-100 px-1 rounded text-xs">AuthMiddleware</code>,
            <code class="bg-gray-100 px-1 rounded text-xs">AdminMiddleware</code>,
            <code class="bg-gray-100 px-1 rounded text-xs">GuestMiddleware</code>.
        </p>
    </div>

    <!-- Base de données -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
        <div class="flex items-center gap-3 mb-3">
            <span class="flex items-center justify-center w-9 h-9 rounded-xl bg-sky-50">
                <svg class="w-5 h-5 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 2.625v2.625m0 2.625v2.625"/>
                </svg>
            </span>
            <div>
                <div class="text-xs font-semibold text-sky-600 uppercase tracking-wide">Base de données</div>
                <h3 class="font-bold text-gray-800 text-sm">DAO + Pagination</h3>
            </div>
        </div>
        <p class="text-gray-500 text-sm leading-relaxed">
            CRUD générique PDO, pagination intégrée (<code class="bg-gray-100 px-1 rounded text-xs">paginate()</code>),
            bascule SQLite / MySQL via <code class="bg-gray-100 px-1 rounded text-xs">.env</code> sans modifier le code.
        </p>
    </div>

    <!-- Middleware & CSRF -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
        <div class="flex items-center gap-3 mb-3">
            <span class="flex items-center justify-center w-9 h-9 rounded-xl bg-amber-50">
                <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                </svg>
            </span>
            <div>
                <div class="text-xs font-semibold text-amber-600 uppercase tracking-wide">Sécurité</div>
                <h3 class="font-bold text-gray-800 text-sm">CSRF & Middleware</h3>
            </div>
        </div>
        <p class="text-gray-500 text-sm leading-relaxed">
            Protection CSRF par token de session, pipeline middleware par route ou par groupe,
            validation déclarative des données entrantes.
        </p>
    </div>

    <!-- Logger / Cache / CLI / Migrations -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
        <div class="flex items-center gap-3 mb-3">
            <span class="flex items-center justify-center w-9 h-9 rounded-xl bg-rose-50">
                <svg class="w-5 h-5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z"/>
                </svg>
            </span>
            <div>
                <div class="text-xs font-semibold text-rose-600 uppercase tracking-wide">Outils</div>
                <h3 class="font-bold text-gray-800 text-sm">Logger · Cache · CLI</h3>
            </div>
        </div>
        <p class="text-gray-500 text-sm leading-relaxed">
            Logger fichier journalier, cache fichier avec TTL (<code class="bg-gray-100 px-1 rounded text-xs">remember()</code>),
            console CLI (<code class="bg-gray-100 px-1 rounded text-xs">php bin/console make:module</code>,
            <code class="bg-gray-100 px-1 rounded text-xs">php bin/console migrate</code>) pour scaffolding et migrations.
        </p>
    </div>

</div>

<!-- Bannière statut connecté -->
<?php if ($auth->check()): ?>
<div class="mt-10 bg-gradient-to-r from-indigo-50 to-violet-50 border border-indigo-100 rounded-2xl p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
    <div>
        <p class="font-semibold text-gray-800">
            Connecté en tant que
            <span class="text-indigo-700"><?= htmlspecialchars($auth->name(), ENT_QUOTES, 'UTF-8') ?></span>
            <?php if ($auth->is(\Core\Auth\Role::ADMIN)): ?>
                <span class="ml-2 inline-flex items-center gap-1 bg-amber-100 text-amber-700 text-xs font-semibold px-2 py-0.5 rounded-full border border-amber-200">
                    Admin
                </span>
            <?php endif ?>
        </p>
        <p class="text-sm text-gray-500 mt-0.5"><?= htmlspecialchars($auth->email(), ENT_QUOTES, 'UTF-8') ?></p>
    </div>
    <?php if ($auth->is(\Core\Auth\Role::ADMIN)): ?>
        <a href="/admin/users"
           class="shrink-0 inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition shadow">
            Ouvrir l’administration &rarr;
        </a>
    <?php else: ?>
        <a href="/users"
           class="shrink-0 inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition shadow">
            Gérer les utilisateurs &rarr;
        </a>
    <?php endif ?>
</div>
<?php endif ?>

<!-- Vue : errors/500 -->
<div class="text-center py-24">
    <p class="text-8xl font-black text-orange-100 mb-4">500</p>
    <h1 class="text-2xl font-bold text-gray-800 mb-2">Erreur serveur</h1>
    <p class="text-gray-400 mb-8">
        <?= htmlspecialchars($message ?? 'Une erreur interne est survenue. Veuillez réessayer.', ENT_QUOTES, 'UTF-8') ?>
    </p>
    <a href="/" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-3 rounded-xl transition shadow">
        &larr; Retour à l'accueil
    </a>
</div>

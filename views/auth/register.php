<!-- Vue : auth/register -->
<div class="max-w-md mx-auto">

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">

        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 bg-indigo-100 rounded-full mb-4">
                <svg class="w-7 h-7 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Créer un compte</h1>
            <p class="mt-1 text-sm text-gray-500">Rejoignez-nous en quelques secondes</p>
        </div>

        <form method="POST" action="/register" class="space-y-5">

            <?= $csrf->field() ?>

            <!-- Nom -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                <input type="text" id="name" name="name" autocomplete="name" autofocus
                       value="<?= htmlspecialchars($old['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                       class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 transition
                              <?= !empty($errors['name']) ? 'border-red-400 bg-red-50' : 'border-gray-200' ?>"
                       placeholder="Jean Dupont">
                <?php if (!empty($errors['name'])): ?>
                    <p class="mt-1 text-xs text-red-600"><?= htmlspecialchars($errors['name'][0], ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif ?>
            </div>

            <!-- E-mail -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse e-mail</label>
                <input type="email" id="email" name="email" autocomplete="email"
                       value="<?= htmlspecialchars($old['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                       class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 transition
                              <?= !empty($errors['email']) ? 'border-red-400 bg-red-50' : 'border-gray-200' ?>"
                       placeholder="jean@exemple.com">
                <?php if (!empty($errors['email'])): ?>
                    <p class="mt-1 text-xs text-red-600"><?= htmlspecialchars($errors['email'][0] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif ?>
            </div>

            <!-- Mot de passe -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe <span class="text-gray-400 font-normal">(min. 8 caractères)</span></label>
                <input type="password" id="password" name="password" autocomplete="new-password" minlength="8"
                       class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 transition
                              <?= !empty($errors['password']) ? 'border-red-400 bg-red-50' : 'border-gray-200' ?>"
                       placeholder="••••••••">
                <?php if (!empty($errors['password'])): ?>
                    <p class="mt-1 text-xs text-red-600"><?= htmlspecialchars($errors['password'][0], ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif ?>
            </div>

            <!-- Confirmation -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
                <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password"
                       class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 transition
                              <?= !empty($errors['password_confirmation']) ? 'border-red-400 bg-red-50' : 'border-gray-200' ?>"
                       placeholder="••••••••">
                <?php if (!empty($errors['password_confirmation'])): ?>
                    <p class="mt-1 text-xs text-red-600"><?= htmlspecialchars($errors['password_confirmation'][0], ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif ?>
            </div>

            <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-xl transition shadow text-sm">
                Créer le compte
            </button>

        </form>

        <p class="mt-6 text-center text-sm text-gray-500">
            Déjà inscrit ?
            <a href="/login" class="text-indigo-600 hover:underline font-medium">Se connecter</a>
        </p>

    </div>
</div>

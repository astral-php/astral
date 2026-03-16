<!-- Vue : auth/login -->
<div class="max-w-md mx-auto">

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">

        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 bg-indigo-100 rounded-full mb-4">
                <svg class="w-7 h-7 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Connexion</h1>
            <p class="mt-1 text-sm text-gray-500">Accédez à votre espace personnel</p>
        </div>

        <form method="POST" action="/login" class="space-y-5">

            <?= $csrf->field() ?>

            <!-- E-mail -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse e-mail</label>
                <input type="email" id="email" name="email" autocomplete="email" autofocus
                       value="<?= htmlspecialchars($old['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                       class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 transition
                              <?= !empty($errors['email']) ? 'border-red-400 bg-red-50' : 'border-gray-200' ?>"
                       placeholder="vous@exemple.com">
                <?= $viewEngine->partial('partials/field-error', ['field' => 'email', 'errors' => $errors ?? []]) ?>
            </div>

            <!-- Mot de passe -->
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                    <a href="/forgot-password" class="text-xs text-indigo-600 hover:underline">Mot de passe oublié ?</a>
                </div>
                <input type="password" id="password" name="password" autocomplete="current-password"
                       class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 transition
                              <?= !empty($errors['password']) ? 'border-red-400 bg-red-50' : 'border-gray-200' ?>"
                       placeholder="••••••••">
                <?= $viewEngine->partial('partials/field-error', ['field' => 'password', 'errors' => $errors ?? []]) ?>
            </div>

            <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-xl transition shadow text-sm">
                Se connecter
            </button>

        </form>

        <p class="mt-6 text-center text-sm text-gray-500">
            Pas encore de compte ?
            <a href="/register" class="text-indigo-600 hover:underline font-medium">Créer un compte</a>
        </p>

    </div>
</div>

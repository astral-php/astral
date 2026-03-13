<!-- Vue : auth/forgot-password -->
<div class="max-w-md mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">

        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 bg-indigo-100 rounded-full mb-4">
                <svg class="w-7 h-7 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Mot de passe oublié</h1>
            <p class="mt-1 text-sm text-gray-500">Saisissez votre e-mail pour recevoir un lien de réinitialisation</p>
        </div>

        <?php if ($sent): ?>

            <!-- Message de confirmation -->
            <div class="flex items-start gap-3 bg-green-50 border border-green-200 rounded-xl px-4 py-4 mb-6">
                <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-green-800">
                    Si cette adresse e-mail est associée à un compte, vous recevrez
                    un lien de réinitialisation dans quelques instants.
                </p>
            </div>

        <?php else: ?>

            <form method="POST" action="/forgot-password" class="space-y-5">

                <?= $csrf->field() ?>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse e-mail</label>
                    <input type="email" id="email" name="email" autocomplete="email" autofocus
                           value="<?= htmlspecialchars($old['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                           class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 transition
                                  <?= !empty($errors['email']) ? 'border-red-400 bg-red-50' : 'border-gray-200' ?>"
                           placeholder="vous@exemple.com">
                    <?php if (!empty($errors['email'])): ?>
                        <p class="mt-1 text-xs text-red-600"><?= htmlspecialchars($errors['email'][0] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endif ?>
                </div>

                <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-xl transition shadow text-sm">
                    Envoyer le lien
                </button>

            </form>

        <?php endif ?>

        <p class="mt-6 text-center text-sm text-gray-500">
            <a href="/login" class="text-indigo-600 hover:underline font-medium">← Retour à la connexion</a>
        </p>

    </div>
</div>

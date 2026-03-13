<!-- Vue : auth/verify-pending -->
<div class="max-w-md mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center">

        <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-100 rounded-full mb-6">
            <svg class="w-8 h-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-gray-900 mb-3">Vérifiez vos e-mails</h1>

        <p class="text-gray-500 text-sm leading-relaxed mb-6">
            Un lien de confirmation vient d'être envoyé à votre adresse e-mail.<br>
            Cliquez sur ce lien pour activer votre compte.
        </p>

        <div class="bg-amber-50 border border-amber-100 rounded-xl px-4 py-3 text-left text-sm text-amber-800 mb-8">
            <p class="font-medium mb-1">Vous ne trouvez pas l'e-mail ?</p>
            <ul class="list-disc list-inside space-y-1 text-amber-700">
                <li>Vérifiez vos dossiers <strong>Spam</strong> ou <strong>Courrier indésirable</strong></li>
                <li>L'e-mail peut prendre quelques minutes à arriver</li>
            </ul>
        </div>

        <a href="/login" class="inline-flex items-center gap-2 text-sm text-indigo-600 hover:underline font-medium">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
            Retour à la connexion
        </a>

    </div>
</div>

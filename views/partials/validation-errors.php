<?php

declare(strict_types=1);

/**
 * Partial : liste globale des erreurs de validation.
 * Variables : $errors (array<string, list<string>>) — optionnel.
 * Tailwind CSS.
 */
$errors = $errors ?? [];
if (empty($errors)) {
    return;
}
$flat = [];
foreach ($errors as $messages) {
    foreach ((array) $messages as $msg) {
        $flat[] = $msg;
    }
}
if (empty($flat)) {
    return;
}
?>
<div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
    <p class="font-medium">Veuillez corriger les erreurs suivantes :</p>
    <ul class="mt-1 list-disc list-inside space-y-0.5">
        <?php foreach ($flat as $msg): ?>
            <li><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></li>
        <?php endforeach; ?>
    </ul>
</div>

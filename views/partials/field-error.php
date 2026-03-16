<?php

declare(strict_types=1);

/**
 * Partial : message d'erreur sous un champ de formulaire.
 * Variables : $field (string), $errors (array<string, list<string>>).
 * Tailwind CSS. À placer après l'input.
 */
$field   = $field ?? '';
$errors  = $errors ?? [];
$message = $errors[$field][0] ?? null;
if ($message === null || $message === '') {
    return;
}
?>
<p class="mt-1 text-xs text-red-600"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>

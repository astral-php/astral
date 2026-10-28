# Upgrade — Astral MVC (hub) 1.x → 2.0

## Prérequis

- PHP **8.3+**
- `"astral-php/astral-core": "^2.0"`

```bash
composer require astral-php/astral-core:^2.0
```

Voir aussi le guide core : [astral-core UPGRADE-2.0](https://github.com/astral-php/astral-core/blob/main/UPGRADE-2.0.md).

## Role

| 1.x | 2.0 |
|-----|-----|
| `Role::ADMIN` | `Role::Admin` |
| `Role::USER` | `Role::User` |
| `Role::GUEST` | `Role::Guest` |

Les valeurs string (`admin`, `user`, `guest`) sont inchangées.  
`Auth::is()` / `can()` acceptent `Role|string`.  
Comparaisons avec une propriété string (`$user->role`) : utiliser `Role::Admin->value`.

## Cache

Si vous utilisez `Core\Cache` directement : sémantique `null` / TTL / `unserialize` — voir le guide core.

## Apps encore en ^1

Starter, blog, shop peuvent rester sur `astral-core:^1` jusqu’à leur vague de migration. Le hub **2.0** est le premier consommateur officiel de core ^2.

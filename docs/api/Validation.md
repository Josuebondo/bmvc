# Core\Validateur - API Reference

Validation des données avec règles flexibles.

## Méthode Principale

### valider(array $data, array $rules, array $messages = []): array

Valide les données et retourne les erreurs.

```php
$erreurs = $this->valider($_POST, [
    'email' => 'requis|email',
    'password' => 'requis|min:8'
]);

if (!empty($erreurs)) {
    return $this->json(['errors' => $erreurs], 422);
}
```

## Règles Disponibles

| Règle                 | Description         | Exemple                                          |
| --------------------- | ------------------- | ------------------------------------------------ |
| `requis`              | Champ obligatoire   | `'nom' => 'requis'`                              |
| `email`               | Format email valide | `'email' => 'email'`                             |
| `numero`              | Valeur numérique    | `'age' => 'numero'`                              |
| `min:N`               | Longueur minimum    | `'pwd' => 'min:8'`                               |
| `max:N`               | Longueur maximum    | `'nom' => 'max:100'`                             |
| `longueur:N`          | Longueur exacte     | `'code' => 'longueur:5'`                         |
| `url`                 | URL valide          | `'site' => 'url'`                                |
| `date`                | Format date         | `'date' => 'date'`                               |
| `unique:table,column` | Unique en BD        | `'email' => 'unique:users,email'`                |
| `confirmed`           | Champ confirmation  | `'pwd' => 'confirmed'` (attend pwd_confirmation) |
| `in:val1,val2`        | Liste valeurs       | `'role' => 'in:user,admin'`                      |
| `regex:pattern`       | Regex personnalisé  | `'code' => 'regex:/^[A-Z0-9]+$/'`                |

## Exemples

### Validation Simple

```php
$erreurs = $this->valider($_POST, [
    'email' => 'requis|email',
    'password' => 'requis|min:8'
]);
```

### Messages Personnalisés

```php
$erreurs = $this->valider($_POST, [
    'email' => 'requis|email',
    'age' => 'numero'
], [
    'email.requis' => 'L\'email est obligatoire',
    'email.email' => 'Email invalide',
    'age.numero' => 'L\'âge doit être un nombre'
]);
```

### Afficher les Erreurs

```html
<?php if (!empty($erreurs)): ?>
<div class="alert alert-danger">
  <?php foreach ($erreurs as $champ =>
  $messages): ?>
  <?php foreach ($messages as $message): ?>
  <p><?php echo e($message); ?></p>
  <?php endforeach; ?>
  <?php endforeach; ?>
</div>
<?php endif; ?>
```

---

[← Retour à INDEX](INDEX.md)

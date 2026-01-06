<?php etendre('layouts.app'); ?>

<?php debut_section('contenu'); ?>

<?php
$erreurs = $erreurs ?? [];
$anciens_inputs = $anciens_inputs ?? [];
?>

<h2>Créer un nouvel article</h2>

<?php if (!empty($erreurs)): ?>
    <div style="background: #fee; border: 1px solid #fcc; padding: 15px; margin-bottom: 20px; border-radius: 4px; color: #c33;">
        <h3>⚠️ Erreurs de validation</h3>
        <ul>
            <?php foreach ($erreurs as $champ => $messages): ?>
                <?php foreach ($messages as $message): ?>
                    <li><?php echo htmlspecialchars($message); ?></li>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" action="<?php echo url('/articles/creer'); ?>" style="max-width: 600px;">
    <div style="margin-bottom: 20px;">
        <label for="titre" style="display: block; margin-bottom: 5px; font-weight: 500;">Titre *</label>
        <input
            type="text"
            name="titre"
            id="titre"
            value="<?php echo htmlspecialchars($anciens_inputs['titre'] ?? ''); ?>"
            style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;"
            placeholder="Entrez le titre de l'article"
            required>
    </div>

    <div style="margin-bottom: 20px;">
        <label for="contenu" style="display: block; margin-bottom: 5px; font-weight: 500;">Contenu *</label>
        <textarea
            name="contenu"
            id="contenu"
            rows="8"
            style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;"
            placeholder="Entrez le contenu de l'article"
            required><?php echo htmlspecialchars($anciens_inputs['contenu'] ?? ''); ?></textarea>
    </div>

    <div style="display: flex; gap: 10px;">
        <button type="submit" style="background: #667eea; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: 500;">
            ✓ Publier l'article
        </button>
        <a href="<?php echo url('/articles'); ?>" style="padding: 10px 20px; color: #999; text-decoration: none; border: 1px solid #ddd; border-radius: 4px; display: inline-block;">
            Annuler
        </a>
    </div>
</form>

<?php fin_section('contenu'); ?>
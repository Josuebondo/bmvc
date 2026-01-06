<?php etendre('layouts.app'); ?>

<?php debut_section('contenu'); ?>

<?php
$erreurs = $erreurs ?? [];
$article = $article ?? [];
$id = $article['id'] ?? null;
?>

<h2>Supprimer l'article</h2>

<div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 20px; margin-bottom: 20px; border-radius: 4px; color: #856404;">
    <h3>⚠️ Attention!</h3>
    <p>Êtes-vous sûr de vouloir supprimer cet article? Cette action est <strong>irréversible</strong>.</p>

    <div style="background: white; padding: 15px; margin: 15px 0; border-radius: 4px; border-left: 4px solid #667eea;">
        <h4><?php echo htmlspecialchars($article['titre'] ?? ''); ?></h4>
        <p style="color: #666; margin: 10px 0;">
            <?php echo htmlspecialchars(substr($article['contenu'] ?? '', 0, 150)); ?>...
        </p>
    </div>
</div>

<?php if (!empty($erreurs)): ?>
    <div style="background: #fee; border: 1px solid #fcc; padding: 15px; margin-bottom: 20px; border-radius: 4px; color: #c33;">
        <h3>❌ Erreur</h3>
        <ul>
            <?php foreach ($erreurs as $champ => $messages): ?>
                <?php foreach ($messages as $message): ?>
                    <li><?php echo htmlspecialchars($message); ?></li>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" action="<?php echo url('/articles/' . $id . '/supprimer'); ?>" style="max-width: 600px;">
    <div style="display: flex; gap: 10px;">
        <button type="submit" style="background: #e74c3c; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: 500;">
            🗑️ Supprimer l'article
        </button>
        <a href="<?php echo url('/articles/' . $id); ?>" style="padding: 10px 20px; color: #667eea; text-decoration: none; border: 1px solid #667eea; border-radius: 4px; display: inline-block; font-weight: 500;">
            Annuler
        </a>
    </div>
</form>

<?php fin_section('contenu'); ?>
<?php etendre('layouts.app'); ?>

<?php debut_section('contenu'); ?>
<?php $articles = $articles ?? [];
$titre = $titre ?? 'Articles'; ?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h2 style="margin: 0;"><?php echo e($titre); ?></h2>
    <a href="<?php echo url('/articles/creer'); ?>" style="background: #667eea; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; display: inline-block; font-weight: 500;">
        ➕ Nouvel Article
    </a>
</div>

<div class="articles-list" style="margin-top: 20px;">
    <?php if (empty($articles)): ?>
        <p style="color: #999; text-align: center; padding: 40px;">
            Aucun article pour le moment. <a href="<?php echo url('/articles/creer'); ?>">Créez le premier!</a>
        </p>
    <?php else: ?>
        <?php foreach ($articles as $article): ?>
            <?php
            $id = is_object($article) ? $article->id : $article['id'];
            $titre_article = is_object($article) ? $article->titre : $article['titre'];
            $contenu = is_object($article) ? $article->contenu : $article['contenu'];
            ?>
            <div class="article-card" style="border: 1px solid #ddd; padding: 20px; margin: 15px 0; border-radius: 5px; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                    <h3 style="margin: 0; flex: 1;"><?php echo htmlspecialchars($titre_article); ?></h3>
                    <div style="display: flex; gap: 5px;">
                        <a href="<?php echo url('/articles/' . $id . '/editer'); ?>" style="background: #f39c12; color: white; padding: 5px 10px; border-radius: 3px; text-decoration: none; font-size: 12px; font-weight: 500;">✏️ Éditer</a>
                        <a href="<?php echo url('/articles/' . $id . '/supprimer'); ?>" style="background: #e74c3c; color: white; padding: 5px 10px; border-radius: 3px; text-decoration: none; font-size: 12px; font-weight: 500;">🗑️ Supprimer</a>
                    </div>
                </div>
                <p style="margin: 0 0 10px 0; color: #666;"><?php echo htmlspecialchars(substr($contenu, 0, 150)); ?>...</p>
                <a href="<?php echo url('/articles/' . $id); ?>" style="color: #667eea; text-decoration: none; font-weight: 500;">Lire l'article complet →</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php fin_section('contenu'); ?>
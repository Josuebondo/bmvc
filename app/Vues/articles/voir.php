<?php etendre('layouts.app'); ?>

<?php debut_section('contenu'); ?>
<?php $article = $article ?? []; ?>
<div style="margin-bottom: 20px;">
    <a href="<?php echo url('articles'); ?>" style="color: #667eea; text-decoration: none;">← Retour aux articles</a>
</div>

<article>
    <h2><?php echo e($article['titre'] ?? 'Article'); ?></h2>
    <div style="color: #999; font-size: 0.9em; margin-bottom: 20px;">
        Publié le: <?php echo e($article['date'] ?? date('d/m/Y')); ?>
    </div>
    <div style="line-height: 1.6;">
        <?php echo e($article['contenu']); ?>
    </div>
</article>
<?php fin_section('contenu'); ?>
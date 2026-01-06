<?php
// Utiliser le layout app
etendre('layouts.app');
?>

<?php debut_section('contenu'); ?>
<h2><?php echo $titre ?? 'Bienvenue sur BMVC'; ?></h2>
<p><?php echo $message ?? 'Framework PHP MVC prêt pour la production'; ?></p>

<ul style="margin: 20px 0; padding-left: 20px;">
    <li>Système de routing avancé</li>
    <li>Moteur de vues avec layouts</li>
    <li>Gestion des erreurs professionnelle</li>
    <li>Support Apache et CLI</li>
    <li>100% en français</li>
</ul>

<div style="background: #f0f4ff; padding: 20px; border-radius: 5px; margin-top: 20px;">
    <h3>Exemple avec paramètres</h3>
    <p>Les routes supportent les paramètres dynamiques.</p>
</div>

<?php fin_section(); ?>
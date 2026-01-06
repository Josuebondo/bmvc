<?php
// Utiliser le layout app
etendre('layouts.app');
?>

<?php debut_section('contenu'); ?>
<h2>Bienvenue sur BMVC</h2>
<p>Framework PHP français inspiré de Laravel avec :</p>

<ul style="margin: 20px 0; padding-left: 20px;">
    <li> Système de routing avancé</li>
    <li> Moteur de vues avec layouts</li>
    <li> Gestion des erreurs professionnelle</li>
    <li> Support Apache et CLI</li>
    <li> 100% en français</li>
</ul>

<div style="background: #f0f4ff; padding: 20px; border-radius: 5px; margin-top: 20px;">
    <h3> Exemple avec paramètres</h3>
    <p>Les routes supportent les paramètres dynamiques :</p>
    <code style="background: #fff; padding: 10px; display: block; border-radius: 3px;">
        /utilisateur/{id} /utilisateur/42
    </code>
</div>
<?php fin_section('contenu'); ?>

<?php debut_section('styles'); ?>
<style>
    h2 {
        color: #667eea;
        margin-bottom: 15px;
    }

    h3 {
        color: #764ba2;
        margin-top: 20px;
        margin-bottom: 10px;
    }

    ul li {
        margin-bottom: 8px;
    }

    code {
        color: #d63384;
        font-family: 'Courier New';
    }
</style>
<?php fin_section('styles'); ?>
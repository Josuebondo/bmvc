<?php

/**
 * ======================================================================
 * Routes Web - Framework BMVC Production
 * ======================================================================
 */

use Core\Routeur;

// Route Welcome
Routeur::obtenir('/', 'WelcomeControleur@index')->nom('welcome');
Routeur::obtenir('/welcome', 'WelcomeControleur@index')->nom('welcome');
// Produit
Routeur::obtenir('/produits', 'ProduitControleur@index')->nom('produit');
Routeur::obtenir('/produits/creer', 'ProduitControleur@creer')->nom('produit.creer');
Routeur::publier('/produits/creer', 'ProduitControleur@enregistrer')->nom('produit.envoyer');
Routeur::obtenir('/produits/{id}/editer', 'ProduitControleur@editer')->ou('id', '[0-9]+')->nom('produit.editer');
Routeur::publier('/produits/{id}/editer', 'ProduitControleur@mettreAJour')->ou('id', '[0-9]+')->nom('produit.mettre');
Routeur::obtenir('/produits/{id}/supprimer', 'ProduitControleur@supprimer')->ou('id', '[0-9]+')->nom('produit.supprimer');

<?php

/**
 * Intergiciel Auth
 * 
 * Middleware d'authentification
 * Vérifie que l'utilisateur est connecté
 * Redirige vers la page de connexion si non authentifié
 * 
 * Utilisation dans les routes :
 *   Routeur::obtenir('/dashboard', 'DashboardControleur@index')
 *       ->intergiciel('auth');
 * 
 * Ou dans un groupe :
 *   Routeur::groupe(['intergiciel' => 'auth'], function() {
 *       Routeur::obtenir('/admin', 'AdminControleur@index');
 *   });
 */

class Auth
{
    // À compléter avec la logique d'authentification
}

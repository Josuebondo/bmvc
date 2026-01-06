<?php

namespace Core\Middlewares;

use Core\Auth;
use Core\CSRF;
use Core\Requete;

/**
 * ======================================================================
 * Middleware - Protection des routes
 * ======================================================================
 */

class MiddlewareAuth
{
    /**
     * Vérifie que l'utilisateur est connecté
     */
    public static function verifier(Requete $requete): bool
    {
        if (!Auth::connecte()) {
            header('Location: /connexion');
            exit;
        }
        return true;
    }
}

class MiddlewareAdmin
{
    /**
     * Vérifie que l'utilisateur est admin
     */
    public static function verifier(Requete $requete): bool
    {
        if (!Auth::estAdmin()) {
            header('Location: /');
            exit;
        }
        return true;
    }
}

class MiddlewareCSRF
{
    /**
     * Vérifie le token CSRF pour les requêtes POST/PUT/DELETE
     */
    public static function verifier(Requete $requete): bool
    {
        $methode = $requete->methode();

        // Ne vérifier que pour POST, PUT, DELETE
        if (!in_array($methode, ['POST', 'PUT', 'DELETE'])) {
            return true;
        }

        $token = $_POST['_csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;

        if (!$token || !CSRF::verifier($token)) {
            http_response_code(403);
            die('Token CSRF invalide');
        }

        return true;
    }
}

class MiddlewareGuest
{
    /**
     * Redirige les utilisateurs connectés vers l'accueil
     */
    public static function verifier(Requete $requete): bool
    {
        if (Auth::connecte()) {
            header('Location: /');
            exit;
        }
        return true;
    }
}

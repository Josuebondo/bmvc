<?php

namespace Core\Storage;

/**
 * ======================================================================
 * StorageManager - Gestion des fichiers uploadés
 * ======================================================================
 * 
 * Gestionnaire de stockage de fichiers inspiré de Laravel,
 * adapté au framework BMVC.
 * 
 * Stocke les fichiers dans /storage/uploads/
 * Retourne les chemins logiques pour la base de données
 * Génère les URLs publiques pour l'affichage
 */
class StorageManager
{
    /**
     * Racine du répertoire de stockage
     */
    protected static string $root = '';

    /**
     * Obtenir la racine du répertoire de stockage
     */
    protected static function obtenerRacine(): string
    {
        if (empty(self::$root)) {
            self::$root = realpath(dirname(__DIR__) . '/storage/uploads') ?: dirname(__DIR__) . '/storage/uploads';
        }
        return self::$root;
    }

    /**
     * Stocker un fichier uploadé
     * 
     * @param string $dossier Dossier de destination (ex: 'menus', 'articles/images')
     * @param array $fichier Tableau $_FILES (ex: $_FILES['image'])
     * @param ?string $nom Nom personnalisé sans extension (optionnel)
     * @return string Chemin logique stockable en base (ex: 'menus/65a9f23a.jpg')
     * 
     * @throws \Exception Si le fichier est invalide
     */
    public static function placer(
        string $dossier,
        array $fichier,
        ?string $nom = null
    ): string {
        // Vérifier le fichier uploadé
        if (!isset($fichier['tmp_name']) || !is_uploaded_file($fichier['tmp_name'])) {
            throw new \Exception("Fichier invalide ou pas uploadé");
        }

        // Créer les dossiers si nécessaire
        self::creerDossier($dossier);

        // Récupérer l'extension
        $extension = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));

        // Générer le nom du fichier
        $nom = $nom
            ? nettoyerNomFichier($nom) . '.' . $extension
            : uniqid() . '_' . time() . '.' . $extension;

        // Chemin complet de destination
        $destination = self::obtenerRacine() . '/' . trim($dossier, '/') . '/' . $nom;

        // Déplacer le fichier
        if (!move_uploaded_file($fichier['tmp_name'], $destination)) {
            throw new \Exception("Erreur lors du déplacement du fichier");
        }

        // Retourner le chemin logique (sans /storage/uploads/)
        return trim($dossier, '/') . '/' . $nom;
    }

    /**
     * Obtenir l'URL publique d'un fichier
     * 
     * @param string $chemin Chemin logique depuis la base (ex: 'menus/65a9f23a.jpg')
     * @return string URL complète (ex: '/storage/menus/65a9f23a.jpg')
     */
    public static function url(string $chemin): string
    {
        return '/storage/' . ltrim($chemin, '/');
    }

    /**
     * Récupérer le chemin complet d'un fichier
     * 
     * @param string $chemin Chemin logique depuis la base
     * @return string Chemin complet du système de fichiers
     */
    public static function chemin(string $chemin): string
    {
        return self::obtenerRacine() . '/' . ltrim($chemin, '/');
    }

    /**
     * Vérifier si un fichier existe
     * 
     * @param string $chemin Chemin logique depuis la base
     * @return bool
     */
    public static function existe(string $chemin): bool
    {
        return file_exists(self::chemin($chemin));
    }

    /**
     * Supprimer un fichier
     * 
     * @param string $chemin Chemin logique depuis la base
     * @return bool
     */
    public static function supprimer(string $chemin): bool
    {
        $cheminComplet = self::chemin($chemin);
        if (file_exists($cheminComplet)) {
            return unlink($cheminComplet);
        }
        return false;
    }

    /**
     * Créer le dossier s'il n'existe pas
     */
    protected static function creerDossier(string $dossier): void
    {
        $cheminComplet = self::obtenerRacine() . '/' . trim($dossier, '/');

        if (!is_dir($cheminComplet)) {
            @mkdir($cheminComplet, 0755, true);
        }
    }
}

/**
 * Nettoyer un nom de fichier
 */
if (!function_exists('nettoyerNomFichier')) {
    function nettoyerNomFichier(string $nom): string
    {
        // Remplacer les espaces par des underscores
        $nom = str_replace(' ', '_', $nom);

        // Supprimer les caractères spéciaux
        $nom = preg_replace('/[^a-zA-Z0-9._-]/', '', $nom);

        return trim($nom, '._-');
    }
}

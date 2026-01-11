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
     * Initialiser la racine (appelée automatiquement)
     */
    protected static function getRoot(): string
    {
        if (empty(self::$root)) {
            self::$root = realpath(dirname(__DIR__) . '/storage/uploads') ?: dirname(__DIR__) . '/storage/uploads';
        }
        return self::$root;
    }

    /**
     * Stocker un fichier uploadé
     * 
     * @param string $directory Dossier de destination (ex: 'menus', 'articles/images')
     * @param array $file Tableau $_FILES (ex: $_FILES['image'])
     * @param ?string $filename Nom personnalisé sans extension (optionnel)
     * @return string Chemin logique stockable en base (ex: 'menus/65a9f23a.jpg')
     * 
     * @throws \Exception Si le fichier est invalide
     */
    public static function put(
        string $directory,
        array $file,
        ?string $filename = null
    ): string {
        // Vérifier le fichier uploadé
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            throw new \Exception("Fichier invalide ou pas uploadé");
        }

        // Créer les dossiers si nécessaire
        self::ensureDirectory($directory);

        // Récupérer l'extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        // Générer le nom du fichier
        $filename = $filename
            ? sanitizeFilename($filename) . '.' . $extension
            : uniqid() . '_' . time() . '.' . $extension;

        // Chemin complet de destination
        $destination = self::getRoot() . '/' . trim($directory, '/') . '/' . $filename;

        // Déplacer le fichier
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new \Exception("Erreur lors du déplacement du fichier");
        }

        // Retourner le chemin logique (sans /storage/uploads/)
        return trim($directory, '/') . '/' . $filename;
    }

    /**
     * Générer l'URL publique d'un fichier
     * 
     * @param string $path Chemin logique depuis la base (ex: 'menus/65a9f23a.jpg')
     * @return string URL complète (ex: '/storage/menus/65a9f23a.jpg')
     */
    public static function url(string $path): string
    {
        return '/storage/' . ltrim($path, '/');
    }

    /**
     * Récupérer le chemin complet d'un fichier
     * 
     * @param string $path Chemin logique depuis la base
     * @return string Chemin complet du système de fichiers
     */
    public static function path(string $path): string
    {
        return self::getRoot() . '/' . ltrim($path, '/');
    }

    /**
     * Vérifier si un fichier existe
     * 
     * @param string $path Chemin logique depuis la base
     * @return bool
     */
    public static function exists(string $path): bool
    {
        return file_exists(self::path($path));
    }

    /**
     * Supprimer un fichier
     * 
     * @param string $path Chemin logique depuis la base
     * @return bool
     */
    public static function delete(string $path): bool
    {
        $fullPath = self::path($path);
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }
        return false;
    }

    /**
     * Créer le dossier s'il n'existe pas
     */
    protected static function ensureDirectory(string $directory): void
    {
        $fullPath = self::getRoot() . '/' . trim($directory, '/');

        if (!is_dir($fullPath)) {
            @mkdir($fullPath, 0755, true);
        }
    }
}

/**
 * Nettoyer un nom de fichier
 */
if (!function_exists('sanitizeFilename')) {
    function sanitizeFilename(string $filename): string
    {
        // Remplacer les espaces par des underscores
        $filename = str_replace(' ', '_', $filename);

        // Supprimer les caractères spéciaux
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '', $filename);

        return trim($filename, '._-');
    }
}

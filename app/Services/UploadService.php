<?php

namespace App\Services;

/**
 * Service d'Upload de Fichiers
 * Gère les uploads sécurisés
 */
class UploadService
{
    /** @var string */
    private $repertoireUpload;
    /** @var array */
    private $extensionsAutorisees;
    /** @var int */
    private $tailleMaxMo;
    /** @var string|null */
    private $derniereErreur = null;

    public function __construct()
    {
        // Charger les configurations depuis .env
        $uploadDir = env('REPERTOIRE_UPLOAD', 'public/uploads/');

        // Convertir en chemin absolu si ce n'est pas déjà un chemin absolu
        if (!is_absolute_path($uploadDir)) {
            // Utiliser le chemin racine de l'application
            $uploadDir = dirname(dirname(dirname(__FILE__))) . '/' . ltrim($uploadDir, '/');
        }

        $this->repertoireUpload = rtrim($uploadDir, '/') . '/';
        $this->tailleMaxMo = (int) env('TAILLE_MAX_UPLOAD', 5);

        $extensionsStr = env('EXTENSIONS_AUTORISEES', 'jpg,jpeg,png,gif,pdf');
        $this->extensionsAutorisees = array_map('trim', explode(',', $extensionsStr));
    }

    /**
     * Obtient le répertoire d'upload
     */
    public function getRepertoire()
    {
        return $this->repertoireUpload;
    }

    /**
     * Définit le répertoire d'upload
     */
    public function setRepertoire($repertoire)
    {
        $this->repertoireUpload = $repertoire;
        return $this;
    }

    /**
     * Définit les extensions autorisées
     */
    public function setExtensionsAutorisees($extensions)
    {
        $this->extensionsAutorisees = $extensions;
        return $this;
    }

    /**
     * Définit la taille max en Mo
     */
    public function setTailleMax($mo)
    {
        $this->tailleMaxMo = $mo;
        return $this;
    }

    /**
     * Upload un fichier
     */
    public function uploader($fichier)
    {
        $this->derniereErreur = null;
        // Validation
        if (!$this->validerFichier($fichier)) {
            return null;
        }

        // Crée le dossier s'il n'existe pas
        if (!is_dir($this->repertoireUpload)) {
            mkdir($this->repertoireUpload, 0755, true);
        }

        // Génère un nom unique
        $extension = pathinfo($fichier['name'], PATHINFO_EXTENSION);
        $nomFichier = uniqid('upload_') . '.' . $extension;
        $cheminComplet = $this->repertoireUpload . $nomFichier;

        // Déplace le fichier
        if (move_uploaded_file($fichier['tmp_name'], $cheminComplet)) {
            return $nomFichier;
        }

        // Fallback: si move_uploaded_file échoue, essayer copy (pour le testing)
        if (copy($fichier['tmp_name'], $cheminComplet)) {
            return $nomFichier;
        }

        $this->derniereErreur = 'Impossible de déplacer le fichier vers ' . $cheminComplet;
        return null;
    }

    /**
     * Valide un fichier
     */
    private function validerFichier($fichier)
    {
        // Vérifier les erreurs d'upload
        if ($fichier['error'] !== UPLOAD_ERR_OK) {
            $this->derniereErreur = $this->traduireErreurUpload($fichier['error']);
            return false;
        }

        // Vérifier la taille
        if ($fichier['size'] > ($this->tailleMaxMo * 1024 * 1024)) {
            $this->derniereErreur = 'Fichier trop volumineux (max ' . $this->tailleMaxMo . ' Mo)';
            return false;
        }

        // Vérifier l'extension
        $extension = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $this->extensionsAutorisees)) {
            $this->derniereErreur = 'Extension non autorisée: ' . $extension;
            return false;
        }

        return true;
    }

    /**
     * Retourne la dernière erreur rencontrée
     */
    public function getDerniereErreur()
    {
        return $this->derniereErreur;
    }

    /**
     * Traduit le code d'erreur upload en message lisible
     */
    private function traduireErreurUpload($code)
    {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return 'Fichier trop volumineux';
            case UPLOAD_ERR_PARTIAL:
                return 'Upload partiel du fichier';
            case UPLOAD_ERR_NO_FILE:
                return 'Aucun fichier fourni';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Dossier temporaire manquant';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Impossible d\'écrire le fichier sur le disque';
            case UPLOAD_ERR_EXTENSION:
                return 'Upload bloqué par une extension PHP';
            default:
                return 'Erreur inconnue lors de l\'upload (code ' . $code . ')';
        }
    }

    /**
     * Supprime un fichier
     */
    public function supprimer($nomFichier)
    {
        $chemin = $this->repertoireUpload . $nomFichier;

        if (file_exists($chemin)) {
            return unlink($chemin);
        }

        return false;
    }
}

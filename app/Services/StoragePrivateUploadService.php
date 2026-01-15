<?php

namespace App\Services;

/**
 * Service d'upload vers storage/uploads uniquement (aucune copie publique).
 * À utiliser avec UPLOAD_DRIVER=storage_private.
 */
class StoragePrivateUploadService
{
    /** @var string */
    private $repertoireStockage;
    /** @var array */
    private $extensionsAutorisees;
    /** @var int */
    private $tailleMaxMo;
    /** @var string|null */
    private $derniereErreur = null;

    public function __construct()
    {
        $storageDir = env('REPERTOIRE_UPLOAD_STOCKAGE', 'storage/uploads/');

        if (!is_absolute_path($storageDir)) {
            $storageDir = dirname(dirname(dirname(__FILE__))) . '/' . ltrim($storageDir, '/');
        }

        $this->repertoireStockage = rtrim($storageDir, '/') . '/';
        $this->tailleMaxMo = (int) env('TAILLE_MAX_UPLOAD', 5);

        $extensionsStr = env('EXTENSIONS_AUTORISEES', 'jpg,jpeg,png,gif,pdf');
        $this->extensionsAutorisees = array_map('trim', explode(',', $extensionsStr));
    }

    /**
     * Upload dans storage uniquement.
     * @return array|null ['nom'=>..., 'stockage_absolu'=>..., 'stockage_relatif'=>..., 'url'=>...]
     */
    public function uploader($fichier)
    {
        $this->derniereErreur = null;

        if (!$this->validerFichier($fichier)) {
            return null;
        }

        if (!is_dir($this->repertoireStockage)) {
            mkdir($this->repertoireStockage, 0755, true);
        }

        $extension = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));
        $nomFichier = uniqid('upload_') . '.' . $extension;
        $cheminStockage = $this->repertoireStockage . $nomFichier;

        if (!move_uploaded_file($fichier['tmp_name'], $cheminStockage)) {
            if (!copy($fichier['tmp_name'], $cheminStockage)) {
                $this->derniereErreur = 'Impossible de déplacer le fichier vers ' . $cheminStockage;
                return null;
            }
        }

        $stockageRelatif = 'uploads/' . $nomFichier; // pour référence interne
        // URL via contrôleur dédié
        $url = url('fichiers/' . $nomFichier);

        return [
            'nom' => $nomFichier,
            'stockage_absolu' => $cheminStockage,
            'stockage_relatif' => $stockageRelatif,
            'url' => $url,
        ];
    }

    private function validerFichier($fichier)
    {
        if ($fichier['error'] !== UPLOAD_ERR_OK) {
            $this->derniereErreur = $this->traduireErreurUpload($fichier['error']);
            return false;
        }

        if ($fichier['size'] > ($this->tailleMaxMo * 1024 * 1024)) {
            $this->derniereErreur = 'Fichier trop volumineux (max ' . $this->tailleMaxMo . ' Mo)';
            return false;
        }

        $extension = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $this->extensionsAutorisees)) {
            $this->derniereErreur = 'Extension non autorisée: ' . $extension;
            return false;
        }

        return true;
    }

    public function getDerniereErreur()
    {
        return $this->derniereErreur;
    }

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
}

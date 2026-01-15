<?php

namespace App\Services;

/**
 * Service d'upload vers storage/uploads avec copie vers public/uploads pour l'affichage.
 * Conserve UploadService existant, ajoute un driver alternatif activable via UPLOAD_DRIVER=storage.
 */
class StorageUploadService
{
    /** @var string */
    private $repertoireStockage;
    /** @var string */
    private $repertoirePublic;
    /** @var array */
    private $extensionsAutorisees;
    /** @var int */
    private $tailleMaxMo;
    /** @var string|null */
    private $derniereErreur = null;

    public function __construct()
    {
        // Dossiers par défaut
        $storageDir = env('REPERTOIRE_UPLOAD_STOCKAGE', 'storage/uploads/');
        $publicDir = env('REPERTOIRE_UPLOAD_PUBLIC', 'public/uploads/');

        // Chemins absolus
        if (!is_absolute_path($storageDir)) {
            $storageDir = dirname(dirname(dirname(__FILE__))) . '/' . ltrim($storageDir, '/');
        }
        if (!is_absolute_path($publicDir)) {
            $publicDir = dirname(dirname(dirname(__FILE__))) . '/' . ltrim($publicDir, '/');
        }

        $this->repertoireStockage = rtrim($storageDir, '/') . '/';
        $this->repertoirePublic = rtrim($publicDir, '/') . '/';
        $this->tailleMaxMo = (int) env('TAILLE_MAX_UPLOAD', 5);

        $extensionsStr = env('EXTENSIONS_AUTORISEES', 'jpg,jpeg,png,gif,pdf');
        $this->extensionsAutorisees = array_map('trim', explode(',', $extensionsStr));
    }

    /**
     * Upload avec sauvegarde en storage et copie en public pour l'affichage.
     * @return array|null ['nom'=>..., 'stockage_absolu'=>..., 'public_absolu'=>..., 'public_relatif'=>..., 'url'=>...]
     */
    public function uploader(array $fichier)
    {
        $this->derniereErreur = null;

        if (!$this->validerFichier($fichier)) {
            return null;
        }

        // Dossiers
        if (!is_dir($this->repertoireStockage)) {
            mkdir($this->repertoireStockage, 0755, true);
        }
        if (!is_dir($this->repertoirePublic)) {
            mkdir($this->repertoirePublic, 0755, true);
        }

        $extension = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));
        $nomFichier = uniqid('upload_') . '.' . $extension;
        $cheminStockage = $this->repertoireStockage . $nomFichier;
        $cheminPublic = $this->repertoirePublic . $nomFichier;

        // Déplacer dans storage
        if (!move_uploaded_file($fichier['tmp_name'], $cheminStockage)) {
            if (!copy($fichier['tmp_name'], $cheminStockage)) {
                $this->derniereErreur = 'Impossible de déplacer le fichier vers ' . $cheminStockage;
                return null;
            }
        }

        // Copier vers public pour l'accès HTTP
        if (!copy($cheminStockage, $cheminPublic)) {
            $this->derniereErreur = 'Fichier stocké mais non copié vers le dossier public';
            return null;
        }

        $publicRelatif = 'uploads/' . $nomFichier;
        $url = url($publicRelatif);

        return [
            'nom' => $nomFichier,
            'stockage_absolu' => $cheminStockage,
            'public_absolu' => $cheminPublic,
            'public_relatif' => $publicRelatif,
            'url' => $url,
        ];
    }

    private function validerFichier(array $fichier)
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

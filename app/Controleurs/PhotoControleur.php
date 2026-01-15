<?php

namespace App\Controleurs;

use App\BaseControleur;
use App\Modeles\Photo;
use App\Services\UploadService;
use App\Services\StorageUploadService;
use Core\Validateur;
use Core\Requete;

/**
 * Contrôleur pour la gestion des photos
 */
class PhotoControleur extends BaseControleur
{
    /**
     * Liste toutes les photos
     */
    public function index()
    {
        try {
            $photos = Photo::tout();
            // Convertir en tableaux et injecter le chemin public si absent
            $photos = array_map(function ($p) {
                $row = (is_object($p) && method_exists($p, 'toArray')) ? $p->toArray() : $p;
                if (empty($row['chemin_complet']) && !empty($row['fichier'])) {
                    $row['chemin_complet'] = '/uploads/' . $row['fichier'];
                }
                return $row;
            }, $photos);

            $this->afficher('photos/index', ['photos' => $photos]);
        } catch (\Exception $e) {
            $this->json(['erreur' => 'Erreur lors de la récupération des photos'], 500);
        }
    }

    /**
     * Affiche la page de création
     */
    public function creer()
    {
        $this->afficher('photos/creer');
        // return vue('photos.creer');
    }

    /**
     * Traite l'upload et sauvegarde la photo
     */
    public function sauvegarder()
    {
        try {
            // Récupérer les données
            $requete = $this->requete();
            $donnees = [
                'titre' => $requete->input('titre'),
                'description' => $requete->input('description')
            ];
            $fichier = $requete->fichier('photo');

            // Valider que le fichier existe
            if (!$fichier || $fichier['error'] !== UPLOAD_ERR_OK) {
                return $this->json(['erreur' => 'Aucun fichier uploadé ou erreur lors de l\'upload'], 400);
            }

            // Valider les données
            $validateur = new Validateur();
            $donnees = $validateur->valider($donnees, [
                'titre' => 'required|min:3|max:255',
                'description' => 'nullable|max:1000'
            ]);

            // Choix du driver : public (historique) ou storage (nouveau)
            $driver = env('UPLOAD_DRIVER', 'public');

            if ($driver === 'storage') {
                $uploadService = new StorageUploadService();
                $resultatUpload = $uploadService->uploader($fichier);
                if (!$resultatUpload) {
                    $raison = $uploadService->getDerniereErreur();
                    $message = 'Erreur lors de l\'upload du fichier' . ($raison ? ' : ' . $raison : '');
                    return $this->json(['erreur' => $message], 400);
                }
                $nomFichier = $resultatUpload['nom'];
                $cheminRelatif = 'uploads/' . $nomFichier;
                $urlComplete = '/uploads/' . $nomFichier;
            } else {
                // Comportement historique (public/uploads)
                $uploadService = new UploadService();
                $nomFichier = $uploadService->uploader($fichier);

                if (!$nomFichier) {
                    $raison = $uploadService->getDerniereErreur();
                    $message = 'Erreur lors de l\'upload du fichier' . ($raison ? ' : ' . $raison : '');
                    return $this->json(['erreur' => $message], 400);
                }

                $cheminRelatif = 'uploads/' . $nomFichier;
                $urlComplete = '/uploads/' . $nomFichier;
            }

            // Sauvegarde en base de données
            $photo = Photo::creer([
                'titre' => $donnees['titre'],
                'description' => isset($donnees['description']) ? $donnees['description'] : null,
                'fichier' => $nomFichier,
                'chemin_relatif' => $cheminRelatif,
                'chemin_complet' => $urlComplete,
                'date_creation' => date('Y-m-d H:i:s')
            ]);

            return $this->json([
                'success' => true,
                'message' => 'Photo uploadée avec succès',
                'photo' => $photo->toArray(),
                'redirect' => url('photos')
            ], 201);
        } catch (\Exception $e) {
            return $this->json(['erreur' => $e->getMessage()], 400);
        }
    }

    /**
     * Affiche une photo spécifique
     */
    public function voir()
    {
        try {
            $id = $this->requete()->param('id');
            $photo = Photo::trouver($id);

            if (!$photo) {
                return $this->json(['erreur' => 'Photo non trouvée'], 404);
            }

            if (is_object($photo) && method_exists($photo, 'toArray')) {
                $photo = $photo->toArray();
            }
            if (empty($photo['chemin_complet']) && !empty($photo['fichier'])) {
                $photo['chemin_complet'] = '/uploads/' . $photo['fichier'];
            }

            $this->afficher('photos/voir', ['photo' => $photo]);
        } catch (\Exception $e) {
            return $this->json(['erreur' => $e->getMessage()], 500);
        }
    }

    /**
     * Supprime une photo
     */
    public function supprimer()
    {
        try {
            $id = $this->requete()->param('id');
            $photo = Photo::trouver($id);

            if (!$photo) {
                return $this->json(['erreur' => 'Photo non trouvée'], 404);
            }

            // Supprimer le fichier
            $uploadService = new UploadService();
            $uploadService->supprimer($photo['fichier']);

            // Supprimer de la base de données
            $photo->supprimer();

            return $this->json([
                'success' => true,
                'message' => 'Photo supprimée avec succès',
                'redirect' => url('photos')
            ], 200);
        } catch (\Exception $e) {
            return $this->json(['erreur' => $e->getMessage()], 500);
        }
    }

    /**
     * Sert un fichier stocké dans storage/uploads (driver storage_private)
     */
    public function fichier()
    {
        $nom = basename($this->requete()->param('nom'));
        $root = realpath(__DIR__ . '/../../'); // vers racine du projet

        // Répertoire de stockage privé
        $storageDir = env('REPERTOIRE_UPLOAD_STOCKAGE', 'storage/uploads/');
        if (!is_absolute_path($storageDir)) {
            $storageDir = $root . '/' . ltrim($storageDir, '/');
        }

        $chemin = rtrim($storageDir, '/') . '/' . $nom;

        // Fallback sur le dossier public si besoin
        if (!file_exists($chemin) || !is_file($chemin)) {
            $publicDir = env('REPERTOIRE_UPLOAD_PUBLIC', 'public/uploads/');
            if (!is_absolute_path($publicDir)) {
                $publicDir = $root . '/' . ltrim($publicDir, '/');
            }
            $chemin = rtrim($publicDir, '/') . '/' . $nom;
        }

        if (!file_exists($chemin) || !is_file($chemin)) {
            return $this->json(['erreur' => 'Fichier introuvable'], 404);
        }

        $mime = function_exists('mime_content_type') ? mime_content_type($chemin) : 'application/octet-stream';
        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($chemin));
        header('Content-Disposition: inline; filename="' . $nom . '"');
        readfile($chemin);
        exit;
    }
}

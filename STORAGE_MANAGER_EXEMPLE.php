<?php

/**
 * ======================================================================
 * EXEMPLE D'UTILISATION - StorageManager BMVC
 * ======================================================================
 */

// DANS UN CONTRÔLEUR
// ==================

use App\Core\Storage\StorageManager;

class MenuControleur extends BaseControleur
{
    /**
     * Créer un menu avec image
     */
    public function creer()
    {
        if ($this->requete->methode() === 'POST') {
            try {
                // Valider les données
                $donnees = Validateur::valider($this->requete->tous(), [
                    'titre' => 'required|string|max:100',
                    'description' => 'required|string',
                    'image' => 'file|mimes:jpg,jpeg,png,webp'
                ]);

                // Si une image est uploadée
                $cheminImage = null;
                if (!empty($_FILES['image']) && $_FILES['image']['size'] > 0) {
                    // Stocker l'image et récupérer le chemin logique
                    $cheminImage = StorageManager::put('menus', $_FILES['image']);
                }

                // Créer le menu en base de données
                $menu = Menu::creer([
                    'titre' => $donnees['titre'],
                    'description' => $donnees['description'],
                    'image' => $cheminImage, // Stocker le chemin logique
                ]);

                return redirection('/menus/' . $menu->id, 'Menu créé avec succès');
            } catch (\Exception $e) {
                return $this->rendre('menus/creer', [
                    'erreur' => $e->getMessage()
                ]);
            }
        }

        return $this->rendre('menus/creer');
    }

    /**
     * Afficher un menu
     */
    public function afficher()
    {
        $menu = Menu::trouver(request('id'));

        if (!$menu) {
            return $this->erreur(404, 'Menu non trouvé');
        }

        return $this->rendre('menus/afficher', ['menu' => $menu]);
    }

    /**
     * Mettre à jour un menu
     */
    public function mettre()
    {
        $menu = Menu::trouver(request('id'));

        if (!$menu) {
            return $this->erreur(404);
        }

        if ($this->requete->methode() === 'POST') {
            try {
                $donnees = Validateur::valider($this->requete->tous(), [
                    'titre' => 'required|string|max:100',
                    'description' => 'required|string',
                ]);

                // Si nouvelle image
                if (!empty($_FILES['image']) && $_FILES['image']['size'] > 0) {
                    // Supprimer l'ancienne image
                    if ($menu->image) {
                        StorageManager::delete($menu->image);
                    }

                    // Stocker la nouvelle
                    $menu->image = StorageManager::put('menus', $_FILES['image']);
                }

                // Mettre à jour
                $menu->titre = $donnees['titre'];
                $menu->description = $donnees['description'];
                $menu->sauvegarder();

                return redirection('/menus/' . $menu->id, 'Menu mis à jour');
            } catch (\Exception $e) {
                return $this->rendre('menus/mettre', [
                    'menu' => $menu,
                    'erreur' => $e->getMessage()
                ]);
            }
        }

        return $this->rendre('menus/mettre', ['menu' => $menu]);
    }

    /**
     * Supprimer un menu
     */
    public function supprimer()
    {
        $menu = Menu::trouver(request('id'));

        if (!$menu) {
            return $this->erreur(404);
        }

        // Supprimer l'image du disque
        if ($menu->image && StorageManager::exists($menu->image)) {
            StorageManager::delete($menu->image);
        }

        // Supprimer en base
        $menu->supprimer();

        return redirection('/menus', 'Menu supprimé');
    }
}


// DANS LA VUE (afficher.php)
// ===========================

?>
<div class="menu">
    <h1><?= echapper($menu->titre) ?></h1>

    <?php if ($menu->image): ?>
        <img src="<?= \App\Core\Storage\StorageManager::url($menu->image) ?>" 
             alt="<?= echapper($menu->titre) ?>" 
             class="menu-image">
    <?php endif; ?>

    <p><?= echapper($menu->description) ?></p>

    <a href="/menus/<?= $menu->id ?>/modifier">Modifier</a>
    <form method="POST" action="/menus/<?= $menu->id ?>/supprimer">
        <button type="submit">Supprimer</button>
        <input type="hidden" name="_token" value="<?= token_csrf() ?>">
    </form>
</div>


// DANS LE FORMULAIRE (creer.php / mettre.php)
// ============================================

?>
<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="_token" value="<?= token_csrf() ?>">

    <div class="form-group">
        <label for="titre">Titre</label>
        <input type="text" id="titre" name="titre" required>
    </div>

    <div class="form-group">
        <label for="description">Description</label>
        <textarea id="description" name="description" required></textarea>
    </div>

    <div class="form-group">
        <label for="image">Image</label>
        <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp">
        <small>JPG, PNG ou WebP (max 5MB)</small>
    </div>

    <button type="submit">Enregistrer</button>
</form>


// HELPER GLOBAL (À AJOUTER DANS Helpers.php)
// ==========================================

if (!function_exists('fichier_storage')) {
    /**
     * Raccourci pour StorageManager::url()
     */
    function fichier_storage(string $path): string
    {
        return \App\Core\Storage\StorageManager::url($path);
    }
}

// Utilisation simplifiée en vue :
?>
<img src="<?= fichier_storage($menu->image) ?>" alt="Menu">


// API COMPLÈTE DU StorageManager
// ==============================

// Stocker un fichier
$chemin = StorageManager::put('menus', $_FILES['image']);
// Retour : "menus/65a9f23a_1234567890.jpg"

// Obtenir l'URL publique
$url = StorageManager::url($chemin);
// Retour : "/storage/menus/65a9f23a_1234567890.jpg"

// Obtenir le chemin complet du disque
$fullPath = StorageManager::path($chemin);
// Retour : "/xampp/htdocs/BMVC/storage/uploads/menus/65a9f23a_1234567890.jpg"

// Vérifier si existe
if (StorageManager::exists($chemin)) {
    // ...
}

// Supprimer
StorageManager::delete($chemin);


// CONFIGURATION NÉCESSAIRE
// =======================

/*
 * 1. Créer le symlink /public/storage → /storage/uploads
 * 
 *    Windows (CMD admin):
 *    mklink /D C:\xampp\htdocs\BMVC\public\storage C:\xampp\htdocs\BMVC\storage\uploads
 * 
 *    Linux/Mac:
 *    ln -s ../storage/uploads public/storage
 * 
 * 2. Ajouter l'helper à Helpers.php (optionnel)
 * 
 * 3. S'assurer que /storage/uploads existe et est writable
 */

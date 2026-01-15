<?php

/**
 * ======================================================================
 * Exemples Pratiques - Upload de Photos BMVC
 * ======================================================================
 */

echo "<!-- EXEMPLE 1: Formulaire d'Upload Basique -->\n";
echo "<form action=\"" . url('photos/sauvegarder') . "\" method=\"POST\" enctype=\"multipart/form-data\">\n";
echo "    " . csrf_input() . "\n";
echo "    <input type=\"text\" name=\"titre\" placeholder=\"Titre\" required>\n";
echo "    <input type=\"file\" name=\"photo\" accept=\"image/*\" required>\n";
echo "    <button type=\"submit\">Uploader</button>\n";
echo "</form>\n\n";

// ============================================================================
// EXEMPLE 2: Uploader avec Validations
// ============================================================================

echo "<!-- EXEMPLE 2: Contrôleur avec Validations -->\n";
echo "<?php\n";
echo "class PhotoControleur extends BaseControleur {\n";
echo "    public function sauvegarder() {\n";
echo "        try {\n";
echo "            // Valider\n";
echo "            \$donnees = Validateur::valider(request()->tous(), [\n";
echo "                'titre' => 'required|min:3|max:255',\n";
echo "                'photo' => 'required|file'\n";
echo "            ]);\n";
echo "\n";
echo "            // Uploader\n";
echo "            \$uploadService = new UploadService();\n";
echo "            \$nomFichier = \$uploadService->uploader(request()->fichier('photo'));\n";
echo "\n";
echo "            if (!\$nomFichier) {\n";
echo "                return \$this->json(['erreur' => 'Upload échoué'], 400);\n";
echo "            }\n";
echo "\n";
echo "            // Sauvegarder en BDD\n";
echo "            \$photo = Photo::creer([\n";
echo "                'titre' => \$donnees['titre'],\n";
echo "                'fichier' => \$nomFichier,\n";
echo "                'chemin_complet' => url('uploads/' . \$nomFichier)\n";
echo "            ]);\n";
echo "\n";
echo "            return \$this->json(['success' => true, 'photo' => \$photo], 201);\n";
echo "        } catch (Exception \$e) {\n";
echo "            return \$this->json(['erreur' => \$e->getMessage()], 400);\n";
echo "        }\n";
echo "    }\n";
echo "}\n";
echo "?>\n\n";

// ============================================================================
// EXEMPLE 3: Afficher les Photos
// ============================================================================

echo "<!-- EXEMPLE 3: Afficher les Photos en Vue -->\n";
echo "@extends('layouts.app')\n";
echo "@section('contenu')\n";
echo "\n";
echo "<div class=\"gallery\">\n";
echo "    <?php foreach (\$photos as \$photo): ?>\n";
echo "        <div class=\"photo-card\">\n";
echo "            <img src=\"<?= echapper(\$photo['chemin_complet']) ?>\"\n";
echo "                 alt=\"<?= echapper(\$photo['titre']) ?>\">\n";
echo "            <h3><?= echapper(\$photo['titre']) ?></h3>\n";
echo "            <p><?= echapper(\$photo['description']) ?></p>\n";
echo "            <a href=\"<?= url('photos/voir/' . \$photo['id']) ?>\">Voir</a>\n";
echo "        </div>\n";
echo "    <?php endforeach; ?>\n";
echo "</div>\n";
echo "\n";
echo "@endsection\n\n";

// ============================================================================
// EXEMPLE 4: Supprimer une Photo
// ============================================================================

echo "<!-- EXEMPLE 4: Supprimer une Photo -->\n";
echo "<?php\n";
echo "public function supprimer() {\n";
echo "    \$id = request()->param('id');\n";
echo "    \$photo = Photo::trouver(\$id);\n";
echo "\n";
echo "    // Supprimer le fichier\n";
echo "    \$uploadService = new UploadService();\n";
echo "    \$uploadService->supprimer(\$photo['fichier']);\n";
echo "\n";
echo "    // Supprimer en BDD\n";
echo "    Photo::supprimer(\$id);\n";
echo "\n";
echo "    return \$this->json(['success' => true, 'message' => 'Photo supprimée']);\n";
echo "}\n";
echo "?>\n\n";

// ============================================================================
// EXEMPLE 5: Configuration Personnalisée
// ============================================================================

echo "<!-- EXEMPLE 5: Configuration Personnalisée -->\n";
echo "<?php\n";
echo "\$uploadService = new UploadService();\n";
echo "\n";
echo "// Changer le dossier d'upload\n";
echo "\$uploadService->setRepertoire('stockage/photos/');\n";
echo "\n";
echo "// Autoriser que PNG et JPG\n";
echo "\$uploadService->setExtensionsAutorisees(['png', 'jpg', 'jpeg']);\n";
echo "\n";
echo "// Limite à 10 MB\n";
echo "\$uploadService->setTailleMax(10);\n";
echo "\n";
echo "\$nomFichier = \$uploadService->uploader(request()->fichier('photo'));\n";
echo "?>\n\n";

// ============================================================================
// EXEMPLE 6: Upload Multiple (AJAX)
// ============================================================================

echo "<!-- EXEMPLE 6: Upload Multiple avec AJAX -->\n";
echo "<form id=\"uploadForm\" enctype=\"multipart/form-data\">\n";
echo "    <input type=\"file\" name=\"photos\" multiple accept=\"image/*\">\n";
echo "    <button type=\"submit\">Uploader</button>\n";
echo "</form>\n";
echo "\n";
echo "<script>\n";
echo "document.getElementById('uploadForm').addEventListener('submit', async (e) => {\n";
echo "    e.preventDefault();\n";
echo "    const formData = new FormData(this);\n";
echo "    const files = document.querySelector('input[name=\"photos\"]').files;\n";
echo "\n";
echo "    for (let file of files) {\n";
echo "        const form = new FormData();\n";
echo "        form.append('titre', file.name);\n";
echo "        form.append('photo', file);\n";
echo "        form.append('_token', '<?= token_csrf() ?>');\n";
echo "\n";
echo "        const res = await fetch('<?= url('photos/sauvegarder') ?>', {\n";
echo "            method: 'POST',\n";
echo "            body: form\n";
echo "        });\n";
echo "\n";
echo "        if (res.ok) {\n";
echo "            console.log('Photo uploadée: ', file.name);\n";
echo "        }\n";
echo "    }\n";
echo "});\n";
echo "</script>\n\n";

// ============================================================================
// EXEMPLE 7: Tests Unitaires
// ============================================================================

echo "<!-- EXEMPLE 7: Tests Unitaires -->\n";
echo "<?php\n";
echo "public function testUploadReussi() {\n";
echo "    \$uploadService = new UploadService();\n";
echo "\n";
echo "    // Créer un fichier de test\n";
echo "    \$fichierTest = [\n";
echo "        'name' => 'test.jpg',\n";
echo "        'tmp_name' => tempnam(sys_get_temp_dir(), 'test'),\n";
echo "        'size' => 1024,\n";
echo "        'error' => UPLOAD_ERR_OK,\n";
echo "        'type' => 'image/jpeg'\n";
echo "    ];\n";
echo "\n";
echo "    // Tester l'upload\n";
echo "    \$nomFichier = \$uploadService->uploader(\$fichierTest);\n";
echo "\n";
echo "    \$this->assertIsString(\$nomFichier);\n";
echo "    \$this->assertFileExists(\$uploadDir . \$nomFichier);\n";
echo "}\n";
echo "?>\n\n";

// ============================================================================
// EXEMPLE 8: Redimensionner une Image
// ============================================================================

echo "<!-- EXEMPLE 8: Redimensionner une Image (Extension Possible) -->\n";
echo "<?php\n";
echo "class ImageService {\n";
echo "    public function redimensionner(\$source, \$largeur, \$hauteur) {\n";
echo "        \$image = imagecreatefromjpeg(\$source);\n";
echo "        \$miniature = imagecreatetruecolor(\$largeur, \$hauteur);\n";
echo "\n";
echo "        \$largeurSource = imagesx(\$image);\n";
echo "        \$hauteurSource = imagesy(\$image);\n";
echo "\n";
echo "        imagecopyresampled(\n";
echo "            \$miniature, \$image,\n";
echo "            0, 0, 0, 0,\n";
echo "            \$largeur, \$hauteur,\n";
echo "            \$largeurSource, \$hauteurSource\n";
echo "        );\n";
echo "\n";
echo "        imagejpeg(\$miniature, \$source, 90);\n";
echo "        imagedestroy(\$image);\n";
echo "        imagedestroy(\$miniature);\n";
echo "    }\n";
echo "}\n";
echo "?>\n\n";

// ============================================================================
// RÉSUMÉ DES COMMANDES
// ============================================================================

echo "<!-- COMMANDES UTILES -->\n";
echo "// Créer la table\n";
echo "php core/Migration.php up\n";
echo "\n";
echo "// Exécuter les tests\n";
echo "vendor/bin/phpunit tests/Unit/Services/UploadServiceTest.php\n";
echo "\n";
echo "// Démarrer le serveur\n";
echo "php -S localhost:8000 -t public/\n";
echo "\n";
echo "// Permissions du dossier\n";
echo "chmod 755 public/uploads/\n";
echo "\n";

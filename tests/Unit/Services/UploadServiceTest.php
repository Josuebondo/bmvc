<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use App\Services\UploadService;

/**
 * Tests pour le Service d'Upload
 * Teste la validation, l'upload et la suppression de fichiers
 */
class UploadServiceTest extends TestCase
{
    /** @var UploadService */
    private $uploadService;
    /** @var string */
    private $repertoireTest;

    protected function setUp(): void
    {
        $this->uploadService = new UploadService();

        // Utiliser un dossier de test temporaire
        $this->repertoireTest = __DIR__ . '/../../temp/uploads/';
        $this->uploadService->setRepertoire($this->repertoireTest);

        // Créer le dossier s'il n'existe pas
        if (!is_dir($this->repertoireTest)) {
            mkdir($this->repertoireTest, 0755, true);
        }
    }

    protected function tearDown(): void
    {
        // Nettoyer les fichiers de test après chaque test
        if (is_dir($this->repertoireTest)) {
            $fichiers = glob($this->repertoireTest . '*');
            foreach ($fichiers as $fichier) {
                if (is_file($fichier)) {
                    unlink($fichier);
                }
            }
            rmdir($this->repertoireTest);
        }
    }

    /**
     * Test 1: Créer un fichier temporaire de test
     */
    public function testCreerFichierTemporaire()
    {
        // Créer un faux fichier uploadé
        $fichierTest = $this->creerFichierTest('test.jpg', 'image/jpeg');

        $this->assertIsArray($fichierTest);
        $this->assertEquals('test.jpg', $fichierTest['name']);
        $this->assertEquals(UPLOAD_ERR_OK, $fichierTest['error']);
        $this->assertTrue(file_exists($fichierTest['tmp_name']));
    }

    /**
     * Test 2: Upload réussi d'une image JPG
     */
    public function testUploadReussiJpg()
    {
        $fichierTest = $this->creerFichierTest('photo.jpg', 'image/jpeg');

        $nomFichier = $this->uploadService->uploader($fichierTest);

        $this->assertIsString($nomFichier);
        $this->assertStringStartsWith('upload_', $nomFichier);
        $this->assertStringEndsWith('.jpg', $nomFichier);
        $this->assertTrue(file_exists($this->repertoireTest . $nomFichier));
    }

    /**
     * Test 3: Upload réussi d'une image PNG
     */
    public function testUploadReussiPng()
    {
        $fichierTest = $this->creerFichierTest('screenshot.png', 'image/png');

        $nomFichier = $this->uploadService->uploader($fichierTest);

        $this->assertIsString($nomFichier);
        $this->assertStringEndsWith('.png', $nomFichier);
        $this->assertTrue(file_exists($this->repertoireTest . $nomFichier));
    }

    /**
     * Test 4: Upload réussi d'une image GIF
     */
    public function testUploadReussiGif()
    {
        $fichierTest = $this->creerFichierTest('animation.gif', 'image/gif');

        $nomFichier = $this->uploadService->uploader($fichierTest);

        $this->assertIsString($nomFichier);
        $this->assertStringEndsWith('.gif', $nomFichier);
        $this->assertTrue(file_exists($this->repertoireTest . $nomFichier));
    }

    /**
     * Test 5: Rejet d'une extension non autorisée
     */
    public function testRejetExtensionInterdite()
    {
        $fichierTest = $this->creerFichierTest('script.exe', 'application/x-msdownload');

        $nomFichier = $this->uploadService->uploader($fichierTest);

        $this->assertNull($nomFichier);
        $this->assertFalse(file_exists($this->repertoireTest . $fichierTest['name']));
    }

    /**
     * Test 6: Rejet d'un fichier trop volumineux
     */
    public function testRejetFichierTropVolumineux()
    {
        // Créer un fichier de 10 MB (limite par défaut: 5 MB)
        $fichierTest = $this->creerFichierTest('grosse_image.jpg', 'image/jpeg', 10 * 1024 * 1024);

        $nomFichier = $this->uploadService->uploader($fichierTest);

        $this->assertNull($nomFichier);
    }

    /**
     * Test 7: Configuration personnalisée des extensions
     */
    public function testConfigurationPersonnaliseeExtensions()
    {
        $this->uploadService->setExtensionsAutorisees(['png', 'gif']);

        // JPG devrait être rejeté maintenant
        $fichierJpg = $this->creerFichierTest('test.jpg', 'image/jpeg');
        $resultatJpg = $this->uploadService->uploader($fichierJpg);
        $this->assertNull($resultatJpg);

        // PNG devrait être accepté
        $fichierPng = $this->creerFichierTest('test.png', 'image/png');
        $resultatPng = $this->uploadService->uploader($fichierPng);
        $this->assertIsString($resultatPng);
    }

    /**
     * Test 8: Modification de la limite de taille
     */
    public function testModificationLimiteTaille()
    {
        // Augmenter la limite à 20 MB
        $this->uploadService->setTailleMax(20);

        // Créer un fichier de 10 MB
        $fichierTest = $this->creerFichierTest('grosse_image.jpg', 'image/jpeg', 10 * 1024 * 1024);
        $nomFichier = $this->uploadService->uploader($fichierTest);

        $this->assertIsString($nomFichier);
    }

    /**
     * Test 9: Suppression d'un fichier uploadé
     */
    public function testSuppressionFichier()
    {
        // Créer et uploader un fichier
        $fichierTest = $this->creerFichierTest('a_supprimer.jpg', 'image/jpeg');
        $nomFichier = $this->uploadService->uploader($fichierTest);

        $this->assertTrue(file_exists($this->repertoireTest . $nomFichier));

        // Supprimer le fichier
        $resultat = $this->uploadService->supprimer($nomFichier);

        $this->assertTrue($resultat);
        $this->assertFalse(file_exists($this->repertoireTest . $nomFichier));
    }

    /**
     * Test 10: Tentative de suppression d'un fichier inexistant
     */
    public function testSuppressionFichierInexistant()
    {
        $resultat = $this->uploadService->supprimer('fichier_inexistant.jpg');
        $this->assertFalse($resultat);
    }

    /**
     * Test 11: Upload avec noms de fichiers problématiques
     */
    public function testUploadNomsProblematiques()
    {
        $noms = [
            'photo avec espaces.jpg',
            'photo-avec-tirets.jpg',
            'photo_avec_underscores.jpg',
            'PHOTO_MAJUSCULES.JPG',
            'photo.double.extension.jpg'
        ];

        foreach ($noms as $nom) {
            $fichierTest = $this->creerFichierTest($nom, 'image/jpeg');
            $nomFichier = $this->uploadService->uploader($fichierTest);

            $this->assertIsString($nomFichier, "L'upload du fichier '$nom' a échoué");
            $this->assertTrue(file_exists($this->repertoireTest . $nomFichier));
        }
    }

    /**
     * Test 12: Plusieurs uploads successifs
     */
    public function testMultiplesUploadSuccessifs()
    {
        $fichiers = [];

        for ($i = 1; $i <= 5; $i++) {
            $fichierTest = $this->creerFichierTest("photo_$i.jpg", 'image/jpeg');
            $nomFichier = $this->uploadService->uploader($fichierTest);

            $this->assertIsString($nomFichier);
            $fichiers[] = $nomFichier;
        }

        // Vérifier que tous les fichiers existent
        foreach ($fichiers as $fichier) {
            $this->assertTrue(file_exists($this->repertoireTest . $fichier));
        }
    }

    // ============================================================================
    // HELPER: Crée un fichier temporaire de test
    // ============================================================================
    private function creerFichierTest($nom, $mimeType, $taille = 1024)
    {
        // Créer un fichier temporaire
        $tempFile = tempnam(sys_get_temp_dir(), 'phpunit_');

        // Remplir avec des données
        $contenu = str_repeat('x', $taille);
        file_put_contents($tempFile, $contenu);

        return [
            'name' => $nom,
            'tmp_name' => $tempFile,
            'size' => $taille,
            'error' => UPLOAD_ERR_OK,
            'type' => $mimeType
        ];
    }
}

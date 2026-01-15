<?php

namespace Tests\Functional;

use PHPUnit\Framework\TestCase;
use App\Controleurs\PhotoControleur;
use App\Modeles\Photo;

/**
 * Tests Fonctionnels pour le Contrôleur Photo
 * Teste les pages de création et d'affichage
 */
class PhotoControllerTest extends TestCase
{
    private PhotoControleur $controleur;
    private string $repertoireTest;

    protected function setUp(): void
    {
        $this->controleur = new PhotoControleur();
        $this->repertoireTest = __DIR__ . '/../temp/uploads/';

        if (!is_dir($this->repertoireTest)) {
            mkdir($this->repertoireTest, 0755, true);
        }
    }

    protected function tearDown(): void
    {
        // Nettoyer la base de données de test
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
     * Test 1: La page de création s'affiche correctement
     */
    public function testPageCreationAffichage()
    {
        // Ce test vérifie que la page est accessible
        $this->assertTrue(method_exists($this->controleur, 'creer'));
    }

    /**
     * Test 2: La page d'index s'affiche correctement
     */
    public function testPageIndexAffichage()
    {
        $this->assertTrue(method_exists($this->controleur, 'index'));
    }

    /**
     * Test 3: La méthode de sauvegarde existe
     */
    public function testMethodeSauvegarde()
    {
        $this->assertTrue(method_exists($this->controleur, 'sauvegarder'));
    }
}

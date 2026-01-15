<?php

namespace Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use App\Services\StorageUploadService;

/**
 * Tests du service StorageUploadService (stockage + copie publique)
 */
class StorageUploadServiceTest extends TestCase
{
    /** @var StorageUploadService */
    private $service;
    /** @var string */
    private $dirStorage;
    /** @var string */
    private $dirPublic;

    protected function setUp(): void
    {
        $this->service = new StorageUploadService();

        // Dossiers de test isolés
        $baseTemp = sys_get_temp_dir() . '/bmvc_storage_test_' . uniqid();
        $this->dirStorage = $baseTemp . '/storage/';
        $this->dirPublic = $baseTemp . '/public/';

        // Surcharger via variables d'environnement temporaires
        putenv('REPERTOIRE_UPLOAD_STOCKAGE=' . $this->dirStorage);
        putenv('REPERTOIRE_UPLOAD_PUBLIC=' . $this->dirPublic);

        // Reconstruire le service avec les nouveaux chemins
        $this->service = new StorageUploadService();
    }

    protected function tearDown(): void
    {
        // Nettoyer les dossiers créés
        foreach ([$this->dirStorage, $this->dirPublic] as $dir) {
            if (is_dir($dir)) {
                $fichiers = glob($dir . '*');
                foreach ($fichiers as $fichier) {
                    if (is_file($fichier)) {
                        unlink($fichier);
                    }
                }
                rmdir($dir);
            }
        }
    }

    public function testUploadCreeDansStorageEtPublic()
    {
        $fichier = $this->creerFichierTest('photo.jpg', 'image/jpeg');
        $resultat = $this->service->uploader($fichier);

        $this->assertIsArray($resultat);
        $this->assertArrayHasKey('nom', $resultat);
        $this->assertFileExists($resultat['stockage_absolu']);
        $this->assertFileExists($resultat['public_absolu']);
        $this->assertSame('uploads/' . $resultat['nom'], $resultat['public_relatif']);
    }

    public function testRejetExtensionInterdite()
    {
        $fichier = $this->creerFichierTest('script.exe', 'application/octet-stream');
        $resultat = $this->service->uploader($fichier);

        $this->assertNull($resultat);
        $this->assertNotEmpty($this->service->getDerniereErreur());
    }

    public function testRejetFichierTropGros()
    {
        $fichier = $this->creerFichierTest('gros.zip', 'application/zip', 10 * 1024 * 1024);
        $resultat = $this->service->uploader($fichier);

        $this->assertNull($resultat);
        $this->assertStringContainsString('trop volumineux', (string) $this->service->getDerniereErreur());
    }

    private function creerFichierTest($nom, $mime, $taille = 1024)
    {
        $tmp = tempnam(sys_get_temp_dir(), 'phpunit_');
        file_put_contents($tmp, str_repeat('x', $taille));

        return [
            'name' => $nom,
            'tmp_name' => $tmp,
            'size' => $taille,
            'error' => UPLOAD_ERR_OK,
            'type' => $mime,
        ];
    }
}

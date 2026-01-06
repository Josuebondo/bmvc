# 🧪 Guide Complet: Exécution des Tests BMVC

**Comment exécuter, déboguer et optimiser les tests du framework BMVC**

---

## 📋 Table des Matières

1. [Installation](#installation)
2. [Lancer les Tests](#lancer-les-tests)
3. [Interpréter les Résultats](#interpréter-les-résultats)
4. [Déboguer les Tests](#déboguer-les-tests)
5. [Couverture de Code](#couverture-de-code)
6. [Bonnes Pratiques](#bonnes-pratiques)
7. [CI/CD Integration](#cicd-integration)

---

## 🔧 Installation

### Étape 1: Installer les Dépendances

```bash
# Naviguer au répertoire BMVC
cd c:\xampp\htdocs\BMVC

# Installer avec Composer
composer install --dev

# Vérifier l'installation
composer show phpunit/phpunit
```

### Étape 2: Vérifier PHPUnit

```bash
# Voir version
php ./vendor/bin/phpunit --version
# Output: PHPUnit 9.5.x by Sebastian Bergmann

# Voir aide
php ./vendor/bin/phpunit --help
```

### Étape 3: Vérifier les Fichiers de Test

```bash
# Voir structure
dir /s tests\

# Fichiers attendus:
# tests\bootstrap.php
# tests\Unit\CoreTest.php
# tests\Unit\OrmValidationTest.php
# tests\Functional\FunctionalTest.php
```

---

## 🧪 Lancer les Tests

### Exécution Simple

```bash
# Tous les tests
composer test

# Ou directement
php ./vendor/bin/phpunit

# Résultat attendu:
# PHPUnit 9.5.x
# 35 tests, 0 failures, 0 errors
# Tests: 35
# Assertions: 120
```

### Tests Spécifiques

```bash
# Tests unitaires uniquement
composer test:unit
# Ou: php ./vendor/bin/phpunit tests/Unit

# Tests fonctionnels uniquement
composer test:functional
# Ou: php ./vendor/bin/phpunit tests/Functional

# Tests d'une classe
php ./vendor/bin/phpunit tests/Unit/CoreTest.php

# Tests d'une méthode
php ./vendor/bin/phpunit --filter testGetMethode tests/Unit/CoreTest.php
```

### Options Utiles

```bash
# Mode verbose (affiche détails)
php ./vendor/bin/phpunit -v
# Output:
# RequeteTest::testGetMethode PASSED
# RequeteTest::testGetChemin PASSED
# ...

# Arrêter au premier échec
php ./vendor/bin/phpunit --stop-on-failure

# Afficher seulement les tests qui échouent
php ./vendor/bin/phpunit --testdox

# Listeur de tests (voir structure)
php ./vendor/bin/phpunit --list-tests

# Format JSON
php ./vendor/bin/phpunit --log-json test-output.json
```

---

## 📊 Interpréter les Résultats

### Sortie Réussie

```
PHPUnit 9.5.27 by Sebastian Bergmann and contributors.

.......                                                            7 / 7 (100%)

Time: 123 ms, Memory: 8.00 MB

OK (7 tests, 21 assertions)
```

**Explication:**

- `.` = Test réussi
- `F` = Test échoué
- `E` = Erreur
- `S` = Ignoré
- `R` = Réussi avec avertissement

### Sortie avec Erreur

```
PHPUnit 9.5.27 by Sebastian Bergmann and contributors.

..F.E..                                                            7 / 7 (100%)

Time: 234 ms, Memory: 10.00 MB

FAILURES!
Tests: 7, Assertions: 21, Failures: 1, Errors: 1.

FAIL: RequeteTest::testGetPost
Failed asserting that two strings are equal.
Expected: 'POST'
Actual: 'GET'

ERROR: ReponseTest::testSetStatus
Exception: [HTTP\Exception] Invalid HTTP code
```

### Codes de Sortie

```bash
# 0 = Succès
# 1 = Échec de test
# 2 = Erreur PHPUnit
# 3+ = Erreur système
```

---

## 🐛 Déboguer les Tests

### Ajouter du Debugging

```php
// Dans un test
public function testGetMethode()
{
    $requete = new Requete('POST', '/');

    // Debug output
    echo "Méthode: " . $requete->methode() . "\n";
    var_dump($requete);

    $this->assertEquals('POST', $requete->methode());
}
```

### Exécuter avec Output

```bash
# Afficher les echo/var_dump
php ./vendor/bin/phpunit --display-errors

# Ou: garder output même au succès
php ./vendor/bin/phpunit --display-incomplete
```

### Utiliser XDebug (Optionnel)

```bash
# Avec XDebug activé
php ./vendor/bin/phpunit tests/Unit/CoreTest.php

# Breakpoint: ajouter dans test
xdebug_break();
```

### Assertion Personnalisée

```php
// Créer assertion custom
public function testAvecAssertion()
{
    $result = someFunction();

    $this->assertIsArray($result);
    $this->assertArrayHasKey('status', $result);
    $this->assertSame('success', $result['status']);
    $this->assertCount(5, $result['data']);
}
```

### Mock Objects

```php
// Créer un mock
$mockRequete = $this->createMock(Requete::class);
$mockRequete->method('methode')
           ->willReturn('POST');

$this->assertEquals('POST', $mockRequete->methode());
```

---

## 📈 Couverture de Code

### Générer le Rapport

```bash
# Générer rapport HTML
composer test:coverage
# Ou: php ./vendor/bin/phpunit --coverage-html tests/coverage/html/

# Affiche: tests/coverage/html/index.html
```

### Consulter le Rapport

```bash
# Ouvrir dans navigateur
start tests/coverage/html/index.html

# Ou voir en ligne de commande
php ./vendor/bin/phpunit --coverage-text

# Output:
# Code Coverage Report
#
# Classes: 50.00% (1/2)
# Methods: 100.00% (10/10)
# Lines: 85.00% (85/100)
```

### Couleurs Couverture

- 🟢 Vert: Couverture complète (100%)
- 🟡 Jaune: Couverture partielle (50-99%)
- 🔴 Rouge: Non couvert (0%)

### Améliorer la Couverture

```php
// MAUVAIS: Code non couvert
public function rareFunction() {
    if (isRare()) {  // Rarement atteint
        // ...
    }
}

// BON: Ajouter un test
public function testRareFunction()
{
    // Force isRare() to return true
    $mock = $this->createMock(RareChecker::class);
    $mock->method('isRare')->willReturn(true);

    rareFunction($mock);
}
```

---

## ✅ Bonnes Pratiques

### 1️⃣ Structure de Test

```php
public function testMyFeature()
{
    // 1. ARRANGE: Préparer les données
    $data = ['name' => 'John', 'age' => 30];

    // 2. ACT: Exécuter la fonction
    $result = processData($data);

    // 3. ASSERT: Vérifier le résultat
    $this->assertIsArray($result);
    $this->assertSame('JOHN', $result['name']);
}
```

### 2️⃣ Nommage de Tests

```php
// ✅ BON
public function testValidEmailShouldPass()
public function testInvalidEmailShouldFail()
public function testEmptyNameShouldReturnError()

// ❌ MAUVAIS
public function test1()
public function testStuff()
public function test_validation()
```

### 3️⃣ Assertions Utiles

```php
// Valeurs
$this->assertEquals($expected, $actual);       // ==
$this->assertSame($expected, $actual);         // ===
$this->assertNotEquals($expected, $actual);

// Types
$this->assertIsArray($value);
$this->assertIsString($value);
$this->assertIsInt($value);
$this->assertIsNull($value);

// Collections
$this->assertCount(3, $array);
$this->assertArrayHasKey('key', $array);
$this->assertContains('value', $array);

// Exceptions
$this->expectException(InvalidException::class);
someFunction(); // Doit lever l'exception

// Strings
$this->assertStringContains('substring', 'string');
$this->assertStringStartsWith('prefix', 'prefixtext');
$this->assertStringEndsWith('suffix', 'textsuffix');
```

### 4️⃣ Test Data Providers

```php
// Fournisseur de données
public function validEmailProvider()
{
    return [
        ['test@example.com'],
        ['user@domain.co.uk'],
        ['admin+tag@site.org'],
    ];
}

// Utiliser le provider
/**
 * @dataProvider validEmailProvider
 */
public function testEmailValidation($email)
{
    $this->assertTrue(Validation::email($email));
}

// Résultat: 3 tests générés automatiquement
```

### 5️⃣ Isolation des Tests

```php
// ❌ MAUVAIS: Dépendance entre tests
public function testFirstOperation() { /* ... */ }
public function testSecondOperation() { /* dépend du premier */ }

// ✅ BON: Tests indépendants
public function testFirstOperation() { /* ... */ }
public function testSecondOperation() { /* indépendant */ }

// Chaque test doit:
// 1. Créer ses propres données
// 2. Ne pas dépendre des autres
// 3. Être exécutable seul
```

---

## 🚀 CI/CD Integration

### GitHub Actions

**Fichier:** `.github/workflows/tests.yml`

```yaml
name: Tests
on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    strategy:
      matrix:
        php: ["8.0", "8.1", "8.2"]

    steps:
      - uses: actions/checkout@v2
      - uses: shivammathur/setup-php@v2
        with:
          php-version: ${{ matrix.php }}

      - run: composer install --dev
      - run: composer test
      - run: composer phpstan
      - run: composer cs-check
```

### GitLab CI

**Fichier:** `.gitlab-ci.yml`

```yaml
test:
  image: php:8.1
  script:
    - composer install --dev
    - composer test
    - composer phpstan
    - composer cs-check
```

### Jenkins

```groovy
pipeline {
    agent any

    stages {
        stage('Install') {
            steps {
                sh 'composer install --dev'
            }
        }
        stage('Test') {
            steps {
                sh 'composer test'
            }
        }
        stage('Quality') {
            steps {
                sh 'composer phpstan'
                sh 'composer cs-check'
            }
        }
    }
}
```

---

## 📋 Checklist Avant Commit

```bash
# ✅ À exécuter avant de committer

# 1. Tests passent
composer test

# 2. Pas d'erreur de syntaxe
composer lint

# 3. Standards PSR-12
composer cs-check

# 4. Analyse statique
composer phpstan

# 5. Couverture acceptable
composer test:coverage

# 6. Tout OK?
composer check
```

---

## 🎯 Résumé Commandes

| Commande                   | Action                  |
| -------------------------- | ----------------------- |
| `composer test`            | Exécuter tous les tests |
| `composer test:unit`       | Tests unitaires         |
| `composer test:functional` | Tests fonctionnels      |
| `composer test:coverage`   | Avec rapport HTML       |
| `composer lint`            | Vérifier syntaxe        |
| `composer phpstan`         | Analyse statique        |
| `composer cs-check`        | Standards PSR-12        |
| `composer check`           | Tout vérifier           |

---

## 🆘 Dépannage

### Erreur: "Class not found"

```
Fatal error: Class 'Core\Requete' not found

Solution:
1. Vérifier autoload dans composer.json
2. Vérifier namespaces dans fichiers
3. Relancer: composer dump-autoload
```

### Erreur: "phpunit not found"

```
'phpunit' is not recognized

Solution:
1. composer install --dev
2. Utiliser chemin complet: ./vendor/bin/phpunit
3. Ou ajouter au PATH: set PATH=%PATH%;vendor\bin
```

### Tests Aléatoires

```
Tests parfois réussissent, parfois échouent?

Solution:
1. Vérifier isolation des tests
2. Pas de dépendances externes
3. Pas de fichiers temporaires
4. Pas de dates aléatoires
5. Utiliser des mocks
```

---

## 📚 Ressources

- 📖 [PHPUnit Documentation](https://phpunit.de/)
- 🔗 [PSR-12 Code Standards](https://www.php-fig.org/psr/psr-12/)
- 📊 [Code Coverage Best Practices](https://phpunit.de/code-coverage.html)
- 🚀 [Testing Best Practices](https://github.com/goldspecdigital/phpunit-pretty-print)

---

**Version:** 1.0.0  
**Last Updated:** 2024-01-06  
**Status:** ✅ COMPLETE

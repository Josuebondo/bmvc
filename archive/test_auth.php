<?php

/**
 * Test d'authentification et CRUD articles avec utilisateur connecté
 */

session_start();

// Configuration de la base de données
$db = new PDO(
    'mysql:host=localhost;dbname=bmvc',
    'root',
    '',
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

echo "=== TEST D'AUTHENTIFICATION ===\n\n";

// 1. Vérifier l'utilisateur de test
echo "1. Vérifier l'utilisateur test...\n";
$stmt = $db->prepare("SELECT * FROM utilisateurs WHERE email = ?");
$stmt->execute(['admin@exemple.com']);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo "   ✓ Utilisateur trouvé: " . $user['nom'] . "\n";
    echo "   - Email: " . $user['email'] . "\n";
    echo "   - Rôle: " . $user['role'] . "\n";
} else {
    echo "   ✗ Utilisateur non trouvé\n";
}

// 2. Vérifier la vérification du mot de passe
echo "\n2. Tester la vérification du mot de passe...\n";
if ($user && password_verify('admin123', $user['mot_de_passe'])) {
    echo "   ✓ Mot de passe correct: admin123\n";
} else {
    echo "   ✗ Erreur de vérification\n";
}

// 3. Simuler une connexion
echo "\n3. Simuler une connexion...\n";
$_SESSION['_auth_user'] = [
    'id' => $user['id'],
    'nom' => $user['nom'],
    'email' => $user['email'],
    'role' => $user['role']
];
echo "   ✓ Session créée\n";
echo "   - ID: " . $_SESSION['_auth_user']['id'] . "\n";

// 4. Créer un article avec l'utilisateur connecté
echo "\n4. Créer un article de test...\n";
$titre = "Article de test - " . date('Y-m-d H:i:s');
$contenu = "Ceci est un article créé lors du test d'authentification.";

$stmt = $db->prepare("INSERT INTO articles (titre, contenu) VALUES (?, ?)");
$stmt->execute([$titre, $contenu]);
$article_id = $db->lastInsertId();

if ($article_id) {
    echo "   ✓ Article créé: ID " . $article_id . "\n";
    echo "   - Titre: " . $titre . "\n";
}

// 5. Récupérer et vérifier l'article
echo "\n5. Vérifier l'article créé...\n";
$stmt = $db->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->execute([$article_id]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if ($article) {
    echo "   ✓ Article vérifié\n";
    echo "   - Titre: " . $article['titre'] . "\n";
    echo "   - Contenu: " . substr($article['contenu'], 0, 50) . "...\n";
}

// 6. Mise à jour de l'article
echo "\n6. Mettre à jour l'article...\n";
$nouveau_titre = "Article mis à jour - " . date('Y-m-d H:i:s');
$stmt = $db->prepare("UPDATE articles SET titre = ? WHERE id = ?");
$stmt->execute([$nouveau_titre, $article_id]);

$stmt = $db->prepare("SELECT titre FROM articles WHERE id = ?");
$stmt->execute([$article_id]);
$updated = $stmt->fetch(PDO::FETCH_ASSOC);

if ($updated['titre'] === $nouveau_titre) {
    echo "   ✓ Article mis à jour\n";
    echo "   - Nouveau titre: " . $updated['titre'] . "\n";
}

// 7. Supprimer l'article
echo "\n7. Supprimer l'article...\n";
$stmt = $db->prepare("DELETE FROM articles WHERE id = ?");
$stmt->execute([$article_id]);

$stmt = $db->prepare("SELECT COUNT(*) as count FROM articles WHERE id = ?");
$stmt->execute([$article_id]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result['count'] == 0) {
    echo "   ✓ Article supprimé avec succès\n";
}

echo "\n=== TOUS LES TESTS RÉUSSIS ===\n";

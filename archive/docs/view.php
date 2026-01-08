<?php

/**
 * Convertisseur Markdown vers HTML simple
 * Lit les .md et affiche en HTML
 */

// Récupérer le fichier demandé
$file = $_GET['file'] ?? 'README.md';

// Sécurité: empêcher les traversées de répertoires
$file = str_replace(['../', '\\', "\0"], '', $file);
$filepath = __DIR__ . '/' . $file;

// Vérifier si le fichier existe
if (!file_exists($filepath)) {
    // Si c'est un .html demandé, essayer le .md
    if (substr($file, -5) === '.html') {
        $mdfile = substr($file, 0, -5) . '.md';
        $filepath = __DIR__ . '/' . $mdfile;
        if (!file_exists($filepath)) {
            http_response_code(404);
            die('Fichier non trouvé: ' . htmlspecialchars($file));
        }
    } else {
        http_response_code(404);
        die('Fichier non trouvé: ' . htmlspecialchars($file));
    }
}

// Lire le contenu markdown
$markdown = file_get_contents($filepath);

// Fonction de conversion simple markdown -> html
function markdownToHtml($markdown)
{
    $html = $markdown;

    // Protéger les blocs de code
    $codeBlocks = [];
    $html = preg_replace_callback(
        '/```(.*?)\n(.*?)```/s',
        function ($m) use (&$codeBlocks) {
            $lang = trim($m[1]) ?: 'php';
            $code = htmlspecialchars($m[2]);
            $key = '<!--CODE' . count($codeBlocks) . '-->';
            $codeBlocks[$key] = '<pre><code class="language-' . htmlspecialchars($lang) . '">' . $code . '</code></pre>';
            return $key;
        },
        $html
    );

    // Protéger le code inline
    $inlineCode = [];
    $html = preg_replace_callback(
        '/`([^`]+)`/',
        function ($m) use (&$inlineCode) {
            $key = '<!--INLINE' . count($inlineCode) . '-->';
            $inlineCode[$key] = '<code>' . htmlspecialchars($m[1]) . '</code>';
            return $key;
        },
        $html
    );

    // Protéger les liens
    $html = htmlspecialchars($html, ENT_QUOTES, 'UTF-8');

    // Titres
    $html = preg_replace('/^### (.*?)$/m', '<h3>$1</h3>', $html);
    $html = preg_replace('/^## (.*?)$/m', '<h2>$1</h2>', $html);
    $html = preg_replace('/^# (.*?)$/m', '<h1>$1</h1>', $html);

    // Bold
    $html = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $html);
    $html = preg_replace('/__(.+?)__/', '<strong>$1</strong>', $html);

    // Italic
    $html = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $html);
    $html = preg_replace('/_(.+?)_/', '<em>$1</em>', $html);

    // Listes
    $html = preg_replace('/^- (.*?)$/m', '<li>$1</li>', $html);
    $html = preg_replace('/(<li>.*<\/li>)/s', '<ul>$1</ul>', $html);

    // Paragraphes
    $html = preg_replace('/\n\n+/', '</p><p>', $html);
    $html = '<p>' . $html . '</p>';

    // Nettoyer les <p> vides
    $html = preg_replace('/<p>\s*<\/p>/', '', $html);
    $html = preg_replace('/<p>\s*<(h|ul|ol|pre)/', '<$1', $html);
    $html = preg_replace('/<\/(h|ul|ol|pre)>\s*<\/p>/', '</$1', $html);

    // Restaurer les blocs protégés
    foreach ($codeBlocks as $key => $code) {
        $html = str_replace(htmlspecialchars($key), $code, $html);
    }

    foreach ($inlineCode as $key => $code) {
        $html = str_replace(htmlspecialchars($key), $code, $html);
    }

    return $html;
}

// Extraire le titre
preg_match('/^#+\s+(.*)$/m', $markdown, $titleMatch);
$title = $titleMatch[1] ?? basename($filepath);

// Convertir le markdown
$html = markdownToHtml($markdown);

// Chemin relatif pour le breadcrumb
$relPath = str_replace(__DIR__, '', $filepath);
$relPath = ltrim($relPath, '/\\');

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?> - BMVC Documentation</title>
    <!-- Google Material Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #1e88e5;
            --secondary: #ff6f00;
            --dark-bg: #0d1117;
            --light-bg: #ffffff;
            --dark-text: #e6edf3;
            --light-text: #24292f;
            --border: #d0d7de;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            line-height: 1.6;
            color: var(--light-text);
            background: var(--light-bg);
            margin: 0;
            transition: all 0.3s ease;
        }

        body.dark-mode {
            background-color: var(--dark-bg);
            color: var(--dark-text);
        }

        /* Header Fixe */
        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            border-bottom: 3px solid var(--primary);
            color: var(--light-text);
            padding: 15px 30px;
            z-index: 1000;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            backdrop-filter: blur(10px);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 1;
        }

        .logo {
            width: 60px;
            height: 60px;
            background: transparent;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .header-title h1 {
            font-size: 1.2em;
            color: var(--primary);
            margin: 0;
            font-weight: 700;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .theme-toggle {
            background: var(--primary);
            color: white;
            border: 2px solid var(--primary);
            padding: 8px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.2em;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .theme-toggle:hover {
            background: var(--secondary);
            border-color: var(--secondary);
            transform: scale(1.1) rotate(20deg);
        }

        .material-icons {
            font-size: 1.2em;
            vertical-align: middle;
        }

        main {
            margin-top: 75px;
        }

        .container {
            max-width: 900px;
            margin: 30px auto;
            padding: 30px;
            background: var(--light-bg);
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        body.dark-mode .container {
            background: #161b22;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .breadcrumb {
            margin-bottom: 20px;
            color: #666;
            font-size: 0.9em;
        }

        body.dark-mode .breadcrumb {
            color: #8b949e;
        }

        .breadcrumb a {
            color: var(--primary);
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        h1,
        h2,
        h3 {
            color: var(--primary);
            margin-top: 30px;
            margin-bottom: 15px;
        }

        h1 {
            font-size: 2em;
            border-bottom: 3px solid var(--secondary);
            padding-bottom: 15px;
        }

        h2 {
            font-size: 1.5em;
            border-bottom: 2px solid var(--border);
            padding-bottom: 10px;
        }

        body.dark-mode h2 {
            border-bottom-color: #30363d;
        }

        h3 {
            font-size: 1.2em;
        }

        p {
            margin-bottom: 15px;
        }

        code {
            background: #f0f0f0;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            font-size: 0.95em;
            color: #d32f2f;
        }

        body.dark-mode code {
            background: #30363d;
            color: #ff7b72;
        }

        pre {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            margin: 20px 0;
        }

        pre code {
            background: none;
            color: inherit;
            padding: 0;
        }

        a {
            color: var(--primary);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        a:hover {
            color: var(--secondary);
            text-decoration: underline;
        }

        nav {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            padding: 15px 30px;
            display: flex;
            gap: 20px;
            margin-top: 70px;
        }

        nav a {
            color: white;
            text-decoration: none;
            transition: opacity 0.3s;
        }

        nav a:hover {
            opacity: 0.8;
            text-decoration: underline;
        }

        footer {
            text-align: center;
            padding: 20px;
            color: #666;
            border-top: 1px solid var(--border);
            margin-top: 50px;
        }

        body.dark-mode footer {
            color: #8b949e;
            border-top-color: #30363d;
        }

        @media (max-width: 768px) {
            header {
                padding: 10px 15px;
            }

            .header-title h1 {
                font-size: 1em;
            }

            .container {
                margin: 20px auto;
                padding: 15px;
            }
        }
        }

        a:hover {
            text-decoration: underline;
        }

        ul,
        ol {
            margin-left: 30px;
            margin-bottom: 15px;
        }

        li {
            margin-bottom: 8px;
        }

        strong {
            color: var(--primary);
        }

        em {
            font-style: italic;
            color: #666;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin: 20px 0;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background: var(--primary);
            color: white;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        footer {
            background: #f5f5f5;
            border-top: 1px solid #ddd;
            padding: 20px 30px;
            text-align: center;
            color: #666;
            margin-top: 30px;
        }

        footer a {
            color: var(--primary);
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: var(--primary);
            color: white;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
        }

        .back-link:hover {
            background: var(--secondary);
        }

        @media (max-width: 768px) {
            .container {
                margin: 10px;
                padding: 20px;
            }

            nav {
                flex-wrap: wrap;
                gap: 10px;
            }
        }
    </style>
</head>

<body>
    <!-- Header Fixe -->
    <header>
        <div class="header-left">
            <div class="logo">
                <img src="images/logo.png" alt="BMVC Logo" />
            </div>
            <div class="header-title">
                <h1>BMVC</h1>
            </div>
        </div>
        <div class="header-right">
            <button class="theme-toggle" id="themeToggle" title="Basculer mode sombre/clair">
                <span class="material-icons">light_mode</span>
            </button>
        </div>
    </header>

    <main>
        <nav>
            <a href="index.html"><span class="material-icons">home</span> Accueil</a>
            <a href="?file=guides/getting-started/START_HERE.md"><span class="material-icons">description</span> Démarrage</a>
            <a href="?file=guides/usage/GUIDE_UTILISATION.md"><span class="material-icons">menu_book</span> Utilisation</a>
            <a href="?file=api/Requete.md"><span class="material-icons">api</span> API</a>
        </nav>

        <div class="container">
            <div class="breadcrumb">
                <a href="index.html"><span class="material-icons">home</span> Documentation</a>
                <?php if ($relPath !== 'README.md'): ?>
                    > <strong><?php echo htmlspecialchars($title); ?></strong>
                <?php endif; ?>
            </div>

            <?php echo $html; ?>

            <footer>
                <p>© 2026 BMVC Framework | <a href="index.html">Retour à l'accueil</a></p>
            </footer>
        </div>
    </main>

    <script>
        // Theme Toggle
        const themeToggle = document.getElementById('themeToggle');
        const savedTheme = localStorage.getItem('theme') || 'light';

        if (savedTheme === 'dark') {
            document.body.classList.add('dark-mode');
            themeToggle.innerHTML = '<span class="material-icons">dark_mode</span>';
        }

        themeToggle.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');
            const isDark = document.body.classList.contains('dark-mode');
            themeToggle.innerHTML = isDark ? '<span class="material-icons">dark_mode</span>' : '<span class="material-icons">light_mode</span>';
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        });
    </script>
</body>

<a href="javascript:history.back()" class="back-link">← Retour</a>
</div>

<footer>
    <p>BMVC Framework v1.0.0 - © 2026 BMVC Framework</p>
    <p><a href="index.html">Retour à l'accueil</a></p>
</footer>
</body>

</html>
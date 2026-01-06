<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titre ?? 'BMVC') ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 60px 40px;
            text-align: center;
            max-width: 700px;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
            font-size: 2.3em;
        }

        .logo {
            font-size: 4em;
            margin-bottom: 30px;
        }

        p {
            color: #666;
            font-size: 1.05em;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .features {
            text-align: left;
            margin: 40px 0;
            background: #f5f5f5;
            padding: 30px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }

        .features h3 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.1em;
        }

        .features ul {
            list-style: none;
        }

        .features li {
            padding: 8px 0;
            color: #555;
        }

        .features li:before {
            content: "✓ ";
            color: #667eea;
            font-weight: bold;
            margin-right: 12px;
        }

        .buttons {
            margin-top: 40px;
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            font-size: 1em;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .version {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #eee;
            color: #999;
            font-size: 0.9em;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo">🚀</div>
        <h1><?= htmlspecialchars($titre ?? 'BMVC') ?></h1>
        <p><?= htmlspecialchars($description ?? 'Bienvenue dans BMVC') ?></p>

        <div class="features">
            <h3>✨ Caractéristiques</h3>
            <ul>
                <li>Routage moderne et flexible (style Laravel)</li>
                <li>Architecture MVC bien structurée</li>
                <li>ORM élégant inspiré d'Eloquent</li>
                <li>Gestion des vues avec PHP natif</li>
                <li>Session et authentification</li>
                <li>Nomenclature 100% en français</li>
            </ul>
        </div>

        <div class="buttons">
            <a href="/auth/login" class="btn btn-primary">🔐 Se connecter</a>
        </div>

        <div class="version">
            <strong>BMVC 1.0.0</strong><br>
            Framework web français, professionnel et pédagogique
        </div>
    </div>
</body>

</html>
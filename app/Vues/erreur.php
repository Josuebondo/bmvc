<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Non Trouvée</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow: hidden;
        }

        .stars {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .star {
            position: absolute;
            width: 2px;
            height: 2px;
            background: white;
            border-radius: 50%;
            opacity: 0.7;
        }

        .container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 700px;
            width: 100%;
            padding: 80px 50px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .animated-404 {
            font-size: 140px;
            font-weight: 900;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
            animation: bounce 2s infinite;
            line-height: 1;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .not-found {
            font-size: 24px;
            color: #666;
            margin: 30px 0 20px;
            font-weight: 500;
        }

        .message {
            font-size: 18px;
            color: #888;
            margin-bottom: 50px;
            line-height: 1.6;
        }

        .illustration {
            margin: 30px 0;
            font-size: 100px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .actions {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 14px 35px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 15px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #f0f0f0;
            color: #333;
            border: 2px solid #e0e0e0;
        }

        .btn-secondary:hover {
            background: #e8e8e8;
            border-color: #d0d0d0;
            transform: translateY(-3px);
        }

        .footer {
            margin-top: 60px;
            padding-top: 30px;
            border-top: 1px solid #e0e0e0;
            font-size: 13px;
            color: #aaa;
        }

        .footer p {
            margin: 8px 0;
        }

        .footer a {
            color: #667eea;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        .search-box {
            margin: 40px 0;
        }

        .search-box input {
            width: 100%;
            max-width: 400px;
            padding: 12px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 50px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .search-box input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        @media (max-width: 600px) {
            .container {
                padding: 60px 30px;
            }

            .animated-404 {
                font-size: 100px;
            }

            .illustration {
                font-size: 70px;
            }

            .not-found {
                font-size: 20px;
            }

            .message {
                font-size: 16px;
            }

            .actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="stars" id="stars"></div>

    <div class="container">
        <div class="illustration">🔍</div>

        <h1 class="animated-404">404</h1>

        <div class="not-found">Page Non Trouvée</div>

        <p class="message">
            <?php
            if ($code == 404) {
                echo "La page que vous cherchez n'existe pas.<br>";
                echo "Vérifiez l'adresse URL et réessayez.";
            } else {
                echo "Une erreur s'est produite.<br>";
                echo "Veuillez réessayer plus tard.";
            }
            ?>
        </p>

        <div class="actions">
            <a href="<?= url('/') ?>" class="btn btn-primary">
                🏠 Retour à l'accueil
            </a>
            <button onclick="history.back()" class="btn btn-secondary">
                ← Page Précédente
            </button>
        </div>

        <div class="footer">
            <p>BMVC Framework © 2026</p>
            <p>Si vous pensez que c'est une erreur, <a href="mailto:support@bmvc.local">contactez-nous</a></p>
            <p>URL demandée: <code style="background: #f5f5f5; padding: 2px 6px; border-radius: 3px;"><?= $_SERVER['REQUEST_URI'] ?? '/' ?></code></p>
        </div>
    </div>

    <script>
        function createStars() {
            const starsContainer = document.getElementById('stars');
            for (let i = 0; i < 50; i++) {
                const star = document.createElement('div');
                star.className = 'star';
                star.style.left = Math.random() * 100 + '%';
                star.style.top = Math.random() * 100 + '%';
                star.style.animationDelay = Math.random() * 2 + 's';
                starsContainer.appendChild(star);
            }
        }

        function search() {
            const query = document.getElementById('searchInput').value;
            if (query) {
                window.location.href = '<?= url('/') ?>' + '?search=' + encodeURIComponent(query);
            }
        }

        createStars();
    </script>
</body>

</html>
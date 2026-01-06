<?php

namespace Bmvc;

use Composer\Script\Event;

class InstallerScript
{
    public static function afficherLogo(Event $event): void
    {
        $logo = <<<'LOGO'
╔══════════════════════════════════════════════════════════════════════════════╗
║                                                                              ║
║                         🚀  BMVC FRAMEWORK  🚀                              ║
║                                                                              ║
║                   Framework PHP MVC 100% en Français                         ║
║                                                                              ║
║                               ✨ OK INSTALLÉ ✨                            ║
║                                                                              ║
║                     https://github.com/Josuebondo/bmvc                       ║
║                    https://packagist.org/packages/bmvc/bmvc                  ║
║                                                                              ║
╚══════════════════════════════════════════════════════════════════════════════╝

PROCHAINES ÉTAPES :

   1. Accédez au dossier de votre projet :
      $ cd votre-projet

   2. Démarrez le serveur de développement :
      $ php -S localhost:8000 -t public

   3. Ouvrez votre navigateur :
      http://localhost:8000

   4. Consultez la documentation :
      https://github.com/Josuebondo/bmvc/blob/main/README_FR.md

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

✅ Installation réussie ! Bienvenue dans BMVC !

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

LOGO;

        echo "\n" . $logo . "\n\n";
    }
}

<?php

namespace Bmvc;

use Composer\Script\Event;

class InstallerScript
{
    public static function afficherLogo(Event $event): void
    {
        $logo = <<<'LOGO'

                          ╔═════════════════════════╗
                         ╱                           ╲
                        ╱    ░░░ BMVC ░░░           ╲
                       ╱                              ╲
                      ╱   ╔═══════════════════════╗   ╲
                     ╱    ║   V     V     V       ║    ╲
                    ╱     ║ V               V     ║     ╲
                   ╱      ║ V     BMVC      V     ║      ╲
                  ╱       ║ V               V     ║       ╲
                 ╱        ║   V     V     V       ║        ╲
                ╱         ╚═══════════════════════╝         ╲
               ╱                                              ╲
              ╚════════════════════════════════════════════════╝


          Framework PHP MVC 100% en Français
          
          ✨ Installation réussie ! ✨

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

PROCHAINES ÉTAPES :

   1. Accédez au dossier de votre projet :
      $ cd votre-projet

   2. Démarrez le serveur de développement :
      $ php -S localhost:8000 -t public

   3. Ouvrez votre navigateur :
      http://localhost:8000

   4. Consultez la documentation :
      https://github.com/Josuebondo/bmvc/blob/main/README_FR.md

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

✅ Bienvenue dans BMVC ! Bon développement ! 🎉

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

LOGO;

        echo "\n" . $logo . "\n\n";
    }
}

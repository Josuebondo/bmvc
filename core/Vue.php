<?php

namespace Core;

/**
 * ======================================================================
 * Vue - Moteur de vues avancé avec layouts et sections
 * ======================================================================
 * 
 * Fonctionnalités :
 * - Layouts et héritage de vues
 * - Sections nommées
 * - Inclusion de vues partielles
 * - Protection XSS avec echapper()
 */
class Vue
{
    protected string $cheminVues;
    protected array $donnees = [];
    protected array $sections = [];
    /** @var string|null */
    protected $layoutActuel = null;
    /** @var Vue|null */
    protected static $instance = null;

    /**
     * @param string $cheminVues
     */
    public function __construct($cheminVues)
    {
        $this->cheminVues = $cheminVues;
        self::$instance = $this;
    }

    /**
     * Rend une vue
     */
    /**
     * @param string $vue
     * @param array $donnees
     * @return string
     */
    public function rendre($vue, $donnees = [])
    {
        $this->donnees = $donnees;
        $this->sections = [];
        $this->layoutActuel = null;

        $fichier = $this->resolveCheminVue($vue);

        if (!file_exists($fichier)) {
            throw new \Exception("Vue non trouvée: $fichier");
        }

        // Lire le fichier et compiler les directives blade
        $contenuBrut = file_get_contents($fichier);
        $contenuCompile = $this->compileBlade($contenuBrut);

        // Écrire le code compilé dans un fichier temporaire et l'inclure
        $tempFile = sys_get_temp_dir() . '/bmvc_vue_' . md5($contenuCompile) . '.php';
        // Nettoyer le contenu compilé qui peut déjà commencer par <?php
        if (strpos($contenuCompile, '<?php') === 0) {
            file_put_contents($tempFile, $contenuCompile);
        } else {
            file_put_contents($tempFile, '<?php ' . $contenuCompile);
        }

        ob_start();
        extract($donnees, EXTR_SKIP);
        include $tempFile;
        $contenu = ob_get_clean();

        // Nettoyer le fichier temporaire
        @unlink($tempFile);

        // Si un layout est défini, le rendre avec le contenu
        if ($this->layoutActuel !== null) {
            $contenu = $this->rendreLayout($this->layoutActuel, $contenu);
            $this->layoutActuel = null;
        }

        return $contenu;
    }

    /**
     * Affiche une vue
     */
    /**
     * @param string $vue
     * @param array $donnees
     */
    public function afficher($vue, $donnees = [])
    {
        echo $this->rendre($vue, $donnees);
    }

    /**
     * Défini un layout pour la vue actuelle
     */
    /**
     * @param string $layout
     */
    public static function extends($layout)
    {
        if (self::$instance) {
            self::$instance->layoutActuel = $layout;
        }
    }

    /**
     * Commence une section
     */
    public static function debut_section(string $nom): void
    {
        ob_start();
    }

    /**
     * Termine une section et la stocke
     */
    /**
     * @param string $nom
     */
    public static function fin_section($nom)
    {
        $contenu = ob_get_clean();
        if (self::$instance) {
            self::$instance->sections[$nom] = $contenu;
        }
    }

    /**
     * Affiche le contenu d'une section
     */
    /**
     * @param string $nom
     * @param string $contenuParDefaut
     */
    public static function section($nom, $contenuParDefaut = '')
    {
        if (self::$instance && isset(self::$instance->sections[$nom])) {
            echo self::$instance->sections[$nom];
        } else {
            echo $contenuParDefaut;
        }
    }

    /**
     * Inclue une vue partielle
     */
    /**
     * @param string $vue
     * @param array $donnees
     * @return string
     */
    public function inclure($vue, $donnees = [])
    {
        $donnees = array_merge($this->donnees, $donnees);
        $fichier = $this->resolveCheminVue($vue);

        if (!file_exists($fichier)) {
            throw new \Exception("Vue partielle non trouvée: $fichier");
        }

        ob_start();
        extract($donnees, EXTR_SKIP);
        include $fichier;
        return ob_get_clean();
    }

    /**
     * Affiche une variable échappée (protection XSS)
     */
    public static function echapper($valeur): string
    {
        return htmlspecialchars($valeur, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Raccourci pour échapper et afficher
     */
    public static function e($valeur): string
    {
        return self::echapper($valeur);
    }

    /**
     * Résout le chemin complet d'une vue
     */
    protected function resolveCheminVue(string $vue): string
    {
        $cheminVues = str_replace('\\', '/', $this->cheminVues);
        $chemin = $cheminVues . '/' . str_replace('.', '/', $vue) . '.php';
        return str_replace('/', DIRECTORY_SEPARATOR, $chemin);
    }

    /**
     * Rend un layout avec son contenu
     */
    protected function rendreLayout(string $layout, string $contenu): string
    {
        // Stocker le contenu principal dans une section spéciale
        // SEULEMENT si la section 'contenu' n'a pas déjà été définie
        if (!isset($this->sections['contenu'])) {
            $this->sections['contenu'] = $contenu;
        }

        $fichierLayout = $this->resolveCheminVue($layout);

        if (!file_exists($fichierLayout)) {
            throw new \Exception("Layout non trouvé: $fichierLayout");
        }

        ob_start();
        extract($this->donnees, EXTR_SKIP);
        include $fichierLayout;
        return ob_get_clean();
    }

    /**
     * Compile les directives Blade
     */
    protected function compileBlade(string $contenu): string
    {
        // @extends('layout') -> Vue::extends('layout');
        $contenu = preg_replace_callback(
            "/@extends\s*\(\s*['\"]([^'\"]+)['\"]\s*\)/",
            function ($matches) {
                return "<?php \Core\Vue::extends('{$matches[1]}'); ?>";
            },
            $contenu
        );

        // @section('name') ... @endsection
        // Capturer les sections avec leurs contenus
        $contenu = preg_replace_callback(
            "/@section\s*\(\s*['\"]([^'\"]+)['\"]\s*\)\s*(.*?)\s*@endsection/s",
            function ($matches) {
                $nom = $matches[1];
                $contenuSection = $matches[2];
                return "<?php \Core\Vue::debut_section('{$nom}'); ?>" .
                    $contenuSection .
                    "<?php \Core\Vue::fin_section('{$nom}'); ?>";
            },
            $contenu
        );

        return $contenu;
    }
}

<?php

namespace Core;

/**
 * ======================================================================
 * Formulaire - Classe d'aide pour les formulaires HTML
 * ======================================================================
 *
 * API statique pour générer des champs de formulaire HTML.
 */
class Formulaire
{
    /**
     * Génère les attributs HTML (utilisable en statique)
     */
    private static function genererAttributsStatic(array $attributs): string
    {
        $html = '';
        foreach ($attributs as $cle => $valeur) {
            $html .= " $cle=\"" . htmlspecialchars((string) $valeur, ENT_QUOTES, 'UTF-8') . "\"";
        }
        return $html;
    }

    /**
     * -------------------------
     * Méthodes statiques simples
     * -------------------------
     */

    public static function texte(string $nom, string $valeur = '', array $attributs = []): string
    {
        $attrs = self::genererAttributsStatic($attributs);
        $val = htmlspecialchars($valeur, ENT_QUOTES, 'UTF-8');
        return "<input type=\"text\" name=\"$nom\" value=\"$val\"$attrs>";
    }

    public static function email(string $nom, string $valeur = '', array $attributs = []): string
    {
        $attrs = self::genererAttributsStatic($attributs);
        $val = htmlspecialchars($valeur, ENT_QUOTES, 'UTF-8');
        return "<input type=\"email\" name=\"$nom\" value=\"$val\"$attrs>";
    }

    public static function password(string $nom, array $attributs = []): string
    {
        $attrs = self::genererAttributsStatic($attributs);
        return "<input type=\"password\" name=\"$nom\"$attrs>";
    }

    public static function recherche(string $nom, string $valeur = '', array $attributs = []): string
    {
        $attrs = self::genererAttributsStatic($attributs);
        $val = htmlspecialchars($valeur, ENT_QUOTES, 'UTF-8');
        return "<input type=\"search\" name=\"$nom\" value=\"$val\"$attrs>";
    }

    public static function textarea(string $nom, string $valeur = '', array $attributs = []): string
    {
        $attrs = self::genererAttributsStatic($attributs);
        $val = htmlspecialchars($valeur, ENT_QUOTES, 'UTF-8');
        return "<textarea name=\"$nom\"$attrs>$val</textarea>";
    }

    public static function select(string $nom, array $options, mixed $valeurSelectionnee = null, array $attributs = []): string
    {
        $attrs = self::genererAttributsStatic($attributs);
        $htmlOptions = '';
        foreach ($options as $val => $label) {
            $sel = ((string) $val === (string) $valeurSelectionnee) ? ' selected' : '';
            $htmlOptions .= '<option value="' . htmlspecialchars((string) $val, ENT_QUOTES, 'UTF-8') . '"' . $sel . '>' . htmlspecialchars((string) $label, ENT_QUOTES, 'UTF-8') . '</option>';
        }
        return "<select name=\"$nom\"$attrs>$htmlOptions</select>";
    }

    public static function checkbox(string $nom, mixed $valeur = 1, bool $cocher = false, array $attributs = []): string
    {
        $attrs = self::genererAttributsStatic($attributs);
        $val = htmlspecialchars((string) $valeur, ENT_QUOTES, 'UTF-8');
        $checked = $cocher ? ' checked' : '';
        return "<input type=\"checkbox\" name=\"$nom\" value=\"$val\"$checked$attrs>";
    }

    public static function radio(string $nom, mixed $valeur, bool $cocher = false, array $attributs = []): string
    {
        $attrs = self::genererAttributsStatic($attributs);
        $val = htmlspecialchars((string) $valeur, ENT_QUOTES, 'UTF-8');
        $checked = $cocher ? ' checked' : '';
        return "<input type=\"radio\" name=\"$nom\" value=\"$val\"$checked$attrs>";
    }

    public static function groupe(string $label, string $champ, ?string $erreur = null): string
    {
        $lab = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
        $errHtml = $erreur ? '<span class="error">' . htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') . '</span>' : '';
        return <<<HTML
<div class="form-group">
    <label>$lab</label>
    $champ
    $errHtml
</div>
HTML;
    }

    public static function bouton(string $texte, array $attributs = []): string
    {
        $attrs = self::genererAttributsStatic($attributs);
        $txt = htmlspecialchars($texte, ENT_QUOTES, 'UTF-8');
        return "<button type=\"button\"$attrs>$txt</button>";
    }

    public static function submit(string $texte = 'Envoyer', array $attributs = []): string
    {
        $attrs = self::genererAttributsStatic($attributs);
        $txt = htmlspecialchars($texte, ENT_QUOTES, 'UTF-8');
        return "<button type=\"submit\"$attrs>$txt</button>";
    }
}

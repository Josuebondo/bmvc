<?php

namespace Bmvc\Cli;

/**
 * Logo officiel BMVC colorisé
 * Style CLI professionnel avec codes ANSI
 */
class Logo
{
    /**
     * Affiche le logo BMVC colorisé
     */
    public static function afficher(): void
    {
        echo "\n";
        echo Colors::$cyan;
        echo "      ██████╗ ███╗   ███╗\n";
        echo "     ██╔═══██╗████╗ ████║\n";
        echo "     ██║   ██║██╔████╔██║\n";
        echo "     ██║   ██║██║╚██╔╝██║\n";
        echo "     ╚██████╔╝██║ ╚═╝ ██║\n";
        echo "      ╚═════╝ ╚═╝     ╚═╝\n";
        echo Colors::$reset;

        echo "\n";
        echo Colors::$blue . "BM" . Colors::$reset;
        echo Colors::$orange . "VC" . Colors::$reset . " ";
        echo Colors::$white . "Framework PHP moderne et léger" . Colors::$reset . "\n";

        echo Colors::$cyan . "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" . Colors::$reset . "\n";
        echo Colors::$green . "✔ MVC simple & structuré\n";
        echo "✔ CLI puissant\n";
        echo "✔ Layouts & Sections\n";
        echo "✔ Inspiré Laravel / Symfony\n";
        echo "✔ 100% PHP natif" . Colors::$reset . "\n";

        echo "\n";
        echo Colors::$white . "Version : " . Colors::$reset;
        echo Colors::$orange . "1.0.5" . Colors::$reset . "\n";
        echo Colors::$white . "Auteur  : " . Colors::$reset;
        echo Colors::$cyan . "Bondo Josué" . Colors::$reset . "\n";
        echo Colors::$cyan . "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━" . Colors::$reset . "\n\n";
    }

    /**
     * Affiche le logo condensé (pour les messages rapides)
     */
    public static function afficherCondense(): void
    {
        echo Colors::$blue . "BM" . Colors::$reset;
        echo Colors::$orange . "VC" . Colors::$reset;
    }

    /**
     * Affiche un message de succès stylisé
     */
    public static function succes(string $message): void
    {
        echo Colors::$green . "✔ " . Colors::$reset;
        echo Colors::$white . $message . Colors::$reset . "\n";
    }

    /**
     * Affiche un message d'erreur stylisé
     */
    public static function erreur(string $message): void
    {
        echo Colors::$red . "✘ " . Colors::$reset;
        echo Colors::$white . $message . Colors::$reset . "\n";
    }

    /**
     * Affiche un message info stylisé
     */
    public static function info(string $message): void
    {
        echo Colors::$cyan . "➡ " . Colors::$reset;
        echo Colors::$white . $message . Colors::$reset . "\n";
    }
}

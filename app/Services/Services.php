<?php

namespace App\Services;

use App\Modeles\Utilisateur;
use Core\Validateur;

/**
 * Service d'Authentification
 * Encapsule la logique d'authentification réutilisable
 */
class AuthService
{
    /**
     * Authentifie un utilisateur
     */
    public function connexion(string $email, string $motDePasse): ?Utilisateur
    {
        $utilisateur = Utilisateur::parEmail($email);

        if (!$utilisateur || !$utilisateur->verifierMotDePasse($motDePasse)) {
            return null;
        }

        return $utilisateur;
    }

    /**
     * Crée un nouvel utilisateur
     */
    public function inscription(array $donnees): ?Utilisateur
    {
        return Utilisateur::creerUtilisateur($donnees);
    }

    /**
     * Valide les données de connexion
     */
    public function validerConnexion(array $donnees): Validateur
    {
        $v = new Validateur();
        $v->ajouter('email', ['requis', 'email']);
        $v->ajouter('mot_de_passe', ['requis', 'min:8']);
        $v->valider($donnees);

        return $v;
    }

    /**
     * Valide les données d'inscription
     */
    public function validerInscription(array $donnees): Validateur
    {
        $v = new Validateur();
        $v->ajouter('nom', ['requis', 'min:3']);
        $v->ajouter('email', ['requis', 'email']);
        $v->ajouter('mot_de_passe', ['requis', 'min:8']);
        $v->ajouter('confirmation_mot_de_passe', ['match:mot_de_passe']);
        $v->valider($donnees);

        return $v;
    }
}

/**
 * Service de Validation
 * Encapsule les règles de validation réutilisables
 */
class ValidationService
{
    /**
     * Valide un article
     */
    public function validerArticle(array $donnees): Validateur
    {
        $v = new Validateur();
        $v->ajouter('titre', ['requis', 'min:3', 'max:255']);
        $v->ajouter('contenu', ['requis', 'min:10']);
        $v->valider($donnees);

        return $v;
    }

    /**
     * Valide un email
     */
    public function validerEmail(string $email): Validateur
    {
        $v = new Validateur();
        $v->ajouter('email', ['requis', 'email']);
        $v->valider(['email' => $email]);

        return $v;
    }

    /**
     * Valide un mot de passe fort
     */
    public function validerMotDePasseFort(string $motDePasse): Validateur
    {
        $v = new Validateur();
        $v->ajouter('mot_de_passe', [
            'requis',
            'min:8',
            'regex:/[A-Z]/',  // Au moins une majuscule
            'regex:/[0-9]/',  // Au moins un chiffre
        ], [
            'regex' => 'Le mot de passe doit contenir au minimum une majuscule et un chiffre'
        ]);
        $v->valider(['mot_de_passe' => $motDePasse]);

        return $v;
    }
}

/**
 * Service d'Upload de Fichiers
 * Gère les uploads sécurisés
 */
class UploadService
{
    private string $repertoireUpload = __DIR__ . '/../../public/uploads/';
    private array $extensionsAutorisees = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
    private int $tailleMaxMo = 5;

    /**
     * Définit le répertoire d'upload
     */
    public function setRepertoire(string $repertoire): self
    {
        $this->repertoireUpload = $repertoire;
        return $this;
    }

    /**
     * Définit les extensions autorisées
     */
    public function setExtensionsAutorisees(array $extensions): self
    {
        $this->extensionsAutorisees = $extensions;
        return $this;
    }

    /**
     * Définit la taille max en Mo
     */
    public function setTailleMax(int $mo): self
    {
        $this->tailleMaxMo = $mo;
        return $this;
    }

    /**
     * Upload un fichier
     */
    public function uploader(array $fichier): ?string
    {
        // Validation
        if (!$this->validerFichier($fichier)) {
            return null;
        }

        // Crée le dossier s'il n'existe pas
        if (!is_dir($this->repertoireUpload)) {
            mkdir($this->repertoireUpload, 0755, true);
        }

        // Génère un nom unique
        $extension = pathinfo($fichier['name'], PATHINFO_EXTENSION);
        $nomFichier = uniqid('upload_') . '.' . $extension;
        $cheminComplet = $this->repertoireUpload . $nomFichier;

        // Déplace le fichier
        if (move_uploaded_file($fichier['tmp_name'], $cheminComplet)) {
            return $nomFichier;
        }

        return null;
    }

    /**
     * Valide un fichier
     */
    private function validerFichier(array $fichier): bool
    {
        // Vérifier les erreurs d'upload
        if ($fichier['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        // Vérifier la taille
        if ($fichier['size'] > ($this->tailleMaxMo * 1024 * 1024)) {
            return false;
        }

        // Vérifier l'extension
        $extension = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $this->extensionsAutorisees)) {
            return false;
        }

        return true;
    }

    /**
     * Supprime un fichier
     */
    public function supprimer(string $nomFichier): bool
    {
        $chemin = $this->repertoireUpload . $nomFichier;

        if (file_exists($chemin)) {
            return unlink($chemin);
        }

        return false;
    }
}

/**
 * Service de Notifications
 * Gère l'envoi de notifications (email, flash messages)
 */
class NotificationService
{
    /**
     * Envoie un email simple
     */
    public function envoyerEmail(string $destinataire, string $sujet, string $corps, string $htmlCorps = ''): bool
    {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type: " . (!empty($htmlCorps) ? "text/html" : "text/plain") . "; charset=UTF-8" . "\r\n";
        $headers .= "From: noreply@bmvc.local" . "\r\n";

        $corps_envoyer = !empty($htmlCorps) ? $htmlCorps : $corps;

        return mail($destinataire, $sujet, $corps_envoyer, $headers);
    }

    /**
     * Envoie un email de bienvenue
     */
    public function bienvenue(string $email, string $nom): bool
    {
        $sujet = "Bienvenue sur BMVC!";

        $corps = "Bonjour $nom,\n\n";
        $corps .= "Votre compte a été créé avec succès!\n\n";
        $corps .= "Cordialement,\nL'équipe BMVC";

        return $this->envoyerEmail($email, $sujet, $corps);
    }

    /**
     * Envoie un email de réinitialisation
     */
    public function reinitialiserMotDePasse(string $email, string $token): bool
    {
        $sujet = "Réinitialiser votre mot de passe";

        $lien = "http://localhost/BMVC/reinitialiser?token=" . $token;

        $corps = "Cliquez sur ce lien pour réinitialiser votre mot de passe:\n\n";
        $corps .= $lien . "\n\n";
        $corps .= "Ce lien expire dans 24 heures.\n\n";
        $corps .= "Si vous n'avez pas demandé ceci, ignorez cet email.\n";

        return $this->envoyerEmail($email, $sujet, $corps);
    }

    /**
     * Ajoute un message flash à la session
     */
    public function succes(string $message): void
    {
        if (!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = [];
        }
        $_SESSION['flash']['succes'] = $message;
    }

    /**
     * Ajoute un message d'erreur
     */
    public function erreur(string $message): void
    {
        if (!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = [];
        }
        $_SESSION['flash']['erreur'] = $message;
    }

    /**
     * Ajoute un message d'avertissement
     */
    public function warning(string $message): void
    {
        if (!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = [];
        }
        $_SESSION['flash']['avertissement'] = $message;
    }

    /**
     * Ajoute un message d'info
     */
    public function info(string $message): void
    {
        if (!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = [];
        }
        $_SESSION['flash']['info'] = $message;
    }
}

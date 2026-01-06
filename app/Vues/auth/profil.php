<?php

/** @var App\Modeles\Utilisateur $utilisateur */
$utilisateur = $utilisateur ?? null;

?>

<style>
    .profile-card {
        border: none;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        border-radius: 15px;
        overflow: hidden;
    }

    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 40px 20px;
        text-align: center;
        color: white;
    }

    .profile-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 40px;
    }

    .profile-info-row {
        display: flex;
        align-items: center;
        padding: 20px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .profile-info-row:last-child {
        border-bottom: none;
    }

    .profile-label {
        color: #667eea;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 150px;
    }

    .profile-value {
        flex: 1;
        color: #333;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card profile-card">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <h3 class="mb-1"><?= e($utilisateur->nom ?? 'Utilisateur') ?></h3>
                    <p class="mb-0" style="opacity: 0.9;">Mon compte</p>
                </div>

                <div class="card-body p-5">
                    <!-- Messages de statut -->
                    <?php if (isset($_SESSION['succes'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> <?= e($_SESSION['succes']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['succes']); ?>
                    <?php endif; ?>

                    <!-- Informations du profil -->
                    <div class="profile-info-row">
                        <div class="profile-label">
                            <i class="fas fa-user"></i> Nom
                        </div>
                        <div class="profile-value">
                            <?= e($utilisateur->nom ?? 'N/A') ?>
                        </div>
                    </div>

                    <div class="profile-info-row">
                        <div class="profile-label">
                            <i class="fas fa-envelope"></i> Email
                        </div>
                        <div class="profile-value">
                            <?= e($utilisateur->email ?? 'N/A') ?>
                        </div>
                    </div>

                    <div class="profile-info-row">
                        <div class="profile-label">
                            <i class="fas fa-shield-alt"></i> Rôle
                        </div>
                        <div class="profile-value">
                            <?php if ($utilisateur->role === 'admin'): ?>
                                <span class="badge bg-danger" style="font-size: 12px; padding: 6px 12px;">
                                    <i class="fas fa-crown"></i> Administrateur
                                </span>
                            <?php else: ?>
                                <span class="badge bg-info" style="font-size: 12px; padding: 6px 12px;">
                                    <i class="fas fa-user-check"></i> Utilisateur
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="profile-info-row">
                        <div class="profile-label">
                            <i class="fas fa-calendar"></i> Inscrit
                        </div>
                        <div class="profile-value">
                            <?= e($utilisateur->created_at ?? 'N/A') ?>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-5 pt-4 border-top">
                        <div class="d-grid gap-2">
                            <a href="/articles" class="btn btn-primary btn-lg mb-2">
                                <i class="fas fa-newspaper"></i> Mes articles
                            </a>
                            <a href="/deconnexion" class="btn btn-outline-danger btn-lg">
                                <i class="fas fa-sign-out-alt"></i> Se déconnecter
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
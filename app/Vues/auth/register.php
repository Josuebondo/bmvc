<?php

/** @var array $erreurs */
$erreurs = $erreurs ?? [];
$anciens_inputs = $anciens_inputs ?? [];

?>

<style>
    .register-container {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
    }

    .register-card {
        border: none;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        border-radius: 15px;
        overflow: hidden;
    }

    .register-header {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        padding: 40px 20px;
        text-align: center;
    }

    .register-header h4 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 5px;
    }
</style>

<div class="register-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-6">
                <div class="card register-card">
                    <div class="register-header text-white">
                        <h4 class="mb-2"><i class="fas fa-user-plus"></i> Inscription</h4>
                        <p class="mb-0" style="font-size: 14px; opacity: 0.9;">Créez votre compte</p>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($erreurs['general'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php foreach ($erreurs['general'] as $erreur): ?>
                                    <div><?= e($erreur) ?></div>
                                <?php endforeach; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="/register">
                            <?= csrf_input() ?>

                            <div class="mb-4">
                                <label for="nom" class="form-label fw-600">
                                    <i class="fas fa-user"></i> Nom complet
                                </label>
                                <input
                                    type="text"
                                    class="form-control form-control-lg <?= !empty($erreurs['nom']) ? 'is-invalid' : '' ?>"
                                    id="nom"
                                    name="nom"
                                    value="<?= e($anciens_inputs['nom'] ?? '') ?>"
                                    placeholder="Votre nom"
                                    required>
                                <?php if (!empty($erreurs['nom'])): ?>
                                    <div class="invalid-feedback d-block">
                                        <?= e($erreurs['nom'][0]) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-4">
                                <label for="email" class="form-label fw-600">
                                    <i class="fas fa-envelope"></i> Adresse Email
                                </label>
                                <input
                                    type="email"
                                    class="form-control form-control-lg <?= !empty($erreurs['email']) ? 'is-invalid' : '' ?>"
                                    id="email"
                                    name="email"
                                    value="<?= e($anciens_inputs['email'] ?? '') ?>"
                                    placeholder="votre@email.com"
                                    required>
                                <?php if (!empty($erreurs['email'])): ?>
                                    <div class="invalid-feedback d-block">
                                        <?= e($erreurs['email'][0]) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-4">
                                <label for="motdepasse" class="form-label fw-600">
                                    <i class="fas fa-key"></i> Mot de passe
                                </label>
                                <input
                                    type="password"
                                    class="form-control form-control-lg <?= !empty($erreurs['motdepasse']) ? 'is-invalid' : '' ?>"
                                    id="motdepasse"
                                    name="motdepasse"
                                    placeholder="••••••••"
                                    required>
                                <?php if (!empty($erreurs['motdepasse'])): ?>
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-times-circle"></i> <?= e($erreurs['motdepasse'][0]) ?>
                                    </div>
                                <?php endif; ?>
                                <small class="form-text text-muted d-block mt-2">
                                    <i class="fas fa-info-circle"></i> Minimum 8 caractères
                                </small>
                            </div>

                            <div class="mb-4">
                                <label for="motdepasse_confirm" class="form-label fw-600">
                                    <i class="fas fa-lock"></i> Confirmer le mot de passe
                                </label>
                                <input
                                    type="password"
                                    class="form-control form-control-lg <?= !empty($erreurs['motdepasse_confirm']) ? 'is-invalid' : '' ?>"
                                    id="motdepasse_confirm"
                                    name="motdepasse_confirm"
                                    placeholder="••••••••"
                                    required>
                                <?php if (!empty($erreurs['motdepasse_confirm'])): ?>
                                    <div class="invalid-feedback d-block">
                                        <?= e($erreurs['motdepasse_confirm'][0]) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <button type="submit" class="btn btn-success btn-lg w-100 mb-3" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border: none;">
                                <i class="fas fa-user-plus"></i> S'inscrire
                            </button>
                        </form>

                        <div class="text-center border-top pt-3">
                            <p class="mb-0 text-muted">
                                Déjà inscrit?
                                <a href="/connexion" class="text-decoration-none fw-600" style="color: #11998e;">
                                    Se connecter
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
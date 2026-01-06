<?php

/** @var array $erreurs */
$erreurs = $erreurs ?? [];
$anciens_inputs = $anciens_inputs ?? [];

?>

<style>
    .login-container {
        min-height: calc(100vh - 200px);
        display: flex;
        align-items: center;
    }

    .login-card {
        border: none;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        border-radius: 15px;
        overflow: hidden;
        transition: transform 0.3s ease;
    }

    .login-card:hover {
        transform: translateY(-5px);
    }

    .login-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 40px 20px;
        text-align: center;
    }

    .login-header h3 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .form-control-lg {
        padding: 12px 15px;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        font-size: 16px;
    }

    .form-control-lg:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .btn-login {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 12px 20px;
        font-weight: 600;
        border-radius: 8px;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
    }
</style>

<div class="login-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-6">
                <div class="card login-card">
                    <div class="login-header">
                        <h3 class="mb-2">
                            <i class="fas fa-lock"></i> Connexion
                        </h3>
                        <p class="text-white mb-0" style="font-size: 14px; opacity: 0.9;">Accédez à votre compte</p>
                    </div>
                    <div class="card-body p-5">
                        <?php if (!empty($erreurs['general'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                <i class="fas fa-exclamation-triangle"></i>
                                <?php foreach ($erreurs['general'] as $erreur): ?>
                                    <div><?= e($erreur) ?></div>
                                <?php endforeach; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="/login" class="needs-validation">
                            <?= csrf_input() ?>

                            <div class="mb-4">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope"></i> Adresse email
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
                                        <i class="fas fa-times-circle"></i> <?= e($erreurs['email'][0]) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-4">
                                <label for="motdepasse" class="form-label">
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
                            </div>

                            <button type="submit" class="btn btn-login btn-lg w-100 mb-3">
                                <i class="fas fa-sign-in-alt"></i> Se connecter
                            </button>
                        </form>

                        <hr>

                        <div class="text-center">
                            <p class="mb-0 text-muted">
                                Pas encore inscrit?
                                <a href="/inscription" class="text-decoration-none fw-600" style="color: #667eea;">
                                    Créer un compte
                                </a>
                            </p>
                        </div>

                        <div class="mt-4 pt-3 border-top text-center">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt"></i> Connexion sécurisée
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Infos utilisateur de test -->
                <div class="mt-4 alert alert-info alert-dismissible fade show" role="alert">
                    <strong><i class="fas fa-info-circle"></i> Données de test</strong>
                    <hr>
                    Email: <code>admin@exemple.com</code>
                    <br>
                    Mot de passe: <code>admin123</code>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    </div>
</div>
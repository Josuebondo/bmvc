@extends('layouts.app')
@section('contenu')

<div class="container mt-5">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <!-- Bouton retour -->
            <a href="<?= url('photos') ?>" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Retour
            </a>

            <!-- Carte photo -->
            <div class="card shadow-lg">
                <!-- Image principale -->
                <div class="text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px;">
                    <img src="<?= echapper($photo['chemin_complet']) ?>"
                        alt="<?= echapper($photo['titre']) ?>"
                        class="img-fluid rounded"
                        style="max-height: 500px; object-fit: contain;">
                </div>

                <!-- Infos photo -->
                <div class="card-body">
                    <h2><?= echapper($photo['titre']) ?></h2>

                    <?php if (!empty($photo['description'])): ?>
                        <div class="mt-3">
                            <h5>Description</h5>
                            <p class="text-muted" style="white-space: pre-wrap;">
                                <?= echapper($photo['description']) ?>
                            </p>
                        </div>
                    <?php endif; ?>

                    <!-- Métadonnées -->
                    <hr>
                    <div class="row mt-3 text-muted">
                        <div class="col-md-6">
                            <p>
                                <strong><i class="far fa-calendar"></i> Date:</strong><br>
                                <?= date('d/m/Y à H:i:s', strtotime($photo['date_creation'])) ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p>
                                <strong><i class="fas fa-file-image"></i> Fichier:</strong><br>
                                <?= echapper($photo['fichier']) ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card-footer bg-light">
                    <div class="btn-group w-100" role="group">
                        <a href="<?= echapper($photo['chemin_complet']) ?>"
                            class="btn btn-info text-white"
                            download
                            title="Télécharger">
                            <i class="fas fa-download"></i> Télécharger
                        </a>
                        <a href="<?= url('photos/supprimer/' . $photo['id']) ?>"
                            class="btn btn-danger"
                            title="Supprimer"
                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette photo ?')">
                            <i class="fas fa-trash"></i> Supprimer
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
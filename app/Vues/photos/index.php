@extends('layouts.app')
@section('contenu')

<div class="container mt-5">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1><i class="fas fa-images"></i> Mes Photos</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="<?= url('photos/creer') ?>" class="btn btn-primary btn-lg">
                <i class="fas fa-plus-circle"></i> Nouvelle Photo
            </a>
        </div>
    </div>

    <?php if (empty($photos)): ?>
        <!-- Aucune photo -->
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="alert alert-info text-center p-5">
                    <i class="fas fa-image" style="font-size: 3rem; margin-bottom: 20px;"></i>
                    <h4>Aucune photo pour le moment</h4>
                    <p class="text-muted mb-3">Commencez par uploader votre première photo</p>
                    <a href="<?= url('photos/creer') ?>" class="btn btn-primary">
                        Uploader une photo
                    </a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Galerie de photos -->
        <div class="row g-4">
            <?php foreach ($photos as $photo): ?>
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 shadow-sm hover-effect" style="transition: transform 0.3s;">
                        <!-- Image -->
                        <div style="overflow: hidden; height: 250px;">
                            <img src="<?= echapper($photo['chemin_complet']) ?>"
                                class="card-img-top"
                                alt="<?= echapper($photo['titre']) ?>"
                                style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;"
                                onmouseover="this.style.transform='scale(1.05)'"
                                onmouseout="this.style.transform='scale(1)'">
                        </div>

                        <!-- Contenu -->
                        <div class="card-body">
                            <h5 class="card-title" title="<?= echapper($photo['titre']) ?>">
                                <?= echapper(substr($photo['titre'], 0, 25)) ?>
                                <?php if (strlen($photo['titre']) > 25): ?>...<?php endif; ?>
                            </h5>

                            <?php if (!empty($photo['description'])): ?>
                                <p class="card-text text-muted small">
                                    <?= echapper(substr($photo['description'], 0, 50)) ?>
                                    <?php if (strlen($photo['description']) > 50): ?>...<?php endif; ?>
                                </p>
                            <?php endif; ?>

                            <p class="card-text">
                                <small class="text-muted">
                                    <i class="far fa-calendar"></i>
                                    <?= date('d/m/Y à H:i', strtotime($photo['date_creation'])) ?>
                                </small>
                            </p>
                        </div>

                        <!-- Actions -->
                        <div class="card-footer bg-light border-top-0">
                            <div class="btn-group w-100" role="group">
                                <a href="<?= url('photos/voir/' . $photo['id']) ?>"
                                    class="btn btn-sm btn-info text-white"
                                    title="Voir">
                                    <i class="fas fa-eye"></i> Voir
                                </a>
                                <a href="<?= url('photos/supprimer/' . $photo['id']) ?>"
                                    class="btn btn-sm btn-danger"
                                    title="Supprimer"
                                    onclick="return confirm('Confirmer la suppression ?')">
                                    <i class="fas fa-trash"></i> Supprimer
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Stats -->
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="alert alert-secondary" role="alert">
                    <i class="fas fa-info-circle"></i>
                    Vous avez <strong><?= count($photos) ?></strong>
                    photo<?= count($photos) > 1 ? 's' : '' ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
    .hover-effect:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15) !important;
    }
</style>

@endsection
@extends('layouts.app')
@section('contenu')

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">📸 Uploader une Photo</h3>
                </div>

                <div class="card-body">
                    <form id="uploadForm" enctype="multipart/form-data">
                        <!-- Protection CSRF -->
                        <?= csrf_input() ?>

                        <!-- Titre -->
                        <div class="form-group mb-3">
                            <label for="titre" class="form-label">
                                <strong>Titre de la photo</strong>
                            </label>
                            <input type="text"
                                id="titre"
                                name="titre"
                                class="form-control"
                                placeholder="Ex: Mon voyage en Afrique"
                                required>
                            <small class="form-text text-muted">Minimum 3 caractères</small>
                        </div>

                        <!-- Description -->
                        <div class="form-group mb-3">
                            <label for="description" class="form-label">
                                <strong>Description (optionnel)</strong>
                            </label>
                            <textarea id="description"
                                name="description"
                                class="form-control"
                                rows="3"
                                placeholder="Décrivez votre photo..."
                                maxlength="1000"></textarea>
                            <small class="form-text text-muted">Maximum 1000 caractères</small>
                        </div>

                        <!-- Upload Photo -->
                        <div class="form-group mb-4">
                            <label for="photo" class="form-label">
                                <strong>Photo</strong>
                            </label>
                            <div class="input-group">
                                <input type="file"
                                    id="photo"
                                    name="photo"
                                    class="form-control"
                                    accept="image/jpeg,image/png,image/gif"
                                    required>
                                <span class="input-group-text">JPG, PNG, GIF</span>
                            </div>
                            <small class="form-text text-muted d-block mt-2">
                                ✓ Formats: JPG, PNG, GIF | ✓ Taille max: 5 MB
                            </small>

                            <!-- Aperçu de l'image -->
                            <div id="previewContainer" class="mt-3" style="display: none;">
                                <img id="preview" src="" alt="Aperçu" class="img-thumbnail" style="max-width: 300px;">
                            </div>
                        </div>

                        <!-- Messages de statut -->
                        <div id="alertContainer"></div>

                        <!-- Boutons -->
                        <div class="d-flex gap-2">
                            <button type="submit" id="submitBtn" class="btn btn-primary btn-lg">
                                <i class="fas fa-cloud-upload-alt"></i> Uploader
                            </button>
                            <a href="<?= url('photos') ?>" class="btn btn-secondary btn-lg">
                                Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Aperçu de l'image
        document.getElementById('photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('preview').src = event.target.result;
                    document.getElementById('previewContainer').style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });

        // Soumission du formulaire
        document.getElementById('uploadForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitBtn = document.getElementById('submitBtn');
            const alertContainer = document.getElementById('alertContainer');

            // Désactiver le bouton
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Upload en cours...';

            try {
                console.log('Envoi du formulaire vers:', '<?= url("photos/sauvegarder") ?>');

                // Point d'API et inclusion des cookies (session/CSRF)
                const endpoint = '<?= url("photos/sauvegarder") ?>';
                const response = await fetch(endpoint, {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                });

                console.log('Réponse reçue:', response.status, response.statusText);

                const responseText = await response.text();
                console.log('Texte brut:', responseText);

                let data;
                try {
                    data = JSON.parse(responseText);
                    console.log('JSON parsé:', data);
                } catch (parseError) {
                    console.error('Erreur de parsing JSON:', parseError);
                    console.error('Texte reçu:', responseText);
                    alertContainer.innerHTML = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> Erreur: réponse invalide du serveur
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-cloud-upload-alt"></i> Uploader';
                    return;
                }

                if (response.ok && data.success) {
                    alertContainer.innerHTML = `
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> ${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;

                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 2000);
                } else {
                    const message = data.erreur || data.message || 'Une erreur est survenue';
                    alertContainer.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle"></i> ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-cloud-upload-alt"></i> Uploader';
                }
            } catch (error) {
                console.error('Erreur fetch:', error);
                console.error('URL appelée:', '<?= url("photos/sauvegarder") ?>');
                alertContainer.innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> Erreur lors de l'upload: ${error.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-cloud-upload-alt"></i> Uploader';
            }
        });
    });
</script>

<style>
    #preview {
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .card {
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
</style>

@endsection
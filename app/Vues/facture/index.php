<?php
section('titre', 'Liste des facture');
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>facture - Liste</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="/creer" class="btn btn-primary">Ajouter +</a>
        </div>
    </div>

    <?php if (!empty($items)): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Nom</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= e($item['id']) ?></td>
                        <td><?= e($item['nom'] ?? '') ?></td>
                        <td>
                            <a href="/editer/<?= $item['id'] ?>" class="btn btn-sm btn-info">Éditer</a>
                            <a href="/supprimer/<?= $item['id'] ?>" class="btn btn-sm btn-danger" 
                               onclick="return confirm('Êtes-vous sûr?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">Aucun élément trouvé</div>
    <?php endif; ?>
</div>
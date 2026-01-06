<?php etendre('layouts.app'); ?>

<?php debut_section('contenu'); ?>
<?php $erreurs = $erreurs ?? []; ?>

<h2>Nous contacter</h2>

<div id="message-reponse" style="margin-bottom: 20px; display: none; padding: 15px; border-radius: 4px;"></div>

<form id="contact-form" style="max-width: 600px;">
    <div style="margin-bottom: 20px;">
        <label for="nom" style="display: block; margin-bottom: 5px; font-weight: 500;">Nom</label>
        <input type="text" name="nom" id="nom" value="<?php echo ancien('nom', ''); ?>" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
        <span class="erreur-nom" style="color: #e74c3c; font-size: 0.9em; margin-top: 5px; display: none;"></span>
    </div>

    <div style="margin-bottom: 20px;">
        <label for="email" style="display: block; margin-bottom: 5px; font-weight: 500;">Email</label>
        <input type="email" name="email" id="email" value="<?php echo ancien('email', ''); ?>" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
        <span class="erreur-email" style="color: #e74c3c; font-size: 0.9em; margin-top: 5px; display: none;"></span>
    </div>

    <div style="margin-bottom: 20px;">
        <label for="message" style="display: block; margin-bottom: 5px; font-weight: 500;">Message</label>
        <textarea name="message" id="message" rows="5" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;"><?php echo ancien('message', ''); ?></textarea>
        <span class="erreur-message" style="color: #e74c3c; font-size: 0.9em; margin-top: 5px; display: none;"></span>
    </div>

    <button type="submit" style="background: #667eea; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: 500;">Envoyer</button>
</form>

<script>
    document.getElementById('contact-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const messageDiv = document.getElementById('message-reponse');

        // Réinitialiser les erreurs
        document.querySelectorAll('.erreur-nom, .erreur-email, .erreur-message').forEach(el => {
            el.style.display = 'none';
            el.textContent = '';
        });

        fetch('/contact', {
                method: 'POST',
                body: new URLSearchParams(formData)
            })
            .then(response => {
                console.log(response.text())
                return response.json().then(data => ({
                    status: response.status,
                    ok: response.ok,
                    data: data
                }));
            })
            .then(({
                status,
                ok,
                data
            }) => {
                console.log('Réponse:', {
                    status,
                    ok,
                    data
                });

                if (ok && data.succes) {
                    messageDiv.style.background = '#d4edda';
                    messageDiv.style.color = '#155724';
                    messageDiv.textContent = '✓ Message envoyé avec succès!';
                    messageDiv.style.display = 'block';
                    document.getElementById('contact-form').reset();
                } else if (data.erreurs) {
                    // Afficher les erreurs spécifiques
                    for (let champ in data.erreurs) {
                        const spanErreur = document.querySelector('.erreur-' + champ);
                        if (spanErreur) {
                            spanErreur.textContent = data.erreurs[champ][0];
                            spanErreur.style.display = 'block';
                        }
                    }
                    messageDiv.style.background = '#f8d7da';
                    messageDiv.style.color = '#721c24';
                    messageDiv.textContent = '✗ Veuillez corriger les erreurs ci-dessus';
                    messageDiv.style.display = 'block';
                } else {
                    messageDiv.style.background = '#f8d7da';
                    messageDiv.style.color = '#721c24';
                    messageDiv.textContent = '✗ Erreur lors de l\'envoi';
                    messageDiv.style.display = 'block';
                }
            })
            .catch(err => {
                console.error('Erreur fetch:', err);
                messageDiv.style.background = '#f8d7da';
                messageDiv.style.color = '#721c24';
                messageDiv.textContent = '✗ Erreur réseau: ' + err.message;
                messageDiv.style.display = 'block';
            });
    });
</script>
<?php fin_section('contenu'); ?>
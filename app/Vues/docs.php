<?php
etendre('layouts.app');
?>

<?php $this->debut_section('contenu'); ?>
<h2>📖 Documentation BMVC - Phase 2</h2>

<h3>🔧 Configuration du Routage</h3>
<p>Définissez vos routes dans <code>routes/web.php</code> :</p>
<pre style="background: #f5f5f5; padding: 15px; border-radius: 5px; overflow-x: auto;"><code>
Routeur::obtenir('/', 'PageControleur@accueil');
Routeur::publier('/contact', 'ContactControleur@envoyer');
Routeur::obtenir('/utilisateur/{id}', 'UtilisateurControleur@voir');
    </code></pre>

<h3>📝 Création de Vues</h3>
<p>Utilisez les layouts et sections :</p>
<pre style="background: #f5f5f5; padding: 15px; border-radius: 5px; overflow-x: auto;"><code>
&lt;?php $this->extends('layouts.app'); ?&gt;

&lt;?php $this->debut_section('contenu'); ?&gt;
    &lt;h1&gt;Mon Contenu&lt;/h1&gt;
&lt;?php $this->fin_section('contenu'); ?&gt;
    </code></pre>

<h3>🎮 Création de Contrôleurs</h3>
<p>Étendez <code>BaseControleur</code> :</p>
<pre style="background: #f5f5f5; padding: 15px; border-radius: 5px; overflow-x: auto;"><code>
class MonControleur extends BaseControleur {
    public function ma_methode() {
        return $this->afficher('ma-vue', [
            'titre' => 'Mon titre'
        ]);
    }
}
    </code></pre>

<h3>🔒 Protection XSS</h3>
<p>Utilisez <code>Vue::echapper()</code> ou <code>Vue::e()</code> :</p>
<pre style="background: #f5f5f5; padding: 15px; border-radius: 5px; overflow-x: auto;"><code>
&lt;p&gt;&lt;?php echo Vue::e($variable); ?&gt;&lt;/p&gt;
    </code></pre>

<h3>📦 Groupes de Routes</h3>
<pre style="background: #f5f5f5; padding: 15px; border-radius: 5px; overflow-x: auto;"><code>
Routeur::groupe(['prefixe' => 'api'], function() {
    Routeur::obtenir('/utilisateurs', 'APIControleur@utilisateurs');
    Routeur::obtenir('/utilisateurs/{id}', 'APIControleur@utilisateur');
});
    </code></pre>
<?php $this->fin_section('contenu'); ?>
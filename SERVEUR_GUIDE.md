# 🚀 Guide du Serveur BMVC

**Comment démarrer, arrêter et redémarrer le serveur de développement**

---

## 🎯 Démarrer le Serveur

### Commande de Base

```bash
php bmvc demarrer
```

Cela démarre le serveur sur le **port 8000 par défaut**:

```
✓ 🚀 Serveur BMVC démarré!
👉 http://localhost:8000
Appuyez sur Ctrl+C pour arrêter
```

### Avec un Port Personnalisé

```bash
# Utiliser le port 9000
php bmvc demarrer --port=9000

# Ou format court
php bmvc demarrer -p 9000
```

---

## 🛑 Arrêter le Serveur

### Méthode 1: Ctrl+C (Recommandée)

**Dans le terminal où le serveur tourne:**

1. Maintenez **Ctrl** et appuyez sur **C**
2. Le serveur s'arrêtera gracieusement

```
^C
✓ Serveur arrêté
```

### Méthode 2: Tuer le Processus (Si Ctrl+C ne marche pas)

**Dans une autre fenêtre terminal:**

```bash
# Windows - Trouver le processus PHP
netstat -ano | findstr :8000

# Puis tuer le processus (remplacer PID)
taskkill /PID 12345 /F
```

**Linux/Mac:**

```bash
# Trouver le processus
lsof -i :8000

# Tuer le processus
kill -9 PID
```

### Méthode 3: Arrêt Automatique

Le serveur s'arrête automatiquement quand vous:

- Fermez le terminal
- Quittez la session SSH
- Redémarrez l'ordinateur

---

## 🔄 Redémarrer le Serveur

### Procédure Complète

**Étape 1: Arrêter le serveur actuel**

```bash
# Dans le terminal où tourne le serveur
Ctrl+C
```

**Étape 2: Relancer le serveur**

```bash
php bmvc demarrer
```

### Raccourci Rapide

```bash
# Une seule ligne pour redémarrer
php bmvc demarrer && echo "Serveur redémarré"
```

### Redémarrer sur un Port Différent

```bash
# Arrêter Ctrl+C
# Puis relancer sur un autre port
php bmvc demarrer --port=9000
```

---

## 💡 Cas d'Utilisation Courants

### Problème: Le Port 8000 est Déjà Occupé

```bash
# Essayez un autre port
php bmvc demarrer --port=8001
php bmvc demarrer --port=8080
php bmvc demarrer --port=3000
```

### Problème: Les Changements ne Sont Pas Appliqués

```bash
# Arrêtez le serveur
Ctrl+C

# Redémarrez-le
php bmvc demarrer

# Rechargez le navigateur (Ctrl+Shift+R pour hard refresh)
```

### Problème: Le Serveur Reste Bloqué

```bash
# Tuer le processus PHP
# Windows
taskkill /IM php.exe /F

# Linux/Mac
killall php

# Puis redémarrer
php bmvc demarrer
```

---

## 🔍 Vérifier l'État du Serveur

### Vérifier que le Serveur Est Actif

**Dans le navigateur:**

```
http://localhost:8000
```

**Ou via curl:**

```bash
curl http://localhost:8000
```

### Vérifier le Port Utilisé

**Windows:**

```bash
netstat -ano | findstr :8000
```

**Linux/Mac:**

```bash
lsof -i :8000
```

---

## 📊 Ports Courants

| Port | Usage       | Commande                        |
| ---- | ----------- | ------------------------------- |
| 8000 | Défaut      | `php bmvc demarrer`             |
| 8001 | Alternative | `php bmvc demarrer --port=8001` |
| 8080 | Alternative | `php bmvc demarrer --port=8080` |
| 3000 | Alternative | `php bmvc demarrer --port=3000` |
| 5000 | Alternative | `php bmvc demarrer --port=5000` |

---

## ⚙️ Configuration Avancée

### Variables d'Environnement

```bash
# Définir le port via variable
export SERVER_PORT=9000
php bmvc demarrer

# Windows
set SERVER_PORT=9000
php bmvc demarrer
```

### Serveur en Arrière-Plan (Linux/Mac)

```bash
# Démarrer en arrière-plan
php bmvc demarrer &

# Voir les processus
jobs

# Arrêter le dernier processus
kill %1
```

### Serveur en Arrière-Plan (Windows)

```bash
# Démarrer dans une nouvelle fenêtre
start php bmvc demarrer

# Ou avec nom personnalisé
start "BMVC Server" php bmvc demarrer
```

---

## 🐛 Dépannage

### Le Serveur ne Démarre Pas

```
Erreur: Permission denied ou Address already in use
```

**Solution 1: Port occupé**

```bash
# Essayez un autre port
php bmvc demarrer --port=8001
```

**Solution 2: Permission**

```bash
# Linux/Mac
sudo php bmvc demarrer

# Ou changez les permissions
chmod 755 public/
```

### Le Serveur Se Ferme Immédiatement

```bash
# Vérifiez les erreurs PHP
php -S localhost:8000 -t public/

# Ou exécutez un test
php bmvc aide
```

### Les Fichiers ne Se Rechargent pas

```bash
# Ctrl+C pour arrêter
# Hard refresh dans le navigateur (Ctrl+Shift+R)
# Redémarrez le serveur
php bmvc demarrer

# Ou videz le cache
rm -rf cache/*  # Linux/Mac
rmdir /S cache  # Windows
```

---

## 📝 Résumé Rapide

| Action            | Commande                            |
| ----------------- | ----------------------------------- |
| Démarrer          | `php bmvc demarrer`                 |
| Arrêter           | `Ctrl+C`                            |
| Redémarrer        | `Ctrl+C` puis `php bmvc demarrer`   |
| Port 9000         | `php bmvc demarrer --port=9000`     |
| Vérifier          | `curl http://localhost:8000`        |
| Tuer le processus | `taskkill /IM php.exe /F` (Windows) |

---

## 🎯 Bonnes Pratiques

✅ **À Faire:**

- Toujours arrêter proprement avec Ctrl+C
- Vérifier les logs après un redémarrage
- Utiliser des ports différents pour plusieurs serveurs
- Recharger le navigateur après un redémarrage (Ctrl+Shift+R)

❌ **À Éviter:**

- Forcer la fermeture du terminal (mauvaise fermeture)
- Laisser plusieurs serveurs actifs sur le même port
- Redémarrer le serveur à chaque modification mineure

---

**🚀 Serveur BMVC - Guide Complet**

**Version:** 1.0.0  
**Last Updated:** 2024-01-06

**Bon développement!** 💻

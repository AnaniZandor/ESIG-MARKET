# 🛒 ESIG-MARKET

Plateforme d'échange et de vente entre étudiants de l'ESIG.

![Version](https://img.shields.io/badge/version-1.0.0-blue)
![Laravel](https://img.shields.io/badge/Laravel-10.x-red)
![PHP](https://img.shields.io/badge/PHP-8.1+-purple)

## 📋 Description

ESIG-MARKET permet aux étudiants de :
- Publier des annonces de vente ou d'échange
- Acheter et vendre entre étudiants
- Donner des avis sur les vendeurs (⭐)
- Gérer leurs favoris
- Bénéficier d'un espace d'administration

## ✨ Fonctionnalités

| Module | Fonctionnalités |
|--------|-----------------|
| **Utilisateurs** | Inscription, connexion, profil, favoris, notifications |
| **Articles** | CRUD complet, recherche, filtres, signalements, upload images |
| **Avis** | Notation 1-5⭐, commentaires, système de reviews |
| **Admin** | Dashboard, gestion users, gestion articles, modération |

## 🛠️ Technologies

- **Backend** : Laravel 10, PHP 8.1+
- **Frontend** : Blade, Bootstrap/Tailwind
- **Base de données** : MySQL
- **Outils** : Git, Composer, NPM

## 📦 Installation

```bash
# 1. Cloner le projet
git clone https://github.com/AnaniZandor/ESIG-MARKET.git
cd ESIG-MARKET

# 2. Installer les dépendances
composer install
npm install

# 3. Configurer l'environnement
cp .env.example .env
php artisan key:generate

# 4. Configurer la base de données (.env)
# DB_DATABASE=esig_market
# DB_USERNAME=root
# DB_PASSWORD=

# 5. Lancer les migrations
php artisan migrate --seed

# 6. Lien pour les images
php artisan storage:link

# 7. Compiler les assets
npm run build

# 8. Lancer le serveur
php artisan serve


## 🔐 Comptes de test

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| 🔴 **Admin** | admin@esig.com | password |
| 🔵 **Utilisateur** | user@esig.com | password |

## 📁 Structure du projet
app/Http/Controllers/
├── ArticleController.php # CRUD articles
├── FavoriteController.php # Gestion favoris
├── ReportController.php # Signalements
├── ReviewController.php # Avis et notations
└── Admin/
├── AdminController.php # Dashboard admin
├── ArticleController.php # Modération articles
└── UserController.php # Gestion utilisateurs


## 🚀 Routes principales

| Route | Méthode | Description |
|-------|---------|-------------|
| `/articles` | GET | Liste des articles |
| `/articles/create` | GET | Publier une annonce |
| `/favorites` | GET | Mes favoris |
| `/admin/dashboard` | GET | Dashboard admin |
| `/admin/users` | GET | Gérer utilisateurs |
| `/admin/articles` | GET | Modérer articles |

## 📝 Guide utilisateur rapide

1. **S'inscrire** → `/register`
2. **Publier** → Bouton "Publier une annonce"
3. **Favoris** → Cliquer sur ❤️
4. **Noter** → Après achat, aller sur le profil du vendeur
5. **Admin** → Accès dashboard pour modération

## 🐛 Dépannage

| Problème | Solution |
|----------|----------|
| 403 Forbidden | Vérifier que l'utilisateur est connecté |
| Images non chargées | `php artisan storage:link` |
| Erreur de session | `php artisan cache:clear` |
| Port déjà utilisé | `php artisan serve --port=8080` |

## 📈 Fonctionnalités à venir

- [ ] Messagerie entre utilisateurs
- [ ] Système de paiement Mobile Money
- [ ] Notifications par email
- [ ] API REST pour mobile

## 👤 Auteur

**Anani Zandor** - Étudiant ESIG

## 📄 Licence

Projet académique - ESIG (École Supérieure d'Informatique)

---

⭐ **N'oubliez pas de laisser une étoile sur GitHub !**
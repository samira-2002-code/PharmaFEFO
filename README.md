# PharmaFEFO — Gestion de stock pharmaceutique (FEFO)

Projet académique PHP 8 MVC sans framework.

## Stack technique
- PHP 8 (OOP, namespaces, types stricts)
- MySQL + PDO
- TailwindCSS (CDN)
- Architecture MVC maison

## Installation

### 1. Base de données
```sql
-- Importer le script SQL :
mysql -u root -p < database.sql
```

### 2. Configuration
Modifier `config/database.php` avec vos identifiants MySQL :
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'pharmafefo');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### 3. Lancer le serveur
```bash
cd pharmafefo/public
php -S localhost:8000
```
Accéder à : http://localhost:8000

---

## Structure du projet

```
pharmafefo/
├── config/
│   └── database.php          ← Connexion PDO
├── public/
│   └── index.php             ← Routeur principal
├── src/
│   ├── Controller/
│   │   ├── DashboardController.php
│   │   ├── LotController.php
│   │   └── RapportController.php
│   ├── Entity/
│   │   ├── Product.php
│   │   └── StockBatch.php
│   └── Repository/
│       ├── ProductRepository.php
│       └── StockBatchRepository.php
├── templates/
│   ├── layouts/
│   │   ├── header.php
│   │   ├── footer.php
│   │   └── _alert_badge.php
│   ├── dashboard/
│   │   └── index.php
│   ├── lots/
│   │   ├── index.php
│   │   └── form.php
│   └── rapport/
│       └── index.php
└── database.sql
```

---

## Fonctionnalités

| Fonctionnalité | Description |
|---|---|
| **Dashboard** | Compteurs totaux, alertes orange/rouge, tableau FEFO |
| **Gestion des lots** | Ajouter, modifier, supprimer un lot |
| **Alertes péremption** | Vert > 90j / Orange < 90j / Rouge < 30j / Expiré |
| **Sortie FEFO** | Sélection automatique du lot le plus proche de péremption |
| **Déclaration périmé** | Bouton « Déclarer périmé » → statut EXPIRED, quantité = 0 |
| **Rapport** | Nombre de lots expirés + valeur totale perdue en MAD |

---

## Logique FEFO

Lors d'une sortie de stock, la méthode `findFefoLot()` dans `StockBatchRepository`
sélectionne le lot **ACTIF** dont `expiry_date` est la plus proche et dont la quantité est > 0.

```php
ORDER BY sb.expiry_date ASC LIMIT 1
```

La quantité est ensuite décrémentée via `decrementQuantity()`.

---

## Niveaux d'alerte

| Couleur | Condition |
|---|---|
| 🟢 Vert | Plus de 90 jours |
| 🟠 Orange | Entre 30 et 90 jours |
| 🔴 Rouge | Moins de 30 jours |
| ⬛ Gris | Expiré (date dépassée ou statut EXPIRED) |

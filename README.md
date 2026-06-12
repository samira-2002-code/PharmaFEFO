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

La quantité est ensuite décrémentée via `decrementQuantity()`
<img width="460" height="319" alt="Screenshot 2026-06-12 165034" src="https://github.com/user-attachments/assets/488a0e8a-7d92-4deb-a769-ac10b48b7218" />

<img width="587" height="413" alt="usecase" src="https://github.com/user-attachments/assets/9a2d2531-c22f-49f4-922e-b6d07d4c73b5" />

<img width="1121" height="614" alt="image" src="https://github.com/user-attachments/assets/69884f50-9daf-4b4e-b11e-828427573fa9" />





---



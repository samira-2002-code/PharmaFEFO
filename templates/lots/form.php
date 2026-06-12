<?php
$isEdit    = isset($lot);
$pageTitle = $isEdit ? 'Modifier un lot' : 'Ajouter un lot';
require __DIR__ . '/../layouts/header.php';
?>

<div class="max-w-xl">

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-slate-400 mb-5">
        <a href="index.php?page=lots" class="hover:text-blue-600 transition-colors">Gestion des lots</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-slate-600"><?= $isEdit ? 'Modifier' : 'Nouveau lot' ?></span>
    </nav>

    <!-- Erreurs de validation -->
    <?php if (!empty($errors)): ?>
        <div class="mb-5 bg-red-50 border border-red-200 rounded-lg px-4 py-3">
            <p class="text-sm font-semibold text-red-700 mb-1">Veuillez corriger les erreurs suivantes :</p>
            <ul class="list-disc list-inside text-sm text-red-600">
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Formulaire -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-lg font-semibold text-slate-800 mb-5">
            <?= $isEdit ? 'Modifier le lot #' . htmlspecialchars($lot->batchNumber) : 'Enregistrer un nouveau lot' ?>
        </h2>

        <form method="post"
              action="index.php?page=lots&action=<?= $isEdit ? 'update&id=' . $lot->id : 'store' ?>"
              class="flex flex-col gap-4">

            <!-- Produit -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1" for="product_id">
                    Produit <span class="text-red-500">*</span>
                </label>
                <select name="product_id" id="product_id" required
                    class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                    <option value="">-- Sélectionner un produit --</option>
                    <?php foreach ($products as $p): ?>
                        <option value="<?= $p->id ?>"
                            <?= ($isEdit && $lot->productId === $p->id) || (isset($_POST['product_id']) && (int)$_POST['product_id'] === $p->id) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p->name) ?> (<?= htmlspecialchars($p->reference) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Numéro de lot -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1" for="batch_number">
                    Numéro de lot <span class="text-red-500">*</span>
                </label>
                <input type="text" name="batch_number" id="batch_number" required
                    placeholder="Ex: LOT-2024-001"
                    value="<?= htmlspecialchars($isEdit ? $lot->batchNumber : ($_POST['batch_number'] ?? '')) ?>"
                    class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none font-mono" />
            </div>

            <!-- Quantité -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1" for="quantity">
                    Quantité <span class="text-red-500">*</span>
                </label>
                <input type="number" name="quantity" id="quantity" required min="0"
                    placeholder="0"
                    value="<?= htmlspecialchars($isEdit ? $lot->quantity : ($_POST['quantity'] ?? '')) ?>"
                    class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" />
            </div>

            <!-- Date de péremption -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1" for="expiry_date">
                    Date de péremption <span class="text-red-500">*</span>
                </label>
                <input type="date" name="expiry_date" id="expiry_date" required
                    value="<?= htmlspecialchars($isEdit ? $lot->expiryDate : ($_POST['expiry_date'] ?? '')) ?>"
                    class="w-full text-sm border border-slate-300 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" />
                <p class="mt-1 text-xs text-slate-400">La méthode FEFO priorisera les lots dont la date est la plus proche.</p>
            </div>

            <!-- Boutons -->
            <div class="flex items-center gap-3 pt-2 border-t border-slate-100 mt-1">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                    <?= $isEdit ? 'Enregistrer les modifications' : 'Ajouter le lot' ?>
                </button>
                <a href="index.php?page=lots"
                    class="text-sm text-slate-500 hover:text-slate-700 px-4 py-2.5 rounded-lg hover:bg-slate-100 transition-colors">
                    Annuler
                </a>
            </div>

        </form>
    </div>

    <!-- Info FEFO -->
    <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg px-4 py-3 text-sm text-blue-700">
        <strong>Méthode FEFO :</strong> lors d'une sortie, le système sélectionne automatiquement le lot dont la date de péremption est la plus proche, afin de minimiser les pertes.
    </div>

</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>

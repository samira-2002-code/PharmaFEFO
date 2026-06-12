<?php
$pageTitle = 'Gestion des lots';
require __DIR__ . '/../layouts/header.php';

// Message flash
$success = $_GET['success'] ?? '';
$error   = $_GET['error']   ?? '';
$lotName = $_GET['lot']     ?? '';
$messages = [
    'created'          => ['type' => 'green',  'text' => 'Lot ajouté avec succès.'],
    'updated'          => ['type' => 'green',  'text' => 'Lot modifié avec succès.'],
    'deleted'          => ['type' => 'green',  'text' => 'Lot supprimé.'],
    'expired'          => ['type' => 'slate',  'text' => 'Lot déclaré périmé.'],
    'fefo_out'         => ['type' => 'green',  'text' => 'Sortie FEFO effectuée sur le lot ' . htmlspecialchars($lotName) . '.'],
];
$errors = [
    'no_fefo_lot'       => 'Aucun lot valide disponible pour ce produit.',
    'insufficient_stock'=> 'Quantité demandée supérieure au stock disponible.',
];
?>

<?php if ($success && isset($messages[$success])): $m = $messages[$success]; ?>
    <div class="mb-4 bg-<?= $m['type'] === 'green' ? 'emerald' : 'slate' ?>-50 border border-<?= $m['type'] === 'green' ? 'emerald' : 'slate' ?>-200 text-<?= $m['type'] === 'green' ? 'emerald' : 'slate' ?>-700 px-4 py-3 rounded-lg text-sm">
        ✓ <?= $m['text'] ?>
    </div>
<?php endif; ?>

<?php if ($error && isset($errors[$error])): ?>
    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
        ✕ <?= $errors[$error] ?>
    </div>
<?php endif; ?>

<!-- En-tête actions -->
<div class="flex flex-wrap items-center justify-between gap-3 mb-4">
    <a href="index.php?page=lots&action=create"
        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Nouveau lot
    </a>

    <!-- Formulaire sortie FEFO -->
    <form method="post" action="index.php?page=lots&action=fefo"
        class="flex flex-wrap items-center gap-2 bg-white border border-slate-200 rounded-lg px-3 py-2 shadow-sm">
        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Sortie FEFO</span>
        <select name="product_id" required
            class="text-sm border border-slate-300 rounded-md px-2 py-1.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            <option value="">-- Produit --</option>
            <?php foreach ($products as $p): ?>
                <option value="<?= $p->id ?>"><?= htmlspecialchars($p->name) ?></option>
            <?php endforeach; ?>
        </select>
        <input type="number" name="quantity" min="1" placeholder="Qté" required
            class="w-20 text-sm border border-slate-300 rounded-md px-2 py-1.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" />
        <button type="submit"
            class="bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium px-3 py-1.5 rounded-md transition-colors">
            Confirmer
        </button>
    </form>
</div>

<!-- Tableau des lots -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="px-5 py-3 border-b border-slate-100">
        <h2 class="font-semibold text-slate-800">Tous les lots <span class="text-slate-400 font-normal text-sm">(triés FEFO : péremption proche en premier)</span></h2>
    </div>

    <?php if (empty($lots)): ?>
        <div class="text-center py-12 text-slate-400">Aucun lot enregistré.</div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wide">
                        <th class="text-left px-5 py-3 font-semibold">Produit</th>
                        <th class="text-left px-5 py-3 font-semibold">N° Lot</th>
                        <th class="text-left px-5 py-3 font-semibold">Qté</th>
                        <th class="text-left px-5 py-3 font-semibold">Péremption</th>
                        <th class="text-left px-5 py-3 font-semibold">Statut</th>
                        <th class="text-left px-5 py-3 font-semibold">Alerte</th>
                        <th class="text-right px-5 py-3 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($lots as $lot): ?>
                        <tr class="hover:bg-slate-50 transition-colors <?= $lot->status === 'EXPIRED' ? 'opacity-60' : '' ?>">
                            <td class="px-5 py-3">
                                <span class="font-medium text-slate-800"><?= htmlspecialchars($lot->productName) ?></span>
                                <span class="block text-xs text-slate-400"><?= htmlspecialchars($lot->productReference) ?></span>
                            </td>
                            <td class="px-5 py-3 font-mono text-xs text-slate-600"><?= htmlspecialchars($lot->batchNumber) ?></td>
                            <td class="px-5 py-3 font-semibold"><?= $lot->quantity ?> <span class="text-xs font-normal text-slate-400"><?= htmlspecialchars($lot->unit) ?></span></td>
                            <td class="px-5 py-3 text-slate-600"><?= date('d/m/Y', strtotime($lot->expiryDate)) ?></td>
                            <td class="px-5 py-3">
                                <?php if ($lot->status === 'EXPIRED'): ?>
                                    <span class="text-xs font-semibold bg-slate-200 text-slate-600 px-2 py-1 rounded-full">PÉRIMÉ</span>
                                <?php else: ?>
                                    <span class="text-xs font-semibold bg-emerald-100 text-emerald-700 px-2 py-1 rounded-full">ACTIF</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-5 py-3"><?php include __DIR__ . '/../layouts/_alert_badge.php'; ?></td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Modifier -->
                                    <a href="index.php?page=lots&action=edit&id=<?= $lot->id ?>"
                                        class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded transition-colors"
                                        title="Modifier">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                    <!-- Déclarer périmé (si actif) -->
                                    <?php if ($lot->status === 'ACTIVE'): ?>
                                        <a href="index.php?page=lots&action=expire&id=<?= $lot->id ?>"
                                            onclick="return confirm('Déclarer ce lot comme périmé ? La quantité sera mise à 0.')"
                                            class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded transition-colors"
                                            title="Déclarer périmé">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                                            </svg>
                                        </a>
                                    <?php endif; ?>

                                    <!-- Supprimer -->
                                    <a href="index.php?page=lots&action=delete&id=<?= $lot->id ?>"
                                        onclick="return confirm('Supprimer ce lot définitivement ?')"
                                        class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors"
                                        title="Supprimer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>

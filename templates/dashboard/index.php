<?php
$pageTitle = 'Tableau de bord';
require __DIR__ . '/../layouts/header.php';
?>

<!-- Cartes de statistiques -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

    <!-- Total lots -->
    <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200 flex items-center gap-4">
        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center shrink-0">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-900"><?= $totalLots ?></p>
            <p class="text-sm text-slate-500">Lots en stock</p>
        </div>
    </div>

    <!-- Alertes orange -->
    <div class="bg-white rounded-xl p-5 shadow-sm border border-amber-200 flex items-center gap-4">
        <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center shrink-0">
            <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-amber-600"><?= $orangeAlerts ?></p>
            <p class="text-sm text-slate-500">Alertes orange <span class="text-xs text-amber-500">(&lt; 90 j)</span></p>
        </div>
    </div>

    <!-- Alertes rouge -->
    <div class="bg-white rounded-xl p-5 shadow-sm border border-red-200 flex items-center gap-4">
        <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center shrink-0">
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-red-600"><?= $redAlerts ?></p>
            <p class="text-sm text-slate-500">Alertes rouges <span class="text-xs text-red-500">(&lt; 30 j)</span></p>
        </div>
    </div>

</div>


<!-- Légende des alertes -->
<div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200 mb-6">
    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Légende des alertes de péremption</p>
    <div class="flex flex-wrap gap-3">
        <span class="inline-flex items-center gap-2 text-sm text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-lg">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Vert : plus de 90 jours
        </span>
        <span class="inline-flex items-center gap-2 text-sm text-amber-700 bg-amber-50 px-3 py-1.5 rounded-lg">
            <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Orange : moins de 90 jours
        </span>
        <span class="inline-flex items-center gap-2 text-sm text-red-700 bg-red-50 px-3 py-1.5 rounded-lg">
            <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span> Rouge : moins de 30 jours
        </span>
        <span class="inline-flex items-center gap-2 text-sm text-slate-600 bg-slate-100 px-3 py-1.5 rounded-lg">
            <span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span> Gris : expiré
        </span>
    </div>
</div>

<!-- Tableau des lots -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
        <h2 class="font-semibold text-slate-800">État du stock (méthode FEFO)</h2>
        <a href="index.php?page=lots&action=create"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Ajouter un lot
        </a>
    </div>

    <?php if (empty($lots)): ?>
        <div class="text-center py-12 text-slate-400">
            <svg class="w-10 h-10 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            Aucun lot enregistré.
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wide">
                        <th class="text-left px-5 py-3 font-semibold">Produit</th>
                        <th class="text-left px-5 py-3 font-semibold">N° Lot</th>
                        <th class="text-left px-5 py-3 font-semibold">Quantité</th>
                        <th class="text-left px-5 py-3 font-semibold">Péremption</th>
                        <th class="text-left px-5 py-3 font-semibold">Alerte</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($lots as $lot): ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3">
                                <span class="font-medium text-slate-800"><?= htmlspecialchars($lot->productName) ?></span>
                                <span class="block text-xs text-slate-400"><?= htmlspecialchars($lot->productReference) ?></span>
                            </td>
                            <td class="px-5 py-3 text-slate-600 font-mono text-xs"><?= htmlspecialchars($lot->batchNumber) ?></td>
                            <td class="px-5 py-3 font-semibold"><?= $lot->quantity ?> <span class="text-xs font-normal text-slate-400"><?= htmlspecialchars($lot->unit) ?></span></td>
                            <td class="px-5 py-3 text-slate-600"><?= date('d/m/Y', strtotime($lot->expiryDate)) ?></td>
                            <td class="px-5 py-3"><?php include __DIR__ . '/../layouts/_alert_badge.php'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>

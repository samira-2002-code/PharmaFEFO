<?php
$pageTitle = 'Rapport des pertes';
require __DIR__ . '/../layouts/header.php';
?>

<!-- Résumé chiffré -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">

    <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200 flex items-center gap-4">
        <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center shrink-0">
            <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
        </div>
        <div>
            <p class="text-3xl font-bold text-slate-900"><?= $countExpired ?></p>
            <p class="text-sm text-slate-500">Lots expirés déclarés</p>
        </div>
    </div>

    <div class="bg-white rounded-xl p-5 shadow-sm border border-red-200 flex items-center gap-4">
        <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center shrink-0">
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div>
            <p class="text-3xl font-bold text-red-600"><?= number_format($totalLost, 2, ',', ' ') ?> MAD</p>
            <p class="text-sm text-slate-500">Valeur totale perdue</p>
        </div>
    </div>

</div>

<!-- Tableau des lots expirés -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100">
        <h2 class="font-semibold text-slate-800">Détail des lots expirés</h2>
        <p class="text-xs text-slate-400 mt-0.5">Lots dont le statut a été déclaré EXPIRÉ</p>
    </div>

    <?php if (empty($expiredLots)): ?>
        <div class="text-center py-12">
            <svg class="w-10 h-10 mx-auto mb-3 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-slate-400 text-sm">Aucun lot expiré. Bonne gestion !</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wide">
                        <th class="text-left px-5 py-3 font-semibold">Produit</th>
                        <th class="text-left px-5 py-3 font-semibold">N° Lot</th>
                        <th class="text-left px-5 py-3 font-semibold">Qté perdue</th>
                        <th class="text-left px-5 py-3 font-semibold">Prix unitaire</th>
                        <th class="text-left px-5 py-3 font-semibold">Date péremption</th>
                        <th class="text-right px-5 py-3 font-semibold">Valeur perdue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($expiredLots as $lot): ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-5 py-3">
                                <span class="font-medium text-slate-800"><?= htmlspecialchars($lot->productName) ?></span>
                                <span class="block text-xs text-slate-400"><?= htmlspecialchars($lot->productReference) ?></span>
                            </td>
                            <td class="px-5 py-3 font-mono text-xs text-slate-600"><?= htmlspecialchars($lot->batchNumber) ?></td>
                            <td class="px-5 py-3">
                                <span class="text-slate-500 line-through text-xs">qté initiale</span>
                                <span class="block font-semibold text-slate-700"><?= $lot->quantity ?> <?= htmlspecialchars($lot->unit) ?></span>
                            </td>
                            <td class="px-5 py-3 text-slate-600"><?= number_format($lot->unitPrice, 2, ',', ' ') ?> MAD</td>
                            <td class="px-5 py-3 text-slate-600"><?= date('d/m/Y', strtotime($lot->expiryDate)) ?></td>
                            <td class="px-5 py-3 text-right">
                                <span class="font-semibold text-red-600">
                                    <?= number_format($lot->getTotalValue(), 2, ',', ' ') ?> MAD
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="bg-red-50 border-t border-red-200">
                        <td colspan="5" class="px-5 py-3 text-sm font-semibold text-slate-700">Total des pertes</td>
                        <td class="px-5 py-3 text-right text-base font-bold text-red-600">
                            <?= number_format($totalLost, 2, ',', ' ') ?> MAD
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Note pédagogique FEFO -->
<div class="mt-5 bg-blue-50 border border-blue-200 rounded-xl p-4 text-sm text-blue-800">
    <p class="font-semibold mb-1">💡 À propos de la méthode FEFO</p>
    <p>
        La méthode <strong>FEFO (First Expired, First Out)</strong> consiste à utiliser en priorité les produits dont
        la date de péremption est la plus proche. En appliquant cette méthode rigoureusement, une pharmacie peut
        réduire significativement les pertes financières liées aux médicaments périmés.
    </p>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>

<?php
// ============================================================
// templates/layouts/_alert_badge.php
// Usage : include avec $lot disponible
// ============================================================

$level = $lot->getAlertLevel();
$days  = $lot->getDaysRemaining();

$configs = [
    'green'   => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'dot' => 'bg-emerald-500', 'label' => 'Valide'],
    'orange'  => ['bg' => 'bg-amber-100',   'text' => 'text-amber-700',   'dot' => 'bg-amber-500',   'label' => 'Alerte orange'],
    'red'     => ['bg' => 'bg-red-100',      'text' => 'text-red-700',     'dot' => 'bg-red-500',     'label' => 'Alerte rouge'],
    'expired' => ['bg' => 'bg-slate-200',    'text' => 'text-slate-600',   'dot' => 'bg-slate-400',   'label' => 'Expiré'],
];

$cfg = $configs[$level];
$daysLabel = $days < 0 ? abs($days) . 'j dépassé' : $days . 'j restants';
?>
<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold <?= $cfg['bg'] ?> <?= $cfg['text'] ?>">
    <span class="w-1.5 h-1.5 rounded-full <?= $cfg['dot'] ?>"></span>
    <?= $cfg['label'] ?>
    <span class="opacity-70 font-normal">(<?= $daysLabel ?>)</span>
</span>

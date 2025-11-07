<?php
/**
 * Componente: Estado Vacío con Acciones
 * 
 * Variables requeridas:
 * - $titulo: Título del mensaje
 * - $mensaje: Mensaje descriptivo
 * 
 * Variables opcionales:
 * - $icono: Icono FontAwesome (default: 'search')
 * - $botones: Array de botones ['texto' => '', 'url' => '', 'clase' => '', 'icono' => '']
 * - $claseCard: Clase CSS del card (default: 'border-0 shadow-sm')
 * - $alertaAdmin: Mostrar alerta para admin (default: null)
 */

$icono = $icono ?? 'search';
$botones = $botones ?? [];
$claseCard = $claseCard ?? 'border-0 shadow-sm';
$alertaAdmin = $alertaAdmin ?? null;
?>

<div class="card <?= esc($claseCard) ?>">
    <div class="card-body text-center py-5">
        <div class="mb-4">
            <i class="fas fa-<?= esc($icono) ?> fa-4x text-primary opacity-50"></i>
        </div>
        <h4 class="text-muted mb-3"><?= esc($titulo) ?></h4>
        <p class="text-muted mb-4 lead"><?= esc($mensaje) ?></p>
        
        <?php if ($alertaAdmin && session()->get('nivel') === 'admin'): ?>
            <div class="alert alert-info border-0 mb-4">
                <i class="fas fa-user-shield me-2"></i>
                <?= $alertaAdmin ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($botones)): ?>
            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                <?php foreach ($botones as $boton): ?>
                    <a href="<?= esc($boton['url']) ?>" class="btn <?= esc($boton['clase'] ?? 'btn-primary') ?> btn-lg">
                        <?php if (!empty($boton['icono'])): ?>
                            <i class="fas fa-<?= esc($boton['icono']) ?> me-2"></i>
                        <?php endif; ?>
                        <?= esc($boton['texto']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

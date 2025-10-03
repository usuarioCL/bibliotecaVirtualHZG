<?php
/**
 * Partial: Card de Préstamo Reutilizable
 * 
 * Variables requeridas:
 * - $prestamo: Array con información del préstamo
 * 
 * Variables opcionales:
 * - $colClasses: Clases CSS para las columnas (default: 'col-lg-6 col-xl-4')
 * - $mostrarAcciones: Mostrar botones de acción (default: true)
 */

$colClasses = $colClasses ?? 'col-lg-6 col-xl-4';
$mostrarAcciones = $mostrarAcciones ?? true;

// Determinar estado y clase del préstamo
$estado = 'activo';
$claseEstado = 'success';
$claseBorde = 'primary';
$fechaVencimiento = '';

if (!empty($prestamo['fechadevolucion'])) {
    $fechaDevolucion = new DateTime($prestamo['fechadevolucion']);
    $hoy = new DateTime();
    
    if (!empty($prestamo['fechahoraretorno'])) {
        // Ya fue devuelto
        $estado = 'devuelto';
        $claseEstado = 'success';
        $claseBorde = 'success';
    } elseif ($fechaDevolucion < $hoy) {
        // Vencido
        $estado = 'vencido';
        $claseEstado = 'danger';
        $claseBorde = 'danger';
        $fechaVencimiento = 'Vencido: ' . $fechaDevolucion->format('d/M/Y');
    } else {
        $diasRestantes = $hoy->diff($fechaDevolucion)->days;
        if ($diasRestantes <= 3) {
            // Por vencer
            $estado = 'por_vencer';
            $claseEstado = 'warning';
            $claseBorde = 'warning';
            $fechaVencimiento = 'Vence: ' . $fechaDevolucion->format('d/M/Y');
        } else {
            // Activo normal
            $fechaVencimiento = 'Vence: ' . $fechaDevolucion->format('d/M/Y');
        }
    }
}

// Textos de estado
$textoEstado = [
    'activo' => 'Activo',
    'vencido' => 'Vencido', 
    'por_vencer' => 'Por Vencer',
    'devuelto' => 'Devuelto'
];
?>

<div class="<?= $colClasses ?> mb-4">
    <div class="card h-100 border-start border-<?= $claseBorde ?> border-3">
        <div class="card-header bg-light border-0 d-flex justify-content-between align-items-center">
            <span class="badge bg-<?= $claseEstado ?>"><?= $textoEstado[$estado] ?></span>
            <?php if ($fechaVencimiento): ?>
                <small class="text-<?= $claseEstado ?>"><?= $fechaVencimiento ?></small>
            <?php endif; ?>
        </div>
        
        <div class="card-body">
            <div class="row">
                <div class="col-4">
                    <?php if (!empty($prestamo['portada'])): ?>
                        <img src="<?= base_url($prestamo['portada']) ?>" 
                             class="img-fluid rounded" 
                             alt="Portada" 
                             style="height: 80px; object-fit: cover;">
                    <?php else: ?>
                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                             style="height: 80px;">
                            <i class="fas fa-book text-muted"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-8">
                    <h6 class="card-title mb-2">
                        <?= esc($prestamo['titulo']) ?>
                    </h6>
                    <p class="card-text small text-muted mb-2">
                        <strong>Autores:</strong> <?= esc($prestamo['nomautor'] ?? 'Sin autor') ?>
                    </p>
                    <p class="card-text small text-muted mb-2">
                        <strong>Prestado:</strong> 
                        <?= date('d/M/Y', strtotime($prestamo['fechaprestamo'])) ?>
                    </p>
                    <?php if (!empty($prestamo['fechahoraretorno'])): ?>
                        <p class="card-text small text-muted mb-0">
                            <strong>Devuelto:</strong> 
                            <?= date('d/M/Y', strtotime($prestamo['fechahoraretorno'])) ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <?php if ($mostrarAcciones && $estado !== 'devuelto'): ?>
            <div class="card-footer bg-transparent border-0">
                <div class="d-flex gap-2">
                    <?php if ($estado === 'vencido'): ?>
                        <button class="btn btn-outline-warning btn-sm flex-fill" 
                                onclick="renovarPrestamo(<?= $prestamo['idprestamo'] ?>)">
                            <i class="fas fa-exclamation-triangle me-1"></i>Renovar
                        </button>
                        <button class="btn btn-danger btn-sm flex-fill"
                                onclick="devolverPrestamo(<?= $prestamo['idprestamo'] ?>)">
                            <i class="fas fa-check me-1"></i>Devolver
                        </button>
                    <?php else: ?>
                        <button class="btn btn-outline-primary btn-sm flex-fill"
                                onclick="renovarPrestamo(<?= $prestamo['idprestamo'] ?>)">
                            <i class="fas fa-redo-alt me-1"></i>Renovar
                        </button>
                        <button class="btn btn-primary btn-sm flex-fill"
                                onclick="devolverPrestamo(<?= $prestamo['idprestamo'] ?>)">
                            <i class="fas fa-check me-1"></i>Devolver
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
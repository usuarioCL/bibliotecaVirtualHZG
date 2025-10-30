<?php
/**
 * Vista de detalles de préstamo para modal
 */

// Calcular estado y días restantes
// Plazo de préstamo: 7 días
$fechaVencimiento = !empty($prestamo['fechadevolucion']) ? strtotime($prestamo['fechadevolucion']) : strtotime($prestamo['fechaprestamo'] . ' +7 days');
$hoy = time();
// Usar floor() en lugar de ceil() y comparar solo las fechas (sin hora)
$fechaVencimientoSinHora = strtotime(date('Y-m-d', $fechaVencimiento));
$hoySinHora = strtotime(date('Y-m-d', $hoy));
$diasRestantes = floor(($fechaVencimientoSinHora - $hoySinHora) / 86400);
$esVencido = $diasRestantes < 0;
$porVencer = $diasRestantes <= 3 && $diasRestantes >= 0;
?>

<div class="row">
    <!-- Portada del libro -->
    <div class="col-md-4 text-center mb-3">
        <?php if (!empty($prestamo['portada'])): ?>
            <img src="<?= base_url($prestamo['portada']) ?>" 
                 class="img-fluid rounded shadow" 
                 alt="Portada de <?= esc($prestamo['titulo']) ?>"
                 style="max-height: 300px; object-fit: cover;">
        <?php else: ?>
            <div class="bg-light rounded shadow d-flex align-items-center justify-content-center" 
                 style="height: 300px;">
                <i class="fas fa-book fa-5x text-muted"></i>
            </div>
        <?php endif; ?>
    </div>

    <!-- Información del libro y préstamo -->
    <div class="col-md-8">
        <h4 class="mb-3 text-primary"><?= esc($prestamo['titulo']) ?></h4>
        
        <div class="mb-3">
            <p class="mb-2">
                <strong><i class="fas fa-user me-2 text-muted"></i>Autor:</strong> 
                <?= esc($prestamo['nomautor'] ?: 'Sin autor') ?>
            </p>
            
            <?php if (!empty($prestamo['nomeditorial'])): ?>
            <p class="mb-2">
                <strong><i class="fas fa-building me-2 text-muted"></i>Editorial:</strong> 
                <?= esc($prestamo['nomeditorial']) ?>
            </p>
            <?php endif; ?>
            
            <?php if (!empty($prestamo['anio'])): ?>
            <p class="mb-2">
                <strong><i class="fas fa-calendar me-2 text-muted"></i>Año:</strong> 
                <?= esc($prestamo['anio']) ?>
            </p>
            <?php endif; ?>
            
            <?php if (!empty($prestamo['isbn'])): ?>
            <p class="mb-2">
                <strong><i class="fas fa-barcode me-2 text-muted"></i>ISBN:</strong> 
                <?= esc($prestamo['isbn']) ?>
            </p>
            <?php endif; ?>
        </div>

        <hr>

        <!-- Información del préstamo -->
        <h5 class="mb-3">Información del Préstamo</h5>
        
        <div class="row mb-3">
            <div class="col-6">
                <p class="mb-2">
                    <strong><i class="fas fa-calendar-check me-2 text-success"></i>Préstamo:</strong><br>
                    <small class="text-muted"><?= date('d/m/Y H:i', strtotime($prestamo['fechaprestamo'])) ?></small>
                </p>
            </div>
            <div class="col-6">
                <p class="mb-2">
                    <strong><i class="fas fa-calendar-times me-2 text-danger"></i>Vencimiento:</strong><br>
                    <small class="<?= $esVencido ? 'text-danger fw-bold' : 'text-muted' ?>">
                        <?= date('d/m/Y', $fechaVencimiento) ?>
                    </small>
                </p>
            </div>
        </div>

        <!-- Estado del préstamo -->
        <div class="mb-3">
            <?php if (!empty($prestamo['fechahoraretorno'])): ?>
                <span class="badge bg-success fs-6">
                    <i class="fas fa-check-circle me-1"></i>
                    Devuelto el <?= date('d/m/Y', strtotime($prestamo['fechahoraretorno'])) ?>
                </span>
            <?php elseif ($esVencido): ?>
                <span class="badge bg-danger fs-6">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    Vencido hace <?= abs($diasRestantes) ?> día(s)
                </span>
            <?php elseif ($porVencer): ?>
                <span class="badge bg-warning text-dark fs-6">
                    <i class="fas fa-clock me-1"></i>
                    Por vencer en <?= $diasRestantes ?> día(s)
                </span>
            <?php else: ?>
                <span class="badge bg-primary fs-6">
                    <i class="fas fa-book-open me-1"></i>
                    Activo - <?= $diasRestantes ?> día(s) restantes
                </span>
            <?php endif; ?>
        </div>

        <?php if (!empty($prestamo['num_renovaciones']) && $prestamo['num_renovaciones'] > 0): ?>
        <div class="alert alert-info mb-3">
            <i class="fas fa-redo me-2"></i>
            Este préstamo ha sido renovado <strong><?= $prestamo['num_renovaciones'] ?></strong> vez(es)
        </div>
        <?php endif; ?>

        <?php if (!empty($prestamo['observaciones_devolucion'])): ?>
        <div class="alert alert-warning mb-0">
            <strong><i class="fas fa-info-circle me-2"></i>Observaciones:</strong><br>
            <?= esc($prestamo['observaciones_devolucion']) ?>
        </div>
        <?php endif; ?>
    </div>
</div>

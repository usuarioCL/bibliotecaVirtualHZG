<?php
/**
 * Componente: Fila de Préstamo para Tabla
 * 
 * Variables requeridas:
 * - $prestamo: Array con información del préstamo
 * 
 * Variables opcionales:
 * - $mostrarRenovar: Mostrar botón de renovar (default: false)
 * - $mostrarDevolver: Mostrar botón de devolver (default: false)
 */

// Cargar helper de préstamos
helper('prestamo');

$mostrarRenovar = $mostrarRenovar ?? false;
$mostrarDevolver = $mostrarDevolver ?? false;

// Calcular información del préstamo
$estadoInfo = calcularEstadoPrestamo($prestamo);
$fechaVencimientoInfo = obtenerInfoFechaVencimiento($prestamo);
?>

<tr class="align-middle">
    <!-- Libro con portada -->
    <td>
        <div class="d-flex align-items-center">
            <?php 
            $portada = $prestamo['portada'] ?? null;
            $titulo = $prestamo['titulo'] ?? 'Sin título';
            $size = 'small';
            $classes = 'me-3';
            echo view('partials/_portada_libro', compact('portada', 'titulo', 'size', 'classes'));
            ?>
            <div>
                <h6 class="mb-0 fw-semibold"><?= esc($prestamo['titulo']) ?></h6>
                <?php if (!empty($prestamo['isbn'])): ?>
                    <small class="text-muted">ISBN: <?= esc($prestamo['isbn']) ?></small>
                <?php endif; ?>
            </div>
        </div>
    </td>

    <!-- Autor -->
    <td class="text-muted"><?= esc(obtenerNombreAutor($prestamo)) ?></td>

    <!-- Fecha Préstamo -->
    <td>
        <small class="text-muted">
            <i class="fas fa-calendar-alt me-1"></i>
            <?= formatearFechaPrestamo($prestamo['fechaprestamo']) ?>
        </small>
    </td>

    <!-- Fecha Vencimiento/Devolución -->
    <td>
        <small class="text-<?= esc($fechaVencimientoInfo['clase']) ?>">
            <i class="fas fa-<?= esc($fechaVencimientoInfo['icono']) ?> me-1"></i>
            <?= esc($fechaVencimientoInfo['texto']) ?>
        </small>
    </td>

    <!-- Estado -->
    <td>
        <?= renderBadgeEstado($prestamo) ?>
    </td>

    <!-- Acciones -->
    <td>
        <div class="d-flex gap-2 justify-content-center">
            <button class="btn btn-sm btn-primary" 
                    data-bs-toggle="modal" 
                    data-bs-target="#prestamoModal"
                    onclick="cargarDetallesPrestamo(<?= $prestamo['idprestamo'] ?>)" 
                    title="Ver detalles">
                <i class="fas fa-eye"></i>
            </button>
            <?php if ($mostrarRenovar): ?>
                <button class="btn btn-sm btn-info text-white" 
                        onclick="renovarPrestamo(<?= $prestamo['idprestamo'] ?>)" 
                        title="Renovar">
                    <i class="fas fa-redo"></i>
                </button>
            <?php endif; ?>
            <?php if ($mostrarDevolver): ?>
                <button class="btn btn-sm btn-success" 
                        onclick="devolverPrestamo(<?= $prestamo['idprestamo'] ?>)" 
                        title="Devolver">
                    <i class="fas fa-check"></i>
                </button>
            <?php endif; ?>
        </div>
    </td>
</tr>

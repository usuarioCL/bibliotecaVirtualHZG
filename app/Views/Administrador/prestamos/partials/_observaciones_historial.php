<?php 
/**
 * Partial: Observaciones e Incidencias del Préstamo
 * Muestra observaciones y detalles de incidencias (daños/pérdidas)
 * 
 * @param array $registro - Registro del préstamo con toda su información
 */

// Verificar si hay incidencia
$tieneIncidencia = isset($registro['tiene_incidencia']) && $registro['tiene_incidencia'] == 1;

// Obtener y limpiar las observaciones
$observaciones = $registro['observaciones'] ?? null;

// Si es una solicitud rechazada, limpiar la parte de "Cantidad solicitada:"
if ($registro['estado_final'] === 'Rechazado' && !empty($observaciones)) {
    // Remover "Cantidad solicitada: X ejemplares. " del inicio
    $observaciones = preg_replace('/^Cantidad solicitada:\s*\d+\s*ejemplares?\.\s*/', '', $observaciones);
}

$tieneObservaciones = !empty($observaciones) && trim($observaciones) !== '' && $observaciones !== 'NULL';
$longitudMaxima = 80; // Caracteres a mostrar antes de truncar
?>

<?php if ($tieneIncidencia): ?>
    <!-- Mostrar información de incidencia -->
    <div class="text-start">
        <div class="alert alert-danger alert-sm mb-2 py-2 px-2">
            <div class="d-flex align-items-start">
                <i class="ti ti-alert-triangle text-danger me-2 mt-1"></i>
                <div class="flex-grow-1">
                    <strong class="d-block small"><?= esc($registro['tipo_incidencia'] ?? 'Incidencia') ?></strong>
                    <?php if (!empty($registro['detalle_incidencia'])): ?>
                        <small class="text-muted"><?= esc($registro['detalle_incidencia']) ?></small>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <button type="button" 
                class="btn btn-link btn-sm p-0 text-decoration-none text-danger" 
                onclick='Historial.mostrarDetalleIncidencia(<?= json_encode([
                    'tipo' => $registro['tipo_incidencia'] ?? 'Incidencia',
                    'detalle' => $registro['detalle_incidencia'] ?? '',
                    'observaciones' => $registro['observaciones_incidencia'] ?? '',
                    'fecha' => $registro['fecha_sancion'] ?? '',
                    'usuario' => $registro['usuario'] ?? ''
                ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)'>
            <small><i class="ti ti-eye me-1"></i>Ver detalles de incidencia</small>
        </button>
    </div>
<?php elseif ($tieneObservaciones): ?>
    <div class="text-start">
        <?php if (strlen($observaciones) > $longitudMaxima): ?>
            <!-- Observación larga - mostrar resumen -->
            <p class="mb-1 small text-muted">
                <i class="ti ti-message-circle me-1"></i>
                <?= esc(substr($observaciones, 0, $longitudMaxima)) ?>...
            </p>
            <button type="button" 
                    class="btn btn-link btn-sm p-0 text-decoration-none" 
                    onclick="Historial.mostrarObservaciones(<?= json_encode($observaciones, JSON_HEX_APOS | JSON_HEX_QUOT) ?>, <?= json_encode($registro['usuario'], JSON_HEX_APOS | JSON_HEX_QUOT) ?>)">
                <small><i class="ti ti-eye me-1"></i>Ver completo</small>
            </button>
        <?php else: ?>
            <!-- Observación corta - mostrar completa -->
            <p class="mb-0 small text-muted">
                <i class="ti ti-message-circle me-1"></i>
                <?= esc($observaciones) ?>
            </p>
        <?php endif; ?>
    </div>
<?php else: ?>
    <div class="text-center">
        <span class="text-muted small">
            <i class="ti ti-minus"></i> Sin observaciones
        </span>
    </div>
<?php endif; ?>

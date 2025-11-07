<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?= esc($titulo ?? 'Listado de Recursos') ?></title>
  <style>
    body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 9px; color: #111; }
    h1 { font-size: 16px; margin: 0 0 8px 0; }
    .meta { font-size: 9px; color: #555; margin-bottom: 12px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ccc; padding: 4px 6px; vertical-align: middle; }
    thead th { background: #f2f2f2; font-size: 9px; }
    tbody tr:nth-child(even) { background: #fafafa; }
    .portada-img { max-width: 35px; max-height: 50px; display: block; margin: 0 auto; }
  </style>
</head>
<body>
  <h1><?= esc($titulo ?? 'Listado de Recursos') ?></h1>
  <div class="meta">Generado: <?= date('d/m/Y H:i') ?></div>

  <table>
    <thead>
      <tr>
        <th style="width:35px;">ID</th>
        <th style="width:50px;">Portada</th>
        <th>Título</th>
        <th style="width:45px;">Año</th>
        <th style="width:55px;">Págs</th>
        <th style="width:90px;">Encuad.</th>
        <th style="width:100px;">ISBN</th>
        <th style="width:55px;">Ed.</th>
        <th style="width:70px;">Estado</th>
        <th style="width:45px;">Stock</th>
        <th style="width:50px;">Tipo</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($recursos)): ?>
        <?php foreach ($recursos as $r): ?>
          <tr>
            <td><?= esc($r['idrecurso']) ?></td>
            <td style="text-align:center; padding: 3px;">
              <?php 
                // Obtener ruta de portada (priorizar portada de tabla específica)
                $portada = !empty($r['portada']) ? $r['portada'] : (!empty($r['rutaportada']) ? $r['rutaportada'] : '');
                
                if ($portada):
                    // Construir ruta absoluta del archivo
                    $rutaAbsoluta = FCPATH . $portada;
                    
                    // Verificar si el archivo existe
                    if (file_exists($rutaAbsoluta)): 
                        // Convertir imagen a base64 para Dompdf
                        $imageData = base64_encode(file_get_contents($rutaAbsoluta));
                        $extension = pathinfo($rutaAbsoluta, PATHINFO_EXTENSION);
                        $mimeType = $extension === 'png' ? 'image/png' : 'image/jpeg';
                        $imageSrc = 'data:' . $mimeType . ';base64,' . $imageData;
              ?>
                <img src="<?= $imageSrc ?>" class="portada-img" alt="Portada">
              <?php else: ?>
                <span style="font-size: 7px; color: #999;">Sin img</span>
              <?php 
                    endif;
                else: 
              ?>
                -
              <?php endif; ?>
            </td>
            <td>
              <div><strong><?= esc($r['titulo']) ?></strong></div>
              <?php if (!empty($r['subtitulo'])): ?>
                <div style="color:#555; font-size: 8px;"><?= esc($r['subtitulo']) ?></div>
              <?php endif; ?>
            </td>
            <td><?= esc($r['anio']) ?></td>
            <td><?= esc($r['numpaginas']) ?></td>
            <td><?= esc($r['encuadernacion']) ?></td>
            <td><?= !empty($r['isbn']) ? esc($r['isbn']) : '-' ?></td>
            <td><?= esc($r['numedicion']) ?></td>
            <td><?= esc(ucfirst($r['estado'])) ?></td>
            <td><?= esc($r['stock']) ?></td>
            <td>
                <?php if(isset($r['tiporecurso']) && stripos($r['tiporecurso'], 'digital') !== false): ?>
                    Digital
                <?php else: ?>
                    Físico
                <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="11" style="text-align:center; padding: 16px;">No hay recursos registrados.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</body>
</html>

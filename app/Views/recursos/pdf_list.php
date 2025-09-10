<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?= esc($titulo ?? 'Listado de Recursos') ?></title>
  <style>
    body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 12px; color: #111; }
    h1 { font-size: 18px; margin: 0 0 8px 0; }
    .meta { font-size: 11px; color: #555; margin-bottom: 12px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ccc; padding: 6px 8px; }
    thead th { background: #f2f2f2; }
    tbody tr:nth-child(even) { background: #fafafa; }
  </style>
</head>
<body>
  <h1><?= esc($titulo ?? 'Listado de Recursos') ?></h1>
  <div class="meta">Generado: <?= date('d/m/Y H:i') ?></div>

  <table>
    <thead>
      <tr>
        <th style="width:45px;">ID</th>
        <th>Título</th>
        <th style="width:55px;">Año</th>
        <th style="width:70px;">Páginas</th>
        <th style="width:110px;">Encuadernación</th>
        <th style="width:120px;">ISBN</th>
        <th style="width:90px;">Edición</th>
        <th style="width:90px;">Estado</th>
        <th style="width:55px;">Stock</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($recursos)): ?>
        <?php foreach ($recursos as $r): ?>
          <tr>
            <td><?= esc($r['idrecurso']) ?></td>
            <td>
              <div><strong><?= esc($r['titulo']) ?></strong></div>
              <?php if (!empty($r['subtitulo'])): ?>
                <div style="color:#555; font-size: 11px;"><?= esc($r['subtitulo']) ?></div>
              <?php endif; ?>
            </td>
            <td><?= esc($r['anio']) ?></td>
            <td><?= esc($r['numpaginas']) ?></td>
            <td><?= esc($r['encuadernacion']) ?></td>
            <td><?= !empty($r['isbn']) ? esc($r['isbn']) : '-' ?></td>
            <td><?= esc($r['numedicion']) ?></td>
            <td><?= esc(ucfirst($r['estado'])) ?></td>
            <td><?= esc($r['stock']) ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="9" style="text-align:center; padding: 16px;">No hay recursos registrados.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</body>
</html>

<!-- app/Views/recurso/ver_pdf_custom.php -->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Ver libro</title>
  <style>
    body { margin: 0; padding: 16px; font-family: system-ui, sans-serif; }
    #viewer { display: flex; flex-direction: column; gap: 16px; align-items: center; }
    canvas { border: 1px solid #ddd; width: 100%; max-width: 900px; }
    .toolbar { display: flex; gap: 8px; margin-bottom: 12px; }
  </style>
  <script src="https://cdn.jsdelivr.net/npm/pdfjs-dist@4.4.168/build/pdf.js"></script>
</head>
<body>
  <div class="toolbar">
    <button id="prev">Anterior</button>
    <span>Página <span id="page_num">1</span> de <span id="page_count">?</span></span>
    <button id="next">Siguiente</button>
    <button id="zoom_out">-</button>
    <button id="zoom_in">+</button>
  </div>
  <div id="viewer">
    <canvas id="pdf-canvas"></canvas>
  </div>

  <script>
    // Ruta del PDF desde PHP
    const pdfUrl = "<?= esc($pdfUrl) ?>";

    // Necesario para cargar los workers
    pdfjsLib.GlobalWorkerOptions.workerSrc =
      "https://cdn.jsdelivr.net/npm/pdfjs-dist@4.4.168/build/pdf.worker.min.js";

    const canvas = document.getElementById('pdf-canvas');
    const ctx = canvas.getContext('2d');
    const pageNumSpan = document.getElementById('page_num');
    const pageCountSpan = document.getElementById('page_count');
    const prevBtn = document.getElementById('prev');
    const nextBtn = document.getElementById('next');
    const zoomInBtn = document.getElementById('zoom_in');
    const zoomOutBtn = document.getElementById('zoom_out');

    let pdfDoc = null;
    let pageNum = 1;
    let scale = 1.2;
    let rendering = false;
    let pendingPage = null;

    function renderPage(num) {
      rendering = true;
      pdfDoc.getPage(num).then(function(page) {
        const viewport = page.getViewport({ scale });
        canvas.height = viewport.height;
        canvas.width = viewport.width;

        const renderCtx = { canvasContext: ctx, viewport };
        const renderTask = page.render(renderCtx);

        renderTask.promise.then(function () {
          rendering = false;
          pageNumSpan.textContent = num;
          if (pendingPage !== null) {
            const toRender = pendingPage;
            pendingPage = null;
            renderPage(toRender);
          }
        });
      });
    }

    function queueRenderPage(num) {
      if (rendering) {
        pendingPage = num;
      } else {
        renderPage(num);
      }
    }

    function onPrevPage() {
      if (pageNum <= 1) return;
      pageNum--;
      queueRenderPage(pageNum);
    }

    function onNextPage() {
      if (pageNum >= pdfDoc.numPages) return;
      pageNum++;
      queueRenderPage(pageNum);
    }

    function onZoom(delta) {
      scale = Math.max(0.5, Math.min(3, scale + delta));
      queueRenderPage(pageNum);
    }

    prevBtn.addEventListener('click', onPrevPage);
    nextBtn.addEventListener('click', onNextPage);
    zoomInBtn.addEventListener('click', () => onZoom(0.2));
    zoomOutBtn.addEventListener('click', () => onZoom(-0.2));

    pdfjsLib.getDocument(pdfUrl).promise.then(function(pdf) {
      pdfDoc = pdf;
      pageCountSpan.textContent = pdf.numPages;
      renderPage(pageNum);
    });
  </script>
</body>
</html>
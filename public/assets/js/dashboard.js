$(function () {
  // Asegurarse de que el contenedor esté visible y tenga dimensiones
  var initializeChart = function() {
    if (!document.querySelector("#sales-overview")) {
      return;
    }
    
    var options_sales_overview = {
      series: [
        {
          name: "Ample Admin",
          data: [355, 390, 300, 350, 390, 180],
        },
        {
          name: "Pixel Admin",
          data: [280, 250, 325, 215, 250, 310],
        },
      ],
      chart: {
        type: "bar",
        height: 275,
        toolbar: {
          show: false,
        },
        foreColor: "#adb0bb",
        fontFamily: "inherit",
        sparkline: {
          enabled: false,
        },
      },
      grid: {
        show: false,
        borderColor: "transparent",
        padding: {
          left: 0,
          right: 0,
          bottom: 0,
        },
      },
      plotOptions: {
        bar: {
          horizontal: false,
          columnWidth: "25%",
          endingShape: "rounded",
          borderRadius: 5,
        },
      },
      colors: ["var(--bs-primary)", "var(--bs-secondary)"],
      dataLabels: {
        enabled: false,
      },
      yaxis: {
        show: true,
        min: 100,
        max: 400,
        tickAmount: 3,
      },
      stroke: {
        show: true,
        width: 5,
        lineCap: "butt",
        colors: ["transparent"],
      },
      xaxis: {
        type: "category",
        categories: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
        axisBorder: {
          show: false,
        },
      },
      fill: {
        opacity: 1,
      },
      tooltip: {
        theme: "dark",
      },
      legend: {
        show: false,
      },
    };

    // Inicializar el gráfico solo si el contenedor existe
    var salesOverviewElement = document.querySelector("#sales-overview");
    if (salesOverviewElement) {
      // Asegurarse de que el contenedor tenga un ancho mínimo
      salesOverviewElement.style.minWidth = "200px";
      salesOverviewElement.style.width = "100%";
      
      var chart_column_basic = new ApexCharts(
        salesOverviewElement,
        options_sales_overview
      );
      
      // Renderizar después de un pequeño delay para asegurar que el DOM esté listo
      setTimeout(function() {
        chart_column_basic.render();
      }, 100);
    }
  };

  // Intentar inicializar inmediatamente
  initializeChart();

  // También intentar inicializar cuando se cargue contenido dinámicamente
  $(document).on('content-loaded', function() {
    initializeChart();
  });
});
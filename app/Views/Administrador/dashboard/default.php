<!-- Primera fila: Cards de estadísticas principales -->
<div class="row">
  <div class="col-lg-8">
      <div class="card w-100">
      <div class="card-body">
          <div class="d-md-flex align-items-center">
          <div>
              <h4 class="card-title">Préstamos del Mes</h4>
              <p class="card-subtitle">
              Comparación de préstamos vs devoluciones
              </p>
          </div>
          <div class="ms-auto">
              <ul class="list-unstyled mb-0">
              <li class="list-inline-item text-primary">
                  <span class="round-8 text-bg-primary rounded-circle me-1 d-inline-block"></span>
                  Préstamos
              </li>
              <li class="list-inline-item text-info">
                  <span class="round-8 text-bg-info rounded-circle me-1 d-inline-block"></span>
                  Devoluciones
              </li>
              </ul>
          </div>
          </div>
          <div id="prestamos-overview" class="mt-4 mx-n6"></div>
      </div>
      </div>
  </div>
  <div class="col-lg-4">
      <div class="card">
      <div class="card-body">
          <div class="d-flex align-items-start">
          <div>
              <h4 class="card-title">Estadísticas Semanales</h4>
              <p class="card-subtitle">Actividad de la biblioteca</p>
          </div>
          <div class="ms-auto">
              <div class="dropdown">
              <a href="javascript:void(0)" class="text-muted" id="year1-dropdown" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <i class="ti ti-dots fs-7"></i>
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="year1-dropdown">
                  <li>
                  <a class="dropdown-item" href="javascript:void(0)">Esta semana</a>
                  </li>
                  <li>
                  <a class="dropdown-item" href="javascript:void(0)">Semana pasada</a>
                  </li>
                  <li>
                  <a class="dropdown-item" href="javascript:void(0)">Este mes</a>
                  </li>
              </ul>
              </div>
          </div>
          </div>
          <div class="mt-3 pb-2 d-flex align-items-center">
          <span class="btn btn-primary rounded-circle round-40 hstack justify-content-center">
              <i class="ti ti-books fs-7"></i>
          </span>
          <div class="ms-3">
              <h6 class="mb-0 fw-bolder fs-5">Más Solicitado</h6>
              <span class="text-muted fs-4">Cien años de soledad</span>
          </div>
          <div class="ms-auto">
              <span class="badge bg-secondary-subtle text-muted">+24</span>
          </div>
          </div>
          <div class="py-2 d-flex align-items-center">
          <span class="btn btn-warning rounded-circle round-40 hstack justify-content-center">
              <i class="ti ti-star fs-7"></i>
          </span>
          <div class="ms-3">
              <h6 class="mb-0 fw-bolder fs-5">Mejor Valorado</h6>
              <span class="text-muted fs-4">Don Quijote</span>
          </div>
          <div class="ms-auto">
              <span class="badge bg-secondary-subtle text-muted">4.8★</span>
          </div>
          </div>
          <div class="py-2 d-flex align-items-center">
          <span class="btn btn-success rounded-circle round-40 hstack justify-content-center">
              <i class="ti ti-users fs-7"></i>
          </span>
          <div class="ms-3">
              <h6 class="mb-0 fw-bolder fs-5">Usuario Activo</h6>
              <span class="text-muted fs-4">María García</span>
          </div>
          <div class="ms-auto">
              <span class="badge bg-secondary-subtle text-muted">15</span>
          </div>
          </div>
      </div>
      </div>
  </div>
</div>

<!-- Segunda fila: Tabla de recursos -->
<div class="row mt-4">
  <div class="col-12">
      <div class="card">
      <div class="card-body">
          <div class="d-md-flex align-items-center">
          <div>
              <h4 class="card-title">Gestión de Recursos</h4>
              <p class="card-subtitle">
              Estado actual de los recursos bibliográficos
              </p>
          </div>
          <div class="ms-auto mt-3 mt-md-0">
              <select class="form-select theme-select border-0" aria-label="Default select example">
              <option value="1">Septiembre 2025</option>
              <option value="2">Agosto 2025</option>
              <option value="3">Julio 2025</option>
              </select>
          </div>
          </div>
          <div class="table-responsive mt-4">
          <table class="table mb-0 text-nowrap varient-table align-middle fs-3">
              <thead>
              <tr>
                  <th scope="col" class="px-0 text-muted">
                  Recurso
                  </th>
                  <th scope="col" class="px-0 text-muted">Autor</th>
                  <th scope="col" class="px-0 text-muted">
                  Estado
                  </th>
                  <th scope="col" class="px-0 text-muted text-end">
                  Stock
                  </th>
              </tr>
              </thead>
              <tbody>
              <tr>
                  <td class="px-0">
                  <div class="d-flex align-items-center">
                      <div class="bg-primary rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                      <i class="ti ti-book-2 text-white"></i>
                      </div>
                      <div class="ms-3">
                      <h6 class="mb-0 fw-bolder">Cien años de soledad</h6>
                      <span class="text-muted">Literatura</span>
                      </div>
                  </div>
                  </td>
                  <td class="px-0">Gabriel García Márquez</td>
                  <td class="px-0">
                  <span class="badge bg-success">Disponible</span>
                  </td>
                  <td class="px-0 text-dark fw-medium text-end">
                  8 unidades
                  </td>
              </tr>
              <tr>
                  <td class="px-0">
                  <div class="d-flex align-items-center">
                      <div class="bg-warning rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                      <i class="ti ti-book text-white"></i>
                      </div>
                      <div class="ms-3">
                      <h6 class="mb-0 fw-bolder">
                          Don Quijote de la Mancha
                      </h6>
                      <span class="text-muted">Clásicos</span>
                      </div>
                  </div>
                  </td>
                  <td class="px-0">Miguel de Cervantes</td>
                  <td class="px-0">
                  <span class="badge text-bg-primary">Prestado</span>
                  </td>
                  <td class="px-0 text-dark fw-medium text-end">
                  3 unidades
                  </td>
              </tr>
              <tr>
                  <td class="px-0">
                  <div class="d-flex align-items-center">
                      <div class="bg-info rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                      <i class="ti ti-book-2 text-white"></i>
                      </div>
                      <div class="ms-3">
                      <h6 class="mb-0 fw-bolder">
                          1984
                      </h6>
                      <span class="text-muted">Ciencia Ficción</span>
                      </div>
                  </div>
                  </td>
                  <td class="px-0">George Orwell</td>
                  <td class="px-0">
                  <span class="badge bg-warning">Stock Bajo</span>
                  </td>
                  <td class="px-0 text-dark fw-medium text-end">
                  2 unidades
                  </td>
              </tr>
              <tr>
                  <td class="px-0">
                  <div class="d-flex align-items-center">
                      <div class="bg-success rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                      <i class="ti ti-book text-white"></i>
                      </div>
                      <div class="ms-3">
                      <h6 class="mb-0 fw-bolder">Crimen y Castigo</h6>
                      <span class="text-muted">Drama</span>
                      </div>
                  </div>
                  </td>
                  <td class="px-0">Fiódor Dostoyevski</td>
                  <td class="px-0">
                  <span class="badge bg-success">Disponible</span>
                  </td>
                  <td class="px-0 text-dark fw-medium text-end">
                  5 unidades
                  </td>
              </tr>
              </tbody>
          </table>
          </div>
      </div>
      </div>
  </div>
</div>

<!-- Tercera fila: Usuarios activos y categorías -->
<div class="row mt-4">
  <div class="col-lg-6">
      <div class="card">
      <div class="card-body">
          <div class="d-flex align-items-center">
          <h4 class="card-title mb-0">Usuarios Activos</h4>
          <select class="form-select w-auto ms-auto">
              <option selected="">Hoy</option>
              <option value="1">Esta semana</option>
          </select>
          </div>
          <div class="d-flex align-items-center flex-row mt-4">
          <div class="p-2 display-6 text-primary">
              <i class="ti ti-users"></i>
              <span>89</span>
          </div>
          <div class="p-2">
              <h4 class="mb-0">Conectados</h4>
              <small>Biblioteca Virtual HZG</small>
          </div>
          </div>
          <table class="table table-borderless">
          <tbody>
              <tr>
              <td>Estudiantes</td>
              <td class="fw-medium">67 usuarios</td>
              </tr>
              <tr>
              <td>Docentes</td>
              <td class="fw-medium">18 usuarios</td>
              </tr>
              <tr>
              <td>Administrativos</td>
              <td class="fw-medium">4 usuarios</td>
              </tr>
              <tr>
              <td>Préstamos Activos</td>
              <td class="fw-medium">156 recursos</td>
              </tr>
          </tbody>
          </table>
      </div>
      </div>
  </div>
  <div class="col-lg-6">
      <div class="card">
      <div class="card-body">
          <div class="d-flex align-items-center">
          <h4 class="card-title mb-0">Categorías Populares</h4>
          <select class="form-select w-auto ms-auto">
              <option selected="">Este mes</option>
              <option value="1">Esta semana</option>
          </select>
          </div>
          <div class="mt-4">
          <div class="d-flex align-items-center mb-3">
              <div class="me-3">
              <div class="bg-primary rounded-circle p-2">
                  <i class="ti ti-book-2 text-white fs-6"></i>
              </div>
              </div>
              <div class="flex-grow-1">
              <h6 class="mb-0">Literatura Clásica</h6>
              <small class="text-muted">245 recursos</small>
              </div>
              <div class="text-end">
              <h6 class="mb-0">35%</h6>
              <div class="progress" style="height: 4px; width: 60px;">
                  <div class="progress-bar bg-primary" style="width: 35%"></div>
              </div>
              </div>
          </div>
          
          <div class="d-flex align-items-center mb-3">
              <div class="me-3">
              <div class="bg-success rounded-circle p-2">
                  <i class="ti ti-atom text-white fs-6"></i>
              </div>
              </div>
              <div class="flex-grow-1">
              <h6 class="mb-0">Ciencias</h6>
              <small class="text-muted">189 recursos</small>
              </div>
              <div class="text-end">
              <h6 class="mb-0">28%</h6>
              <div class="progress" style="height: 4px; width: 60px;">
                  <div class="progress-bar bg-success" style="width: 28%"></div>
              </div>
              </div>
          </div>
          
          <div class="d-flex align-items-center mb-3">
              <div class="me-3">
              <div class="bg-warning rounded-circle p-2">
                  <i class="ti ti-history text-white fs-6"></i>
              </div>
              </div>
              <div class="flex-grow-1">
              <h6 class="mb-0">Historia</h6>
              <small class="text-muted">156 recursos</small>
              </div>
              <div class="text-end">
              <h6 class="mb-0">22%</h6>
              <div class="progress" style="height: 4px; width: 60px;">
                  <div class="progress-bar bg-warning" style="width: 22%"></div>
              </div>
              </div>
          </div>
          
          <div class="d-flex align-items-center">
              <div class="me-3">
              <div class="bg-info rounded-circle p-2">
                  <i class="ti ti-palette text-white fs-6"></i>
              </div>
              </div>
              <div class="flex-grow-1">
              <h6 class="mb-0">Arte y Cultura</h6>
              <small class="text-muted">89 recursos</small>
              </div>
              <div class="text-end">
              <h6 class="mb-0">15%</h6>
              <div class="progress" style="height: 4px; width: 60px;">
                  <div class="progress-bar bg-info" style="width: 15%"></div>
              </div>
              </div>
          </div>
          </div>
      </div>
      </div>
  </div>
</div>
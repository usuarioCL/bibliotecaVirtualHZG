<?= $header; ?>
<?= $navbar; ?>

<!-- Estilos institucionales de la Biblioteca Virtual HZG -->
<link rel="stylesheet" href="<?= base_url('assets/css/biblioteca-hzg.css') ?>">

<div class="container mt-4">

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Hero Section -->
            <div class=" text-center mb-5">
                <h1 class="display-4 fw-bold text-primary mb-3">Biblioteca Virtual HZG</h1>
                <p class="lead text-muted">Tu acceso digital al conocimiento</p>
            </div>

            <!-- Misión y Visión -->
            <div class="row mb-5">
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-bullseye fa-3x text-primary mb-3"></i>
                            <h3 class="card-title">Nuestra Misión</h3>
                            <p class="card-text">Democratizar el acceso a la información y el conocimiento mediante una plataforma digital moderna, facilitando el aprendizaje y la investigación para toda nuestra comunidad educativa.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-eye fa-3x text-primary mb-3"></i>
                            <h3 class="card-title">Nuestra Visión</h3>
                            <p class="card-text">Ser la plataforma de referencia para el acceso digital a recursos educativos, promoviendo la cultura de la lectura y el aprendizaje continuo en la era digital.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Características de la Plataforma -->
            <div class="mb-5">
                <h2 class="text-center mb-4 text-primary">¿Qué Ofrecemos?</h2>
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="text-center">
                            <i class="fas fa-book fa-2x text-primary mb-3"></i>
                            <h5>Amplio Catálogo</h5>
                            <p class="text-muted">Miles de recursos digitales organizados por categorías y niveles educativos.</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="text-center">
                            <i class="fas fa-search fa-2x text-primary mb-3"></i>
                            <h5>Búsqueda Avanzada</h5>
                            <p class="text-muted">Encuentra rápidamente el recurso que necesitas con nuestros filtros inteligentes.</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="text-center">
                            <i class="fas fa-mobile-alt fa-2x text-primary mb-3"></i>
                            <h5>Acceso 24/7</h5>
                            <p class="text-muted">Disponible desde cualquier dispositivo, en cualquier momento y lugar.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tipos de Usuarios -->
            <div class="mb-5">
                <h2 class="text-center mb-4 text-primary">Para Quien Está Diseñada</h2>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card border-primary">
                            <div class="card-body text-center">
                                <i class="fas fa-user-graduate fa-2x text-primary mb-2"></i>
                                <h5 class="card-title">Estudiantes</h5>
                                <p class="card-text">Acceso a material de estudio, investigación y recursos complementarios para tu formación académica.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <i class="fas fa-chalkboard-teacher fa-2x text-success mb-2"></i>
                                <h5 class="card-title">Docentes</h5>
                                <p class="card-text">Recursos pedagógicos, material de apoyo y herramientas para enriquecer tus clases.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-warning">
                            <div class="card-body text-center">
                                <i class="fas fa-user-cog fa-2x text-warning mb-2"></i>
                                <h5 class="card-title">Administradores</h5>
                                <p class="card-text">Herramientas de gestión para administrar usuarios, recursos y el funcionamiento de la plataforma.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información Técnica -->
            <div class="mb-5">
                <div class="card bg-light">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4">Información de la Plataforma</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <h5><i class="fas fa-code text-primary"></i> Tecnología</h5>
                                <ul>
                                    <li>Desarrollada con CodeIgniter 4</li>
                                    <li>Base de datos MySQL</li>
                                    <li>Interfaz responsive con Bootstrap</li>
                                    <li>Compatible con dispositivos móviles</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h5><i class="fas fa-shield-alt text-success"></i> Seguridad</h5>
                                <ul>
                                    <li>Sistema de autenticación seguro</li>
                                    <li>Control de acceso por roles</li>
                                    <li>Protección de datos personales</li>
                                    <li>Respaldos automáticos</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contacto -->
            <div class="text-center mb-5">
                <h3 class="text-primary mb-3">¿Necesitas Ayuda?</h3>
                <p class="lead">Nuestro equipo está aquí para apoyarte</p>
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <p><i class="fas fa-envelope text-primary"></i> <strong>Email:</strong> biblioteca@hzg.edu</p>
                                <p><i class="fas fa-phone text-primary"></i> <strong>Teléfono:</strong> +1 234 567 890</p>
                                <p><i class="fas fa-clock text-primary"></i> <strong>Horarios:</strong> Lunes a Viernes, 8:00 AM - 6:00 PM</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $footer; ?>

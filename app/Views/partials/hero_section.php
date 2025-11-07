<!-- Hero section con buscador -->
<div class="hero-section mt-4 mb-4">
    <!-- Carrusel de fondo -->
    <div id="heroCarousel" class="hero-carousel carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="<?= base_url('img/portada_1.png') ?>" alt="Biblioteca 1">
            </div>
            <div class="carousel-item">
                <img src="<?= base_url('img/portada_2.png') ?>" alt="Biblioteca 2">
            </div>
        </div>
    </div>
    
    <!-- Overlay con gradiente -->
    <div class="hero-overlay"></div>
    
    <!-- Contenido del hero -->
    <div class="hero-content">
        <h1 class="display-4 mb-3">Biblioteca Virtual</h1>
        <p class="lead mb-4">Horacio Zeballos Gámez</p>
        <form action="<?= base_url('recursos/buscarRecursos') ?>" method="get" class="w-100 d-flex justify-content-center">
            <div class="input-group" style="max-width: 500px;">
                <input 
                    type="search" 
                    name="query" 
                    class="form-control form-control-lg rounded-start-pill" 
                    placeholder="Buscar recursos educativos..." 
                    aria-label="Buscar" 
                    required>
                <button type="submit" class="btn btn-danger btn-lg rounded-end-pill">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>
</div>

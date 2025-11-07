<?php

if (!function_exists('renderBadgeEstadoRecurso')) {
    /**
     * Renderizar badge de estado del recurso (disponible/no disponible)
     * 
     * @param string $estado Estado del recurso
     * @return string HTML del badge
     */
    function renderBadgeEstadoRecurso($estado)
    {
        if ($estado === 'disponible') {
            return '<span class="badge bg-success">
                <i class="fas fa-check-circle me-1"></i>Disponible
            </span>';
        }
        
        return '<span class="badge bg-secondary">
            <i class="fas fa-ban me-1"></i>No disponible
        </span>';
    }
}

if (!function_exists('renderPortadaRecurso')) {
    /**
     * Renderizar portada del recurso con fallback
     * 
     * @param string|null $portada URL de la portada
     * @param string $titulo Título del recurso (para alt)
     * @param string $size Tamaño: 'small', 'medium', 'large'
     * @return string HTML de la imagen o placeholder
     */
    function renderPortadaRecurso($portada, $titulo = 'Portada', $size = 'small')
    {
        $sizes = [
            'small' => ['width' => 40, 'height' => 50],
            'medium' => ['width' => 80, 'height' => 100],
            'large' => ['width' => 150, 'height' => 200]
        ];
        
        $dimensions = $sizes[$size] ?? $sizes['small'];
        $width = $dimensions['width'];
        $height = $dimensions['height'];
        
        if (!empty($portada)) {
            return sprintf(
                '<img src="%s" class="rounded" style="width: %dpx; height: %dpx; object-fit: cover;" alt="%s" loading="lazy">',
                base_url($portada),
                $width,
                $height,
                esc($titulo)
            );
        }
        
        return sprintf(
            '<div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: %dpx; height: %dpx;">
                <i class="fas fa-book text-muted"></i>
            </div>',
            $width,
            $height
        );
    }
}

if (!function_exists('renderBadgeCategorias')) {
    /**
     * Renderizar badges de categoría y subcategoría
     * 
     * @param string|null $categoria Categoría principal
     * @param string|null $subcategoria Subcategoría
     * @return string HTML de los badges
     */
    function renderBadgeCategorias($categoria, $subcategoria = null)
    {
        $html = '';
        
        if (!empty($categoria)) {
            $html .= sprintf(
                '<span class="badge bg-primary">%s</span>',
                esc($categoria)
            );
            
            if (!empty($subcategoria)) {
                $html .= sprintf(
                    '<br><small class="text-muted">%s</small>',
                    esc($subcategoria)
                );
            }
        } elseif (!empty($subcategoria)) {
            $html .= sprintf(
                '<span class="badge bg-info">%s</span>',
                esc($subcategoria)
            );
        } else {
            $html .= '<span class="badge bg-secondary">Sin categoría</span>';
        }
        
        return $html;
    }
}

if (!function_exists('renderISBN')) {
    /**
     * Renderizar ISBN de forma segura
     * 
     * @param string|null $isbn ISBN del recurso
     * @return string HTML del ISBN o vacío
     */
    function renderISBN($isbn)
    {
        if (empty($isbn)) {
            return '';
        }
        
        return sprintf(
            '<small class="text-muted">ISBN: %s</small>',
            esc($isbn)
        );
    }
}

if (!function_exists('renderSpinner')) {
    /**
     * Renderizar spinner de carga
     * 
     * @param string $mensaje Mensaje a mostrar
     * @param string $color Color del spinner (primary, secondary, etc.)
     * @return string HTML del spinner
     */
    function renderSpinner($mensaje = 'Cargando...', $color = 'primary')
    {
        return sprintf(
            '<div class="text-center">
                <div class="spinner-border text-%s" role="status">
                    <span class="visually-hidden">%s</span>
                </div>
                <p class="mt-2">%s</p>
            </div>',
            esc($color),
            esc($mensaje),
            esc($mensaje)
        );
    }
}

if (!function_exists('renderAlertError')) {
    /**
     * Renderizar alerta de error
     * 
     * @param string $mensaje Mensaje de error
     * @return string HTML de la alerta
     */
    function renderAlertError($mensaje = 'Error al cargar los datos')
    {
        return sprintf(
            '<div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>%s
            </div>',
            esc($mensaje)
        );
    }
}

if (!function_exists('renderInfoRecurso')) {
    /**
     * Renderizar información completa del recurso (título, portada, ISBN)
     * 
     * @param array $recurso Datos del recurso
     * @param string $portadaSize Tamaño de la portada
     * @return string HTML de la información
     */
    function renderInfoRecurso($recurso, $portadaSize = 'small')
    {
        $portada = $recurso['portada'] ?? null;
        $titulo = $recurso['titulo'] ?? 'Sin título';
        $isbn = $recurso['isbn'] ?? null;
        
        $html = '<div class="d-flex align-items-center">';
        $html .= '<div class="me-3">' . renderPortadaRecurso($portada, $titulo, $portadaSize) . '</div>';
        $html .= '<div>';
        $html .= sprintf('<h6 class="mb-0 fw-semibold">%s</h6>', esc($titulo));
        
        if (!empty($isbn)) {
            $html .= renderISBN($isbn);
        }
        
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
}

if (!function_exists('renderBotonAccion')) {
    /**
     * Renderizar botón de acción con accesibilidad
     * 
     * @param array $config Configuración del botón ['tipo', 'icono', 'titulo', 'onclick', 'disabled', 'dataAttrs']
     * @return string HTML del botón
     */
    function renderBotonAccion($config)
    {
        $tipo = $config['tipo'] ?? 'primary';
        $icono = $config['icono'] ?? 'circle';
        $titulo = $config['titulo'] ?? '';
        $onclick = $config['onclick'] ?? '';
        $disabled = $config['disabled'] ?? false;
        $dataAttrs = $config['dataAttrs'] ?? [];
        
        $dataString = '';
        foreach ($dataAttrs as $key => $value) {
            $dataString .= sprintf(' data-%s="%s"', esc($key), esc($value));
        }
        
        $disabledAttr = $disabled ? ' disabled' : '';
        $onclickAttr = !empty($onclick) ? sprintf(' onclick="%s"', esc($onclick)) : '';
        
        return sprintf(
            '<button class="btn btn-sm btn-%s"%s%s title="%s" aria-label="%s"%s>
                <i class="fas fa-%s"></i>
            </button>',
            esc($tipo),
            $disabledAttr,
            $onclickAttr,
            esc($titulo),
            esc($titulo),
            $dataString,
            esc($icono)
        );
    }
}

if (!function_exists('renderGrupoAcciones')) {
    /**
     * Renderizar grupo de botones de acción
     * 
     * @param array $botones Array de configuraciones de botones
     * @return string HTML del grupo de botones
     */
    function renderGrupoAcciones($botones)
    {
        $html = '<div class="d-flex gap-2 justify-content-center">';
        
        foreach ($botones as $boton) {
            $html .= renderBotonAccion($boton);
        }
        
        $html .= '</div>';
        
        return $html;
    }
}

if (!function_exists('sanitizeIdRecurso')) {
    /**
     * Sanitizar ID de recurso
     * 
     * @param mixed $id ID a sanitizar
     * @return int|null ID sanitizado o null si es inválido
     */
    function sanitizeIdRecurso($id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        
        return ($id !== false && $id > 0) ? $id : null;
    }
}

if (!function_exists('formatearNombreAutor')) {
    /**
     * Formatear nombre del autor
     * 
     * @param string|null $nombreAutor Nombre del autor
     * @return string Nombre formateado o texto por defecto
     */
    function formatearNombreAutor($nombreAutor)
    {
        return !empty($nombreAutor) ? esc($nombreAutor) : 'Sin autor';
    }
}

if (!function_exists('renderEstadoVacio')) {
    /**
     * Renderizar estado vacío genérico
     * 
     * @param array $config ['icono', 'titulo', 'mensaje', 'botones']
     * @return string HTML del estado vacío
     */
    function renderEstadoVacio($config)
    {
        $icono = $config['icono'] ?? 'inbox';
        $titulo = $config['titulo'] ?? 'No hay elementos';
        $mensaje = $config['mensaje'] ?? '';
        $botones = $config['botones'] ?? [];
        
        $html = '<div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-' . esc($icono) . ' fa-4x text-muted opacity-50"></i>
                </div>
                <h4 class="text-muted mb-3">' . esc($titulo) . '</h4>';
        
        if (!empty($mensaje)) {
            $html .= '<p class="text-muted mb-4 lead">' . esc($mensaje) . '</p>';
        }
        
        if (!empty($botones)) {
            $html .= '<div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">';
            
            foreach ($botones as $boton) {
                $url = $boton['url'] ?? '#';
                $texto = $boton['texto'] ?? 'Botón';
                $icono = $boton['icono'] ?? 'arrow-right';
                $tipo = $boton['tipo'] ?? 'primary';
                $outline = $boton['outline'] ?? false;
                $claseBoton = $outline ? "btn-outline-{$tipo}" : "btn-{$tipo}";
                
                $html .= sprintf(
                    '<a href="%s" class="btn %s btn-lg">
                        <i class="fas fa-%s me-2"></i>%s
                    </a>',
                    esc($url),
                    esc($claseBoton),
                    esc($icono),
                    esc($texto)
                );
            }
            
            $html .= '</div>';
        }
        
        $html .= '</div></div>';
        
        return $html;
    }
}

/*
Sidebar Menu - Versión Minimalista
Author: Sistema Biblioteca HZG
*/

(function($) {
  'use strict';

  // Configuración básica
  const CONFIG = {
    activeClass: 'active',
    expandedClass: 'in'
  };

  // Utilidades simples
  const Utils = {
    getCurrentPath: function() {
      return window.location.href;
    },

    findActiveElement: function() {
      const url = this.getCurrentPath();
      const anchors = document.querySelectorAll("#sidebarnav a");
      
      for (const anchor of anchors) {
        if (anchor.href === url) {
          return anchor;
        }
      }
      return null;
    },

    // Nuevo método para encontrar elemento por URL parcial
    findElementByPartialUrl: function(targetUrl) {
      const anchors = document.querySelectorAll("#sidebarnav a");
      
      for (const anchor of anchors) {
        // Comparar rutas sin dominio
        const anchorPath = new URL(anchor.href).pathname;
        const targetPath = new URL(targetUrl, window.location.origin).pathname;
        
        if (anchorPath === targetPath) {
          return anchor;
        }
      }
      return null;
    }
  };

  // Clase principal simplificada
  class SidebarMenu {
    constructor() {
      this.$sidebar = $('#sidebarnav');
      this.$sidebarContainer = $('.sidebar-hzg');
      this.isMinimized = false;
      this.init();
    }

    init() {
      this.setActiveLink();
      this.bindEvents();
      this.setupControls();
      this.loadSavedState();
    }

    setupControls() {
      // Botón minimizar
      $(document).on('click', '#sidebarMinimize', (e) => {
        e.preventDefault();
        this.toggleMinimize();
      });
    }

    toggleMinimize() {
      this.isMinimized = !this.isMinimized;
      
      if (this.isMinimized) {
        this.$sidebarContainer.addClass('sidebar-minimized');
        this.$sidebar.find('ul.collapse.in').removeClass('in');
        this.$sidebar.find('.has-arrow.active').removeClass('active');
      } else {
        this.$sidebarContainer.removeClass('sidebar-minimized');
      }
      
      localStorage.setItem('sidebarMinimized', this.isMinimized);
    }

    loadSavedState() {
      if (localStorage.getItem('sidebarMinimized') === 'true') {
        this.toggleMinimize();
      }
    }

    setActiveLink(targetUrl = null) {
      let activeElement;
      
      if (targetUrl) {
        activeElement = Utils.findElementByPartialUrl(targetUrl);
      } else {
        activeElement = Utils.findActiveElement();
      }
      
      if (!activeElement) return;

      // Solo limpiar estados activos de enlaces, no de submenús
      this.clearActiveLinkStates();
      activeElement.classList.add(CONFIG.activeClass);
      this.handleParentElements(activeElement);
    }

    clearActiveStates() {
      this.$sidebar.find('a').removeClass(CONFIG.activeClass);
      this.$sidebar.find('ul').removeClass(CONFIG.expandedClass);
    }

    // Nueva función que solo limpia enlaces activos pero mantiene submenús abiertos
    clearActiveLinkStates() {
      this.$sidebar.find('a').removeClass(CONFIG.activeClass);
    }

    handleParentElements(element) {
      const $element = $(element);
      const $parentUl = $element.closest('ul.collapse');
      
      if ($parentUl.length) {
        $parentUl.addClass(CONFIG.expandedClass);
        $parentUl.prev('a').addClass(CONFIG.activeClass);
      }
    }

    // Método mejorado para mantener el estado del submenú
    setActiveLinkAndMaintainSubmenu(targetUrl) {
      // Solo limpiar enlaces activos, no submenús
      this.$sidebar.find('a').removeClass(CONFIG.activeClass);
      
      let activeElement;
      if (targetUrl) {
        activeElement = Utils.findElementByPartialUrl(targetUrl);
      } else {
        activeElement = Utils.findActiveElement();
      }
      
      if (activeElement) {
        const $activeEl = $(activeElement);
        $activeEl.addClass(CONFIG.activeClass);
        
        // Solo abrir el submenú padre del elemento activo
        const $parentUl = $activeEl.closest('ul.collapse');
        if ($parentUl.length) {
          $parentUl.addClass(CONFIG.expandedClass);
          const $parentTrigger = $parentUl.prev('a.has-arrow');
          $parentTrigger.addClass(CONFIG.activeClass);
        }
      }
    }

    bindEvents() {
      // Toggle de dropdown
      this.$sidebar.on('click', '.has-arrow', (e) => {
        e.preventDefault();
        this.handleDropdownToggle($(e.currentTarget));
      });

      // Enlaces regulares
      this.$sidebar.on('click', 'a:not(.has-arrow)', (e) => {
        this.handleLinkClick($(e.currentTarget));
      });

      // Mejorar la experiencia visual con hover en submenús
      this.$sidebar.on('mouseenter', '.collapse .sidebar-item a', function() {
        $(this).addClass('hover-highlight');
      });

      this.$sidebar.on('mouseleave', '.collapse .sidebar-item a', function() {
        $(this).removeClass('hover-highlight');
      });
    }

    handleDropdownToggle($trigger) {
      const $submenu = $trigger.next('ul.collapse');
      const isCurrentlyExpanded = $submenu.hasClass(CONFIG.expandedClass);
      
      // Cerrar todos los otros submenús en el mismo nivel
      const $siblingMenus = $trigger.closest('ul').find('> li > ul.collapse.' + CONFIG.expandedClass);
      $siblingMenus.not($submenu).removeClass(CONFIG.expandedClass);
      $siblingMenus.not($submenu).prev('a.has-arrow').removeClass(CONFIG.activeClass);
      
      // Toggle del submenú actual
      if (isCurrentlyExpanded) {
        $submenu.removeClass(CONFIG.expandedClass);
        $trigger.removeClass(CONFIG.activeClass);
      } else {
        $submenu.addClass(CONFIG.expandedClass);
        $trigger.addClass(CONFIG.activeClass);
      }
    }

    handleLinkClick($link) {
      // Limpiar solo enlaces activos
      this.$sidebar.find('a').removeClass(CONFIG.activeClass);
      
      // Marcar el enlace clickeado como activo
      $link.addClass(CONFIG.activeClass);
      
      // Si es un enlace de submenú, mantener el padre activo también
      const $parentUl = $link.closest('ul.collapse');
      if ($parentUl.length) {
        // Asegurar que el submenú permanezca abierto
        $parentUl.addClass(CONFIG.expandedClass);
        // Marcar el trigger padre como activo
        const $parentTrigger = $parentUl.prev('a.has-arrow');
        $parentTrigger.addClass(CONFIG.activeClass);
      }
    }

    // Método refresh mejorado que mantiene submenús abiertos
    refresh(targetUrl = null) {
      this.setActiveLinkAndMaintainSubmenu(targetUrl);
    }

    // Método para refresh completo (limpia todo)
    fullRefresh() {
      this.clearActiveStates();
      this.setActiveLink();
    }

    // Restaurar menús expandidos después del refresh
    restoreExpandedMenus() {
      // No restaurar automáticamente todos los menus - causar problemas
      // Solo mantener el menú del elemento activo abierto
    }
  }

  // Inicialización simplificada
  $(function() {
    const sidebarMenu = new SidebarMenu();
    
    // Método global para contenido dinámico que mantiene submenús abiertos
    window.initSidebarMenu = function(targetUrl = null) {
      sidebarMenu.refresh(targetUrl);
    };

    // Método global para refresh completo
    window.refreshSidebarMenu = function() {
      sidebarMenu.fullRefresh();
    };
  });

})(jQuery);
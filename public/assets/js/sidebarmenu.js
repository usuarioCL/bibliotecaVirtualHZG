/*
Template Name: Admin Template
Author: Wrappixel

File: js
*/
// ==============================================================
// Auto select left navbar
// ==============================================================
$(function () {
  "use strict";
  // Activar el enlace actual
  var url = window.location + "";
  var path = url.replace(
    window.location.protocol + "//" + window.location.host + "/",
    ""
  );
  var element = $("ul#sidebarnav a").filter(function () {
    return this.href === url || this.href === path;
  });

  function findMatchingElement() {
    var currentUrl = window.location.href;
    var anchors = document.querySelectorAll("#sidebarnav a");
    for (var i = 0; i < anchors.length; i++) {
      if (anchors[i].href === currentUrl) {
        return anchors[i];
      }
    }
    return null;
  }
  var elements = findMatchingElement();
  if (elements) {
    elements.classList.add("active");
  }
  document
    .querySelectorAll("ul#sidebarnav ul li a.active")
    .forEach(function (link) {
      link.closest("ul").classList.add("in");
      link.closest("ul").parentElement.classList.add("selected");
    });
  document.querySelectorAll("#sidebarnav li").forEach(function (li) {
    const isActive = li.classList.contains("selected");
    if (isActive) {
      const anchor = li.querySelector("a");
      if (anchor) {
        anchor.classList.add("active");
      }
    }
  });

  // Delegación de eventos para submenús
  $(document).on('click', '#sidebarnav .has-arrow', function (e) {
    e.preventDefault();
    var $this = $(this);
    var submenu = $this.next('ul');
    var parentUl = $this.closest('ul');
    // Cierra todos los submenús abiertos excepto el actual
    parentUl.find('ul.in').not(submenu).removeClass('in');
    parentUl.find('.has-arrow.active').not($this).removeClass('active');
    // Alterna el submenú actual
    submenu.toggleClass('in');
    $this.toggleClass('active');
    // Marca el padre como seleccionado si está abierto
    if (submenu.hasClass('in')) {
      $this.parent().addClass('selected');
    } else {
      $this.parent().removeClass('selected');
    }
  });

  // Mantener el estado activo al navegar dinámicamente
  window.initSidebarMenu = function () {
    $("ul#sidebarnav a").removeClass("active");
    $("ul#sidebarnav li").removeClass("selected");
    var currentUrl = window.location.href;
    var anchors = document.querySelectorAll("#sidebarnav a");
    anchors.forEach(function (a) {
      if (a.href === currentUrl) {
        a.classList.add("active");
        var parentLi = a.closest("li.sidebar-item");
        if (parentLi) parentLi.classList.add("selected");
        var parentUl = a.closest("ul.collapse");
        if (parentUl) parentUl.classList.add("in");
      }
    });
  };
});
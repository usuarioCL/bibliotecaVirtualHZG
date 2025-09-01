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
    var isActive = $this.hasClass('active');
    var parentUl = $this.closest('ul');
    if (!isActive) {
      parentUl.find('ul').removeClass('in');
      parentUl.find('a').removeClass('active');
      var submenu = $this.next('ul');
      if (submenu.length) {
        submenu.addClass('in');
      }
      $this.addClass('active');
    } else {
      $this.removeClass('active');
      parentUl.removeClass('active');
      var submenu = $this.next('ul');
      if (submenu.length) {
        submenu.removeClass('in');
      }
    }
  });
});
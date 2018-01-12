'use strict'

//Application de la clase active au li cliqué
$(document).ready(function() {
  var path = window.location.pathname;
  $("li[data-url='http://localhost:8000" + path + "']").addClass('active');
  console.log(path);
});

/* Add here all your JS customizations */
$(function() {
  $("#switcher-top").mouseover(function() {
    $("#button-access").toggleClass("fade show active");
    $("#switcher-top").toggleClass("fade show active");
  });
});
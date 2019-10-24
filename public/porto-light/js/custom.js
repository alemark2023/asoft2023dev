/* Add here all your JS customizations */
$(function() {
  $("#switcher-top").mouseenter(function() {
    $("#switcher-list").toggleClass("fade show active");
    $("#switcher-top").toggleClass("fade show active");
  });
  $("#switcher-list").mouseleave(function() {
    $("#switcher-list").toggleClass("fade show active");
    $("#switcher-top").toggleClass("fade show active");
  });
});
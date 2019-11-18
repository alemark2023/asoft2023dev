/* Add here all your JS customizations */
$(function() {
  $(".switcher-hover").mouseenter(function() {
    $("#switcher-list").toggleClass("fade show active");
    $("#switcher-top").toggleClass("fade show active");
  });
  $(".switcher-hover").mouseleave(function() {
    $("#switcher-list").toggleClass("fade show active");
    $("#switcher-top").toggleClass("fade show active");
  });
});
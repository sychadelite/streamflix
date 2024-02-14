$(window).on("resize", function () {
  if ($(this).width() >= 768) {
    $(".main_list").removeClass("show_list")
  }
});

$(document).ready(function () {
  $(".navTrigger").click(function () {
    $(this).toggleClass("active");
    $("#mainListDiv").toggleClass("show_list");
    $("#mainListDiv").fadeIn();
  });
});
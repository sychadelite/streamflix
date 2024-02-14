$(window).scroll(function () {
  handleNavSticky();
});

$(document).ready(function () {
  const osBody = initializeOverlayScroller(document.body);

  handleNavSticky(osBody);
});


// -------------------------- FUNCTIONS --------------------------

function handleNavSticky(osInstance) {
  // Native Scroll
  if ($(document).scrollTop() > 50) {
    $(".nav").addClass("affix");
    $(".nav .navTrigger i").css({
      backgroundColor: "white"
    })
  } else {
    $(".nav").removeClass("affix");
    $(".nav .navTrigger i").css({
      backgroundColor: "#FEFEFE"
    })
  }

  // External Scroll
  // Listen for scroll events on the OverlayScrollbars instance
  osInstance.getElements().viewport.addEventListener("scroll", function (event) {
    // Get the current vertical scroll position using OverlayScrollbars
    var scrollY = Math.round(osInstance.scroll().position.y);

    if (scrollY > 50) {
      $(".nav").addClass("affix");
      $(".nav .navTrigger i").css({
        backgroundColor: "white"
      });
    } else {
      $(".nav").removeClass("affix");
      $(".nav .navTrigger i").css({
        backgroundColor: "#FEFEFE"
      });
    }
  });
}
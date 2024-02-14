function initializeOverlayScroller(el) {
  var osInstance = OverlayScrollbars(el, {
    scrollbars: {
      autoHide: "scroll"
    },
    paddingAbsolute: true
  });

  return osInstance
}
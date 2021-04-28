/**
 * Back to top
 * @type {Element}
 */
var el = document.querySelector('[data-dynamic-block="back-to-top"]');
var headerEl = document.querySelector('[data-block="header"]')
var ACTIVE_EL_CLASS = 'back-to-top--visible';

if (el) {
  el.addEventListener('click', function() {
    var scrollToTop = window.setInterval(function() {
      var pos = window.pageYOffset;
      if ( pos > 0 ) {
        window.scrollTo( 0, pos - 20 ); // how far to scroll on each step
      } else {
        window.clearInterval( scrollToTop );
      }
    }, 16);
  })
}

window.addEventListener('scroll', function(ev) {
  var scrollTop = window.pageYOffset || document.body.scrollTop;
  var offset = headerEl ? headerEl.offsetHeight + 20 : 0;
  if (scrollTop > offset) {
    el.classList.add(ACTIVE_EL_CLASS)
  } else{
    el.classList.remove(ACTIVE_EL_CLASS)
  }
})

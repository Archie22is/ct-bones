/* global jQuery */
const $ = jQuery

const widgetProductCategories = () => {
  $(document).ready(function () {
    let catParent = $(
      '.widget_product_categories .product-categories .cat-item.cat-parent'
    )
    catParent.each(function () {
      if ($(this).find('ul.children').length) {
        $('<div class="woo-cat-toggle"></div>').insertBefore(
          $(this).find('> a')
        )
      }
    })
    $('.widget_product_categories .product-categories .woo-cat-toggle').click(
      function () {
        $(this).toggleClass('cat-popped')
      }
    )

    $(
      '.widget_product_categories .product-categories > .cat-item.cat-parent'
    ).each(function () {
      if ($(this).is('.current-cat, .current-cat-parent')) {
        let child = $(this).children('.woo-cat-toggle')
        child.toggleClass('cat-popped')
      }
    })
  })
}

export { widgetProductCategories }

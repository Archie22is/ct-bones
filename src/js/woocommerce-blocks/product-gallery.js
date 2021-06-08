/* global jQuery */
import { select, on } from 'lib/dom'
import carousel from 'lib/carousel'

const $ = jQuery

export default el => {
  const sliderEl = select('.js-slider', el)
  const navSliderEl = select('.js-nav-slider', el)
  // eslint-disable-next-line no-unused-vars
  let slider = null
  // eslint-disable-next-line no-unused-vars
  let navSlider = null

  if (sliderEl && navSliderEl) {
    slider = carousel(sliderEl)
    // eslint-disable-next-line no-unused-vars
    navSlider = carousel(navSliderEl, {
      asNavFor: sliderEl
    })
  }

  $(el).on('woocommerce_gallery_init_zoom', () => {
    if (slider) {
      slider.resize()
      // Back to first slider item to display variant image
      slider.select(0)
    }
  })

  on(
    'load',
    () => {
      if (slider) {
        slider.resize()
      }

      if (navSlider) {
        navSlider.resize()
      }
    },
    window
  )
}

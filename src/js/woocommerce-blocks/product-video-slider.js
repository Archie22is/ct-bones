/* global jQuery, wc_single_product_params */
import { getData, select, on, inViewPort } from 'lib/dom'
import { throttle } from 'lib/utils'

const $ = jQuery

const playIconSvg = `<svg enable-background="new 0 0 15 15" viewBox="0 0 15 15" x="0" y="0"><g opacity="0.54"><g><circle cx="7.5" cy="7.5" fill="#040000" r="7.3"></circle><path d="m7.5.5c3.9 0 7 3.1 7 7s-3.1 7-7 7-7-3.1-7-7 3.1-7 7-7m0-.5c-4.1 0-7.5 3.4-7.5 7.5s3.4 7.5 7.5 7.5 7.5-3.4 7.5-7.5-3.4-7.5-7.5-7.5z" fill="#ffffff"></path></g></g><path d="m6.1 5.1c0-.2.1-.3.3-.2l3.3 2.3c.2.1.2.3 0 .4l-3.3 2.4c-.2.1-.3.1-.3-.2z" fill="#ffffff"></path></svg>`

export default el => {
  const videoUrl = getData('video-url', el)
  const sliderWrapper = select('.js-slider-wrapper', el)
  const $variationForm = $('.variations_form')

  let $videoEl = null

  wc_single_product_params.flexslider.video = videoUrl.length

  if (videoUrl) {
    wc_single_product_params.photoswipe_enabled = false
    wc_single_product_params.zoom_enabled = false

    // Changing a slide: check if it has video. Work with both only 1 video or multiple videos
    wc_single_product_params.flexslider.after = function ($slider) {
      $slider.find('li').removeClass('is-active')
      $slider
        .find('li .flex-active')
        .parent()
        .addClass('is-active')

      const $currentSlide = $slider.find('.flex-active-slide')
      if ($currentSlide.hasClass('has-video')) {
        $videoEl = $currentSlide.find('.js-video')

        if ($videoEl.length) {
          $videoEl[0].play()
        }
      }
    }

    wc_single_product_params.flexslider.start = function ($slider) {
      $slider
        .find('li .flex-active')
        .parent()
        .addClass('has-video-icon is-active')
        .append(playIconSvg)

      const $videoEl = $slider.find('.flex-active-slide .js-video')

      if ($videoEl.length) {
        $videoEl[0].play()
      }
    }

    $variationForm.on('change', () => {
      const $currentSlide = $(sliderWrapper).find('.flex-active-slide')
      const $currentNav = $(sliderWrapper).find('li.is-active')

      if ($currentSlide.hasClass('has-video')) {
        $currentSlide
          .removeClass('has-video')
          .addClass('has-variation-change')
          .find('.js-video')
          .remove()
      }

      if ($currentNav.hasClass('has-video-icon')) {
        $currentNav
          .removeClass('has-video-icon')
          .addClass('has-variation-change')
      }
    })
  }

  // Display video play when outside of scroll
  const checkVideoPlay = () => {
    if ($videoEl) {
      if (inViewPort(sliderWrapper)) {
        $videoEl[0].play()
      } else {
        $videoEl[0].pause()
      }
    }
  }

  on('scroll', throttle(checkVideoPlay, 300), window)
}

/* global jQuery, wc_single_product_params */
import { getData, select, selectAll, on, inViewPort } from 'lib/dom'
import { throttle } from 'lib/utils'

const $ = jQuery

const videoMarkup = videoUrl => `
  <div class="product-gallery__video-wrapper js-video-wrapper">
    <video class="product-gallery__video js-video" muted="" playsinline="" loop="">
      <source src="${videoUrl}" type="video/mp4">
    </video>
  </div>
`

const playIconSvg = `<svg enable-background="new 0 0 15 15" viewBox="0 0 15 15" x="0" y="0"><g opacity="0.54"><g><circle cx="7.5" cy="7.5" fill="#040000" r="7.3"></circle><path d="m7.5.5c3.9 0 7 3.1 7 7s-3.1 7-7 7-7-3.1-7-7 3.1-7 7-7m0-.5c-4.1 0-7.5 3.4-7.5 7.5s3.4 7.5 7.5 7.5 7.5-3.4 7.5-7.5-3.4-7.5-7.5-7.5z" fill="#ffffff"></path></g></g><path d="m6.1 5.1c0-.2.1-.3.3-.2l3.3 2.3c.2.1.2.3 0 .4l-3.3 2.4c-.2.1-.3.1-.3-.2z" fill="#ffffff"></path></svg>`

export default el => {
  const videoUrl = getData('video-url', el)
  const videoHtml = videoMarkup(videoUrl)
  const sliderEl = select('.js-slider', el)

  $('.variations_form').on('change', e => {
    console.log(e.target)
  })

  const initVideoOnFirstSlide = $slider => {
    $slider.find('.flex-active-slide').addClass('has-video')
    $slider.find('.flex-active-slide a').append(videoHtml)
    $slider.find('.flex-active-slide .js-video')[0].play()

    $(el).find('.flex-control-nav li').first().addClass('is-active has-video-icon').append(playIconSvg)
  }

  const updateVideoAction = $slider => {
    const $videoEls = $slider.find('.js-video')
    const $currentSlide = $slider.find('.flex-active-slide')
    const $currentVideo = $currentSlide.find('.js-video')

    if ($videoEls.length) {
      $videoEls.each(function (index, videoEl) {
        if ($currentVideo && videoEl === $currentVideo[0]) {
          videoEl.play()
        } else {
          videoEl.pause()
        }
      })
    }
  }

  wc_single_product_params.flexslider.video = videoUrl.length

  if (videoUrl) {
    wc_single_product_params.photoswipe_enabled = false
    wc_single_product_params.zoom_enabled = false

    // Add video and play when first slide has been init
    wc_single_product_params.flexslider.start = function ($slider) {
      initVideoOnFirstSlide($slider)
    }

    // Changing a slide: check if it has video. Work with both only 1 video or multiple videos
    wc_single_product_params.flexslider.after = function ($slider) {
      updateVideoAction($slider)

      $slider.find('li').removeClass('is-active')
      $slider.find('li .flex-active').parent().addClass('is-active')
    }
  }

  console.log(wc_single_product_params)

  // Display video play when outside of scroll
  const checkVideoPlay = () => {
    const videoEls = selectAll('.js-video', sliderEl)

    if (!inViewPort(sliderEl)) {
      // Pause all videos
      videoEls.forEach(videoEl => {
        videoEl.pause()
      })
    } else {
      // Play video in current slide
      const currentVideo = select('.flex-active-slide .js-video')

      if (currentVideo) {
        currentVideo.play()
      }
    }
  }

  on(
    'scroll',
    throttle(checkVideoPlay, 300),
    window
  )
}

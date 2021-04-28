import { select, selectAll, on, getHeight, setStyle } from 'lib/dom'
import carousel from 'lib/carousel'
import { map } from 'lib/utils'

export default el => {
  const list = select('.js-slider', el)
  const previous = select('.js-button--previous', el)
  const next = select('.js-button--next', el)

  const imageEl = select('.js-slider-image', el)
  let slider = null

  const buttons = selectAll('.js-button--previous, .js-button--next', el)

  const setHeightButton = () => {
    if (imageEl) {
      const imageHeight = getHeight(imageEl)

      map(button => {
        setStyle('top', imageHeight / 2, button)
      }, buttons)
    }
  }

  if (list) {
    slider = carousel(list)

    if (previous) {
      on(
        'click',
        () => {
          slider.previous()
        },
        previous
      )
    }

    if (next) {
      on(
        'click',
        () => {
          slider.next()
        },
        next
      )
    }
  }

  setHeightButton()

  on('resize', setHeightButton(), window)
}

import Flickity from 'flickity'
import { getModuleOptions, on, addClass, removeClass } from 'lib/dom'
import { throttle } from 'lib/utils'
require('flickity-as-nav-for')

const MODULE_NAME = 'carousel'
const INIT_CLASS = 'is-initialized'

export default (el, options = {}) => {
  const defaults = {
    prevNextButtons: true,
    pageDots: true,
    cellAlign: 'left',
    percentPosition: false,
    items: 1,
    watchCSS: false,
    arrowShape: {
      x0: 10,
      x1: 50,
      y1: 50,
      x2: 55,
      y2: 45,
      x3: 20
    }
  }
  const args = getModuleOptions(MODULE_NAME, el, defaults)
  const finalArgs = { ...args, ...options }
  if (el.childElementCount > args.items) {
    const flickity = new Flickity(el, finalArgs)

    const resize = () => addClass(INIT_CLASS, el)
    const reset = () => removeClass(INIT_CLASS, el)
    const handler = () => {
      reset()
      flickity.resize()
      resize()
    }
    on('change', handler, window)
    on('resize', throttle(handler, 300), window)
    on('load', handler, window)

    return flickity
  }

  return null
}

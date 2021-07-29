import {
  on,
  select,
  hasClass,
  removeClass,
  inViewPort,
  trigger,
  loadNoscriptContent
} from 'lib/dom'
import { throttle } from 'lib/utils'

const LOADING_CLASS = 'is-loading'

export default el => {
  const contentEl = select('.js-main-content', el)

  const initLoad = () => {
    if (inViewPort(contentEl) && hasClass(LOADING_CLASS, el)) {
      loadNoscriptContent(contentEl)

      removeClass(LOADING_CLASS, el)
    }
  }

  on('scroll', throttle(initLoad, 100), window)

  on('load', throttle(initLoad, 100), window)

  setTimeout(() => {
    trigger('loaded', el)
  }, 10000)
}

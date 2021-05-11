import { select, on, getData, addClass, removeClass } from 'lib/dom'

const VISIBLE_CLASS = 'is-content-visible'

export default el => {
  const contentEl = select('.js-content', el)
  const maxHeight = getData('max-height', el)
  const openEl = select('.js-open-trigger', el)

  const init = () => {
    const contentHeight = contentEl.scrollHeight || contentEl.offsetHeight

    if (contentHeight <= maxHeight) {
      addClass(VISIBLE_CLASS, el)
      contentEl.setAttribute('style', `height: auto`)
    } else {
      contentEl.setAttribute('style', `height: ${maxHeight}px`)
      removeClass(VISIBLE_CLASS, el)
    }
  }

  if (openEl) {
    on(
      'click',
      () => {
        addClass(VISIBLE_CLASS, el)
        contentEl.setAttribute('style', 'height: auto')
      },
      openEl
    )
  }

  init()
}

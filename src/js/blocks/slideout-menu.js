import {
  on,
  select,
  selectAll,
  getData,
  addClass,
  removeClass,
  toggleClass,
  closest,
  trigger
} from '../lib/dom'
require('whatwg-fetch')

const body = document.body
const SLIDEOUT_VISIBLE_CLASS = 'is-slideout-visible'

export default el => {
  const closeEls = selectAll('.js-mobile-menu-close', el)
  const menuWrapperEl = select('.js-menu-wrapper', el)
  const endpoint = getData('mobile-endpoint', el) + '?view=mobile'
  let loaded = false
  let triggers = []
  on(
    'slideout.visible',
    () => {
      if (!loaded) {
        addClass('is-loading', el)

        window
          .fetch(endpoint)
          .then(response => {
            return response.json()
          })
          .then(data => {
            if (data.html && menuWrapperEl) {
              menuWrapperEl.innerHTML = data.html
            }
          })
          .then(() => {
            removeClass('is-loading', el)
            loaded = true
            triggers = selectAll('.js-toggle-sub-menu', el)

            if (triggers) {
              on(
                'click',
                e => {
                  const parentTrigger = closest(
                    '.menu-item-has-children',
                    e.target
                  )
                  toggleClass('is-active', e.target)
                  toggleClass('is-active', parentTrigger)
                },
                triggers
              )
            }
          })
      }
      addClass(SLIDEOUT_VISIBLE_CLASS, body)
    },
    body
  )

  on(
    'slideout.hidden',
    () => {
      removeClass(SLIDEOUT_VISIBLE_CLASS, body)
    },
    body
  )

  if (closeEls) {
    on(
      'click',
      () => {
        trigger('slideout.hidden', body)
      },
      closeEls
    )
  }
}

import {
  on,
  selectAll,
  setAttribute,
  trigger,
  addClass,
  removeClass
} from 'lib/dom'

export default (el, customOptions = {}) => {
  const defaultOptions = {
    tabNavSelector: '[role="tab"]',
    tabPanelSelector: '[role="tabpanel"]',
    activeNavClass: 'is-active',
    activePanelClass: 'is-active',
    lazyload: true,
    lazyloadCallback: function () {}
  }

  const options = { ...defaultOptions, ...customOptions }
  const navItems = selectAll(options.tabNavSelector, el)
  const panels = selectAll(options.tabPanelSelector, el)

  on(
    'update',
    e => {
      for (let index = 0; index < navItems.length; index++) {
        if (index === e.detail.currentIndex) {
          setAttribute('aria-selected', 'true', navItems[index])
          addClass(options.activeNavClass, navItems[index])

          setAttribute('aria-expanded', 'true', panels[index])
          addClass(options.activePanelClass, panels[index])

          if (options.lazyload) {
            checkTabPanelLoad(panels[index])

            if (typeof options.lazyloadCallback === 'function') {
              options.lazyloadCallback(navItems[index], panels[index])
            }
          }

          navItems[index].focus()
        } else {
          setAttribute('aria-selected', 'false', navItems[index])
          removeClass(options.activeNavClass, navItems[index])

          setAttribute('aria-expanded', 'false', panels[index])
          removeClass(options.activePanelClass, panels[index])
        }
      }
    },
    el
  )

  const checkTabPanelLoad = tabPanel => {
    const contextEls = tabPanel.getElementsByTagName('noscript')

    if (!contextEls || !contextEls.length) {
      return false
    }

    const content = contextEls[0].textContent || contextEls[0].innerHTML

    tabPanel.innerHTML = content
  }

  on(
    'click',
    e => {
      const navItem = e.target

      trigger(
        {
          event: 'update',
          data: {
            currentIndex: navItems.indexOf(navItem)
          }
        },
        el
      )
    },
    navItems
  )

  on(
    'load',
    () => {
      trigger(
        {
          event: 'update',
          data: {
            currentIndex: 0
          }
        },
        el
      )
    },
    window
  )
}

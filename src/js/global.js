import { select, selectAll } from 'lib/dom'
import { initStyle } from 'lib/scripts'

const blocks = document.querySelectorAll('[data-block]')

const initBlocks = () => {
  if (blocks) {
    blocks.forEach(block => {
      const blockName = block.getAttribute('data-block')
      if (!blockName) {
        return
      }

      require(`./blocks/${blockName}.js`).default(block)
    })
  }
}

const checkjQueryUIStyle = () => {
  const datePickerEl = select('.ui-date-picker')
  const datePickerTrigger = selectAll('.datepicker')

  if (datePickerEl && datePickerTrigger) {
    setTimeout(() => {
      initStyle('https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css')
    }, 10000)
  }
}

document.addEventListener('DOMContentLoaded', () => {
  initBlocks()
  checkjQueryUIStyle()
})

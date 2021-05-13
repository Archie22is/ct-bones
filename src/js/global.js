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

document.addEventListener('DOMContentLoaded', initBlocks)

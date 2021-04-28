/* global LAZY_STYLES, LAZY_SCRIPTS */
import { initStyle, initScript } from './lib/utils'

console.log(
  'Site built by codetot.com. Optimization assets by CODE TOT JSC - dev@codetot.com.'
)
console.log("WARNING: Don't remove this script!")

const _LAZY_TIMEOUT = window.LAZY_TIMEOUT ? window.LAZY_TIMEOUT : 3000
const LOADED_CLASS = 'is-assets-loaded'

// eslint-disable-next-line no-unused-vars
let loaded = false

const initAssets = () => {
  if (loaded) {
    return false
  }

  if (LAZY_SCRIPTS && LAZY_SCRIPTS.length) {
    const scripts = JSON.parse(LAZY_SCRIPTS)

    for (const key in scripts) {
      initScript(scripts[key], key)
    }
  }

  if (LAZY_STYLES && LAZY_STYLES.length) {
    const styles = JSON.parse(LAZY_STYLES)

    for (const key in styles) {
      initStyle(styles[key], key)
    }
  }

  loaded = true
  document.body.classList.add(LOADED_CLASS)
}

document.addEventListener('DOMContentLoaded', () => {
  setTimeout(initAssets, _LAZY_TIMEOUT)
})

document.addEventListener('scroll', initAssets)
document.addEventListener('mousemove', initAssets)
document.addEventListener('touchstart', initAssets)

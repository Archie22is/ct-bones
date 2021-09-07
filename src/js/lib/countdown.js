const renderHtml = () => {
	return `
    <span class="single-product__price__bottom">
      <span class="single-product__price__label js-label"></span>
      <span class="single-product__price__countdown js-countdown">
        <span class="single-product__price__item single-product__price__days js-days"></span>
        <span class="single-product__price__item single-product__price__hours js-hours"></span>
        <span class="single-product__price__item single-product__price__minutes js-minutes"></span>
        <span class="single-product__price__item single-product__price__seconds js-seconds"></span>
      </span>
    </span>
    <span class="single-product__price__notice js-notice"></span>
  `
}

export { renderHtml }

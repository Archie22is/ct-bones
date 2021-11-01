import {
	addClass,
	removeClass,
	hasClass,
	toggleClass,
	getAttribute,
	setAttribute,
	getData,
	setData,
	setStyle,
	select,
	selectAll,
	remove
} from './dom'

beforeEach(() => {
	document.body.innerHTML = `<div class="one two three"></div>`
})

const getElementClass = el => el.className

test('test addClass', () => {
	const targetEl = document.body.querySelector('.one')
	addClass('fourth', targetEl)

	expect(getElementClass(targetEl)).toEqual('one two three fourth')
})

test('test removeClass', () => {
	const targetEl = document.body.querySelector('.one')
	removeClass('three', targetEl)

	expect(getElementClass(targetEl)).toEqual('one two')
})

test('test hasClass', () => {
	const targetEl = document.body.querySelector('.one')

	expect(hasClass('two', targetEl)).toEqual(true)
})

test('test toggleClass', () => {
	const targetEl = document.body.querySelector('.one')
	toggleClass('two', targetEl)

	expect(targetEl.classList.contains('two')).toEqual(false)
})

test('test getAttribute', () => {
	const targetEl = document.body.querySelector('.one')

	targetEl.setAttribute('style', `background-color: #eee;`)

	expect(getAttribute('style', targetEl)).toEqual(`background-color: #eee;`)
})

test('test setAttribute', () => {
	const targetEl = document.body.querySelector('.one')
	setAttribute('style', 'color: #fff;', targetEl)

	expect(targetEl.getAttribute('style')).toEqual(`color: #fff;`)
})

test('test getData', () => {
	const targetEl = document.body.querySelector('.one')

	targetEl.setAttribute('data-block', `test`)

	expect(getData('block', targetEl)).toEqual('test')
})

test('test setData', () => {
	const targetEl = document.body.querySelector('.one')
	setData('block', 'test', targetEl)

	expect(targetEl.getAttribute('data-block')).toEqual('test')
})

test('test setStyle', () => {
	const targetEl = document.body.querySelector('.one')
	setStyle('display', 'block', targetEl)

	expect(targetEl.style.display === 'block').toBe(true)
})

test('test select', () => {
	const targetEl = document.body.querySelector('.one')
	const selectTargetEl = select('.one')

	expect(targetEl === selectTargetEl).toEqual(true)
})

test('test selectAll', () => {
	const targetEls = selectAll('.one')

	expect(targetEls.length === 1).toEqual(true)
})

test('test remove', () => {
	const targetEl = document.querySelector('.one')
	remove(targetEl)

	expect(document.querySelector('.one') === null).toBe(true)
})

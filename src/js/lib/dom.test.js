import { addClass, removeClass, hasClass } from './dom'

beforeEach(() => {
	document.body.innerHTML = `<div class="one two three"></div>`
})

const getElementClass = el => el.className;

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


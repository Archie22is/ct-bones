import { getData } from 'lib/dom'

export default el => {
  const options = getData('options', el)

  console.log(options)
}

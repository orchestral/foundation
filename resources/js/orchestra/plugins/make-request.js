import $ from '../../vendor/jquery'
import Javie from '../../vendor/javie'

class MakeRequest {
  constructor(method, to) {
    this.method = method.toUpperCase()
    this.to = to
  }

  dispatch() {
    if (this.method === 'GET') {
      return
    }

    $(this.createForm())
      .appendTo('body')
      .submit()
  }

  createForm() {
    const token = Javie.get('csrf.token')

    return `<form action="${this.to}" method="POST" style="display: none;">
      <input type="hidden" name="_method" value="${this.method}">
      <input type="hidden" name="_token" value="${token}">
    </form>`
  }
}

export default MakeRequest

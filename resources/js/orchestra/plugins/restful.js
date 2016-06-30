import _ from '../../vendor/underscore'
import $ from '../../vendor/jquery'

class Restful {
  constructor(method, url, data = {}) {
    method = method.toUpperCase()

    this.data = data

    if (method !== 'POST') {
      this.data._method = method
    }

    this.url = this.parseUrl(url)
  }

  dispatch() {
    if (this.method === 'GET') {
      return
    }

    $(this.createForm())
      .appendTo('body')
      .submit()
  }

  parseUrl(url) {
    let startIndex = url.indexOf('?')
    let hasParam = (startIndex > -1)

    if (hasParam) {
      let params = url.substr(startIndex + 1).split('#')[0].split('&')
      url = url.substr(0, startIndex)

      _.forEach(params, (query) => {
        let pair = query.split('=')

        this.data[pair[0]] = pair[1]
      })
    }

    return url
  }

  createForm() {
    return `<form action="${this.url}" method="POST" style="display: none;">
      ${this.createHiddenFields()}
    </form>`
  }

  createHiddenFields() {
    let hidden = ''

    _.forEach(this.data, (value, key) => {
      hidden += `<input type="hidden" name="${decodeURIComponent(key)}" value="${decodeURIComponent(value)}">`
    })

    return hidden
  }
}

export default Restful

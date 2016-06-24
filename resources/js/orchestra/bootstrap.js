import Restful from './plugins/restful'
import $ from '../vendor/jquery'
import Javie from '../vendor/javie'

class Bootstrap {
  select2() {
    $('select.form-control, .tabular-form > select').each((i, item) => {
      let selector = $(item)

      if (selector.is('[role!="agreement"]') && selector.is('[role!="native"]')) {
        selector.select2().removeClass('form-control')
      }
    })

    return this
  }

  switcher() {
    const switchers = $('select[role="agreement"]')

    switchers.removeClass('form-control')
      .each((i, item) => {
        let switcher = $(item)
        switcher.toggleSwitch({
          highlight: switcher.data('highlight'),
          width: 25,
          change: (e, target) => {
            Javie.trigger('switcher.change', [switcher, e])
          }
        })
        switcher.css('display', 'none')
      })

    return this
  }

  restful() {
    $('body').on('click', '[data-method]', function (e) {
      e.preventDefault()
      e.stopPropagation()

      let element = $(this)
      let method = element.data('method')
      let href = element.attr('href')
      let rest = new Restful(method, href, {_token: Javie.get('csrf.token')})

      rest.dispatch()

      return false
    })

    return this
  }
}

export default Bootstrap

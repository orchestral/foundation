import $ from '../vendor/jquery'

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
}

(new Bootstrap())
  .select2()
  .switcher()


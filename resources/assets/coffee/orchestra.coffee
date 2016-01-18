root = @
Javie = root.Javie
jQuery = root.jQuery

setup_button_group = ($) ->
  group = $ @
  form = group.parents('form').eq 0
  name = group.attr 'data-toggle-name'
  hidden = $ "input[name='#{name}']", form
  buttons = $ 'button', group

  set_active = (button) ->
    button.addClass 'active' if button.val() is hidden.val()
    return

  buttons.each (i, item) ->
    button = $ item
    button.on 'click', ->
      self = $ @
      buttons.removeClass 'active'
      hidden.val self.val()

      set_active self
      return

    set_active button
    return

  return

setup_helper = ($) ->
  $ 'input[type="date"]'
    .datepicker { dateFormat: "yy-mm-dd" }
  $ 'select[role!="native"], select.form-control[role!="agreement"], .navbar-form > select[role!="agreement"]'
    .select2()
    .removeClass 'form-control'
  $ '*[role="tooltip"]'
    .tooltip()
  return

setup_pagination = ($) ->
  $ 'div.pagination > ul'
    .each (i, item) ->
      $ item
        .addClass 'pagination'
        .parent()
        .removeClass 'pagination'
      return
  return

setup_agreement = ($) ->
  switchers = $ 'select[role="agreement"]'
  switchers
    .removeClass 'form-control'
    .each (i, item) ->
      switcher = $ item
      switcher.toggleSwitch
        highlight: switcher.data 'highlight'
        width: 25
        change: (e, target) ->
          Javie.trigger 'switcher.change', [switcher, e]
          return

      switcher.css 'display', 'none'
      return
  return

jQuery ($) ->
  setup_agreement $
  setup_button_group $
  setup_helper $
  setup_pagination $
  return


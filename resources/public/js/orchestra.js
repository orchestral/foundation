(function() {
  var Javie, jQuery, root, setup_agreement, setup_button_group, setup_helper, setup_pagination;

  root = this;

  Javie = root.Javie;

  jQuery = root.jQuery;

  setup_button_group = function($) {
    var buttons, form, group, hidden, name;
    group = $(this);
    form = group.parents('form').eq(0);
    name = group.attr('data-toggle-name');
    hidden = $("input[name='" + name + "']", form);
    buttons = $('button', group);
    buttons.each(function(i, item) {
      var button, set_active;
      button = $(item);
      set_active = function() {
        if (button.val() === hidden.val()) {
          button.addClass('active');
        }
        return true;
      };
      button.on('click', function() {
        buttons.removeClass('active');
        hidden.val($(this).val());
        return set_active();
      });
      return set_active();
    });
    return true;
  };

  setup_helper = function($) {
    $('input[type="date"]').datepicker({
      dateFormat: "yy-mm-dd"
    });
    $('select.form-control[role!="agreement"], .navbar-form > select[role!="agreement"]').select2().removeClass('form-control');
    $('*[role="tooltip"]').tooltip();
    return true;
  };

  setup_pagination = function($) {
    $('div.pagination > ul').each(function(i, item) {
      $(item).addClass('pagination').parent().removeClass('pagination');
      return true;
    });
    return true;
  };

  setup_agreement = function($) {
    var switchers;
    switchers = $('select[role="agreement"]');
    switchers.removeClass('form-control');
    switchers.each(function(i, item) {
      var switcher;
      switcher = $(item);
      switcher.toggleSwitch({
        highlight: switcher.data('highlight'),
        width: 25,
        change: function(e, target) {
          Javie.trigger('switcher.change', [switcher, e]);
          return true;
        }
      });
      switcher.css('display', 'none');
      return true;
    });
    return true;
  };

  jQuery(function($) {
    setup_agreement($);
    setup_button_group($);
    setup_helper($);
    setup_pagination($);
    return true;
  });

}).call(this);

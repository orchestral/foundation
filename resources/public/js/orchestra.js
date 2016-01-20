(function() {
  var Javie, jQuery, root, setup_agreement, setup_button_group, setup_helper, setup_pagination;

  root = this;

  Javie = root.Javie;

  jQuery = root.jQuery;

  setup_button_group = function($) {
    var buttons, form, group, hidden, name, set_active;
    group = $(this);
    form = group.parents('form').eq(0);
    name = group.attr('data-toggle-name');
    hidden = $("input[name='" + name + "']", form);
    buttons = $('button', group);
    set_active = function(button) {
      if (button.val() === hidden.val()) {
        button.addClass('active');
      }
    };
    buttons.each(function(i, item) {
      var button;
      button = $(item);
      button.on('click', function() {
        var self;
        self = $(this);
        buttons.removeClass('active');
        hidden.val(self.val());
        set_active(self);
      });
      set_active(button);
    });
  };

  setup_helper = function($) {
    $('input[type="date"]').datepicker({
      dateFormat: "yy-mm-dd"
    });
    $('select.form-control, .navbar-form > select').each(i, item)(function() {
      var selector;
      selector = $(item);
      if (selector.is('[role!="agreement"]' || selector.is('[role!="native"]'))) {
        return selector.select2().removeClass('form-control');
      }
    });
    $('*[role="tooltip"]').tooltip();
  };

  setup_pagination = function($) {
    $('div.pagination > ul').each(function(i, item) {
      $(item).addClass('pagination').parent().removeClass('pagination');
    });
  };

  setup_agreement = function($) {
    var switchers;
    switchers = $('select[role="agreement"]');
    switchers.removeClass('form-control').each(function(i, item) {
      var switcher;
      switcher = $(item);
      switcher.toggleSwitch({
        highlight: switcher.data('highlight'),
        width: 25,
        change: function(e, target) {
          Javie.trigger('switcher.change', [switcher, e]);
        }
      });
      switcher.css('display', 'none');
    });
  };

  jQuery(function($) {
    setup_agreement($);
    setup_button_group($);
    setup_helper($);
    setup_pagination($);
  });

}).call(this);

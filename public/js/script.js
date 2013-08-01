jQuery(function startOrchestra ($) { 'use strict';
	var events = new Javie.Events;

	$('div.pagination > ul').each(function onEachPaginator (i, item) {
		$(item).addClass('pagination').parent().removeClass('pagination');
	});

	$('select[role="switcher"]').removeClass('form-control').each(function onEachSwitcher (i, item) {
		var el = $(item);

		el.toggleSwitch({
			highlight: $(item).data("highlight"),
			width: 25,
			change : function () {
				events.fire('switcher.change', this);
			}
		})

		el.css('display', 'none');
	})

	$('select[role!="switcher"]').removeClass('form-control').select2();

	$('*[role="tooltip"]').tooltip();

	$('input[type="date"]').datepicker({ dateFormat: "yy-mm-dd" });
		
	$('div.btn-group[data-toggle-name]').each(function loopEachBtnGroup () {
		var group, form, name, hidden, buttons;

		group   = $(this);
		form    = group.parents('form').eq(0);
		name    = group.attr('data-toggle-name');
		hidden  = $('input[name="' + name + '"]', form);
		buttons = $('button', group);

		buttons.each(function setStateOnEachButton () {
			var button, setActive;

			button = $(this);

			setActive = function setActive() {
				if(button.val() == hidden.val()) {
					button.addClass('active');
				}
			};

			button.on('click', function() {
				buttons.removeClass('active');

				hidden.val($(this).val());

				setActive();
			});

			setActive();
		});
	});
});

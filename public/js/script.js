jQuery(function startOrchestra($) {
	var dispatcher = Javie.make('event');

	$('div.pagination > ul').each(function onEachPaginator (i, item) {
		$(item).addClass('pagination').parent().removeClass('pagination');
	});

	$('select[role="switcher"]').removeClass('form-control').each(function onEachSwitcher (i, item) {
		var element = $(item);

		element.toggleSwitch({
			highlight: element.data("highlight"),
			width: 25,
			change : function (e, target) {
				dispatcher.fire('switcher.change', [element, e]);
			}
		})

		element.css('display', 'none');
	})

	$('select[role!="switcher"]').select2().removeClass('form-control');

	$('*[role="tooltip"]').tooltip();

	$('input[type="date"]').datepicker({ dateFormat: "yy-mm-dd" });

	$('div.btn-group[data-toggle-name]').each(function loopEachBtnGroup() {
		var group, form, name, hidden, buttons;

		group   = $(this);
		form    = group.parents('form').eq(0);
		name    = group.attr('data-toggle-name');
		hidden  = $('input[name="' + name + '"]', form);
		buttons = $('button', group);

		buttons.each(function setStateOnEachButton() {
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

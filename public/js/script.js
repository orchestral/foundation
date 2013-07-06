jQuery(function startOrchestra ($) { 'use strict';
	var events = new Javie.Events;

	// Listen to email.driver changed event. 
	events.listen('setting.changed: email.driver', function listenToEmailDriverChange(e, self) {
		var value, smtp;

		value = self.value ? self.value : '';
		smtp  = ['email_host', 'email_port', 'email_address', 'email_username', 'email_password', 'email_encryption'];

		$('input[name^="email_"]').parent().parent().hide();

		$('input[name="email_queue"]').parent().parent().hide();

		switch (value) {
			case 'smtp' :
				$.each(smtp, function (index, name) {
					$('input[name="'+name+'"]').parent().parent().show();
				});

				break;
			case 'sendmail' :
				$('input[name^="email_address"]').parent().parent().show();
				$('input[name^="email_sendmail"]').parent().parent().show();
				break;
			default :
				$('input[name^="email_address"]').parent().parent().show();
				break;
		}
	});

	$('div.pagination > ul').each(function(i, item) {
		$(item).addClass('pagination').parent().removeClass('pagination');
	});

	$('select[role="switcher"]').each(function(i, item) {
		var el = $(item);

		el.toggleSwitch({
			highlight: $(item).data("highlight"),
			width: 25,
			change : function () {
				ev.fire('switcher.change', this);
			}
		})

		el.css('display', 'none');
	})

	$('select[role!="switcher"]').select2();

	$('*[role="tooltip"]').tooltip();

	$('div.btn-group[data-toggle-name]').each(function() {
		var group, form, name, hidden, buttons;

		group   = $(this);
		form    = group.parents('form').eq(0);
		name    = group.attr('data-toggle-name');
		hidden  = $('input[name="' + name + '"]', form);
		buttons = $('button', group);

		buttons.each(function(){
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

<?php

use Orchestra\Support\Facades\Form; ?>

<div id="cancel_password_container">
	<a href="#" id="cancel_password_button" class="btn btn-mini btn-info">
		{{ trans('orchestra/foundation::label.cancel') }}
	</a>
</div>
<div id="change_password_container">
	<span>{{ str_repeat('*', strlen($model->email_password)) }}</span>
	&nbsp;&nbsp;
	<a href="#" id="change_password_button" class="btn btn-mini btn-warning">
		{{ trans('orchestra/foundation::label.email.change_password') }}
	</a>
	{{ Form::hidden('change_password', 'no') }}
</div>

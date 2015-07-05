<?php

$message = app('orchestra.messages')->retrieve();

if ($message instanceof Orchestra\Messages\MessageBag) :
    $message->setFormat(<<<MESSAGE
<div class="alert alert-:key">
    :message <button class="close" data-dismiss="alert">×</button>
</div>
MESSAGE);

	foreach (['error', 'info', 'success'] as $key) :
		if ($message->has($key)) :
			echo implode('', $message->get($key));
		endif;
	endforeach;
endif;

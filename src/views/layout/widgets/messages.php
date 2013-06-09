<?php 

$message = Orchestra\Support\Facades\Messages::retrieve();

if ($message instanceof Orchestra\Support\Messages) :
	foreach (array('error', 'info', 'success') as $key) :
		if ($message->has($key)) :
			$message->setFormat(
				'<div class="alert alert-'.$key.'">:message<button class="close" data-dismiss="alert">×</button></div>'
			);

			echo implode('', $message->get($key));
		endif;
	endforeach;
endif;

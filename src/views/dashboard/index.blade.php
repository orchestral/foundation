@extends('orchestra/foundation::layout.main')

<?php

use Illuminate\Support\Facades\HTML;
use Illuminate\Support\Facades\View; ?>

@section('content')

<div class="row">
	<?php if (count($panes) > 0) :

	$panes->add('mini-profile', '<')->title('Mini Profile')
		->attributes(array('class' => 'three columns widget'))
		->content(View::make('orchestra/foundation::layout.widgets.miniprofile'));

	foreach ($panes as $id => $pane) : ?>
		<div<?php echo HTML::attributes(HTML::decorate($pane->attributes, array('class' => 'panel'))); ?>>
		<?php if ( ! empty($pane->html)) :
			echo $pane->html;
		else : ?>
			<div class="panel-heading"><?php echo $pane->title; ?></div>
			<?php echo $pane->content; ?>
		<?php endif; ?>
		</div>
	<?php endforeach;
	else : ?>
	@include('orchestra/foundation::dashboard._welcome')
	<?php endif; ?>
</div>

@stop

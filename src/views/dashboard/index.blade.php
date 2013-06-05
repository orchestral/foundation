@extends('orchestra/foundation::layout.main')

@section('content')

<div class="row">
	<?php if (count($panes) > 0) :
	foreach ($panes as $id => $pane) : ?>
		<div<?php echo HTML::attributes($pane->attributes); ?>>
		<?php if ( ! empty($pane->html)) :
			echo $pane->html; 
		else : ?>
			<table<?php echo HTML::attributes(array('class' => "table table-bordered")); ?>>
				<thead>
					<tr>
						<th><?php echo $pane->title; ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php echo $pane->content; ?></td>
					</tr>
				</tbody>
			</table>
		<?php endif; ?>
		</div>
	<?php endforeach;
	else : ?>
	<div class="jumbotron">
		<h2>Welcome to your new Orchestra Platform site!</h2>
		<p>
			If you need help getting started, check out our documentation on First Steps with Orchestra Platform. 
			If you’d rather dive right in, here are a few things most people do first when they set up a new Orchestra Platform site. 
			<!-- If you need help, use the Help tabs in the upper right corner to get information on how to use your current 
			screen and where to go for more assistance.-->
		</p>
	</div>
	<?php endif; ?>
</div>

@stop

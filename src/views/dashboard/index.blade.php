@extends('orchestra/foundation::layout.main')

@section('content')

<div class="row">
	@if (count($panes) > 0)
	@foreach ($panes as $id => $pane) 
		<div{{ Html::attributes($pane->attributes) }}>
		@if ( ! empty($pane->html))
			{{ $pane->html }}
		@else
			<table{{ Html::attributes(array('class' => "table table-bordered")) }}>
				<thead>
					<tr>
						<th>{{ $pane->title }}</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>{{ $pane->content }}</td>
					</tr>
				</tbody>
			</table>
		@endif
		</div>
	@endforeach
	@else
	<div class="jumbotron">
		<h2>Welcome to your new Orchestra Platform site!</h2>
		<p>
			If you need help getting started, check out our documentation on First Steps with Orchestra Platform. 
			If you’d rather dive right in, here are a few things most people do first when they set up a new Orchestra Platform site. 
			<!-- If you need help, use the Help tabs in the upper right corner to get information on how to use your current 
			screen and where to go for more assistance.-->
		</p>
	</div>
	@endif
</div>

@stop

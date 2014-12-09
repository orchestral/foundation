<?php $attributes['table'] = app('html')->decorate($attributes['table'], [
	'class' => 'table table-striped'
]); ?>

<table{!! app('html')->attributes($attributes['table']) !!}>
	<thead>
		<tr>
			@foreach ($columns as $col)
			<th{!! app('html')->attributes($col->headers ?: []) !!}>
				{!! $col->label !!}
			</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		@foreach ($rows as $row)
		<tr{!! app('html')->attributes(call_user_func($attributes['row'], $row) ?: []) !!}>
			@foreach ($columns as $col)
			<td{!! app('html')->attributes(call_user_func($col->attributes, $row)) !!}>
				{!! $col->getValue($row) !!}
			</td>
			@endforeach
		</tr>
		@endforeach
		@if (! count($rows) && $empty)
		<tr class="norecords">
			<td colspan="{!! count($columns) !!}">{!! $empty !!}</td>
		</tr>
		@endif
	</tbody>
</table>

{!! $pagination or '' !!}

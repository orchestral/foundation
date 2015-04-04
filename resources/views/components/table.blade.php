@inject('htmlbuilder', 'html')
#{{ $attributes['table'] = $htmlbuilder->decorate($attributes['table'], ['class' => 'table table-striped']) }}

<table{!! $htmlbuilder->attributes($attributes['table']) !!}>
	<thead>
		<tr>
			@foreach ($columns as $col)
			<th{!! $htmlbuilder->attributes($col->headers ?: []) !!}>
				{!! $col->label !!}
			</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		@foreach ($rows as $row)
		<tr{!! $htmlbuilder->attributes(call_user_func($attributes['row'], $row) ?: []) !!}>
			@foreach ($columns as $col)
			<td{!! $htmlbuilder->attributes(call_user_func($col->attributes, $row)) !!}>
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

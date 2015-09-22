<table{!! HTML::attributable($grid->attributes(), ['class' => 'table table-striped']) !!}>
	<thead>
		<tr>
			@foreach($grid->columns() as $column)
			<th{!! HTML::attributes($column->headers ?: []) !!}>
				{!! $column->label !!}
			</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		@foreach($grid->data() as $row)
		<tr{!! HTML::attributes(call_user_func($grid->header(), $row) ?: []) !!}>
			@foreach($grid->columns() as $column)
			<td{!! HTML::attributes(call_user_func($column->attributes, $row)) !!}>
				{!! $col->getValue($row) !!}
			</td>
			@endforeach
		</tr>
		@endforeach
		@if(! count($grid->data()) && $empty)
		<tr class="norecords">
			<td colspan="{!! count($grid->columns()) !!}">{!! $empty !!}</td>
		</tr>
		@endif
	</tbody>
</table>

{!! $pagination or '' !!}

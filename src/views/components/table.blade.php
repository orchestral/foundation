<? $attributes['table'] = HTML::decorate($attributes['table'], array('class' => 'table table-striped')); ?>

<table{{ HTML::attributes($attributes['table']) }}>
	<thead>
		<tr>
			@foreach ($columns as $col)
			<th{{ HTML::attributes($col->headers ?: array()) }}>
				{{ $col->label }}
			</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		@foreach ($rows as $row)
		<tr{{ HTML::attributes(call_user_func($attributes['row'], $row) ?: array()) }}>
			@foreach ($columns as $col)
			<td{{ HTML::attributes(call_user_func($col->attributes, $row)) }}>
				{{ $col->getValue($row) }}
			</td>
			@endforeach
		</tr>
		@endforeach
		@if (! count($rows) and $empty)
		<tr class="norecords">
			<td colspan="{{ count($columns) }}">{{ $empty }}</td>
		</tr>
		@endif
	</tbody>
</table>

{{ $pagination or '' }}

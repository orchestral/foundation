<table{!! HTML::attributable($grid->attributes(), ['class' => 'table table-hover']) !!}>
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
        {!! $column->getValue($row) !!}
      </td>
      @endforeach
    </tr>
    @endforeach
    @if(! count($grid->data()) && $empty)
    <tr class="no-records">
      <td colspan="{!! count($grid->columns()) !!}">{!! $empty !!}</td>
    </tr>
    @endif
  </tbody>
</table>

<div class="row">
  <div class="col-sm-5 col-xs-12 sm-text">
  @if($pagination->total() > 0)
    @if($pagination->firstItem() !== $pagination->lastItem())
    {{ trans('orchestra/foundation::label.paginations.multiple', Arr::only($pagination->toArray(), ['from', 'to', 'total'])) }}
    @else
    {{ trans('orchestra/foundation::label.paginations.single', Arr::only($pagination->toArray(), ['from', 'total'])) }}
    @endif
  @endif
  </div>
  @if($pagination->hasPages())
  <div class="col-sm-7 col-xs-12">
    <div class="pull-right">{{ $pagination }}</div>
  </div>
  @endif
</div>


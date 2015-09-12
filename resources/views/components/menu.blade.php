#{{ $active = null }}
<ul class="nav navbar-nav" role="menu">
	@foreach($menu as $item)
		#{{ $parent = $item->get('id') }}
		@if(1 > count($item->get('childs')))
			<li data-menu="{{ $parent }}">
				#{{ $active = $item->active() ? $parent : $active }}
				<a href="{{ $item->get('link') }}">
					{!! $item->get('title') !!}
				</a>
			</li>
		@else
			<li data-menu="{{ $parent }}" class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">{!! $item->get('title') !!}</a>
				<ul class="dropdown-menu">
					@if($item->hasLink())
					<li>
						#{{ $active = $item->active() ? $parent : $active }}
						<a href="{{ $item->get('link') }}">
							{!! $item->get('title') !!}
						</a>
					</li>
					<li class="divider"></li>
					@endif
					@foreach($item->get('childs') as $child)
						#{{ $grands = $child->get('childs') }}
						#{{ $active = $child->active() ? $parent : $active }}
						<li{!! HTML::attributes(HTML::decorate(
								['class' => $child->active() ? 'active' : ''],
								['class' => ! empty($grands) ? 'dropdown-submenu' : 'normal']
							)) !!}>
							<a href="{{ $child->get('link') }}">
								{!! $child->get('title') !!}
							</a>
							@if(! empty($child->get('childs')))
							<ul class="dropdown-menu">
								@foreach($child->get('childs') as $grand)
								#{{ $active = $grand->active() ? $parent : $active }}
								<li{{ HTML::attributes(['class' => $grand->active() ? 'active' : '']) }}>
									<a href="{{ $grand->get('link') }}">
										{!! $grand->get('title') !!}
									</a>
								</li>
								@endforeach
							</ul>
							@endif
						</li>
					@endforeach
				</ul>
			</li>
		@endif
	@endforeach
</ul>

@push('orchestra.footer')
@unless(is_null($active))
<script>
jQuery(function ($) {
  var active = "{{ $active }}";
  $('li[data-menu="'+active+'"]').addClass('active');
});
</script>
@endunless
@endpush

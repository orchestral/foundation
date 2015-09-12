<ul class="nav navbar-nav" role="menu">
	@foreach ($menu as $item)
		@if (1 > count($item->get('childs')))
			<li>
				<a href="{!! $item->get('link') !!}">
					{!! $item->get('title') !!}
				</a>
			</li>
		@else
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">{!! $item->get('title') !!}</a>
				<ul class="dropdown-menu">
					@unless ($item->get('link') == '#' && ! empty($item->get('link')))
					<li>
						<a href="{!! $item->get('link') !!}">
							{!! $item->get('title') !!}
						</a>
					</li>
					<li class="divider"></li>
					@endunless
					@foreach ($item->get('childs') as $child)
						#{{ $grands = $child->get('childs') }}
						<li class="{!! (! empty($grands) ? "dropdown-submenu" : "normal") !!}">
							<a href="{!! $child->get('link') !!}">
								{!! $child->get('title') !!}
							</a>
							@if (! empty($child->get('childs')))
							<ul class="dropdown-menu">
								@foreach ($child->get('childs') as $grand)
								<li>
									<a href="{!! $grand->get('link') !!}">
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

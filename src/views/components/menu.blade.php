<ul class="nav navbar-nav" role="menu">
	@foreach ($menu as $item)
		@if (1 > count($item->childs))
			<li>
				<a href="{{ $item->link }}">
					{{ $item->title }}
				</a>
			</li>
		@else
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ $item->title }}</a>
				<ul class="dropdown-menu">
					<li>
						<a href="{{ $item->link }}">
							{{ $item->title }}
						</a>
					</li>
					<li class="divider"></li>
					@foreach ($item->childs as $child)
						<?php $grands = $child->childs; ?>
						<li class="{{ ( ! empty($grands) ? "dropdown-submenu" : "normal" ) }}">
							<a href="{{ $child->link }}">
								{{ $child->title }}
							</a>
							@if (! empty($child->childs))
							<ul class="dropdown-menu">
								@foreach ($child->childs as $grand)
								<li>
									<a href="{{ $grand->link }}">
										{{ $grand->title }}
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

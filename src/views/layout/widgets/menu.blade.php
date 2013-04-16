<ul class="nav" role="menu">
	
	@foreach ($menu->getItem() as $item) 

		@if (1 > count($item->childs)) 
			<li>{{ HTML::link($item->link, $item->title) }}</li>
		@else
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					{{ $item->title }}
				</a>
				<ul class="dropdown-menu">
					<li>{{ HTML::link($item->link, $item->title) }}</li>
					<li class="divider"></li>

					@foreach ($item->childs as $child) 
						<?php $grands = $child->childs; ?>

						<li class="{{ ( ! empty($grands) ? "dropdown-submenu" : "normal" ) }}">
							{{ HTML::link($child->link, $child->title) }}
							
							@if ( ! empty($child->childs))
							<ul class="dropdown-menu">
								@foreach ($child->childs as $grand) 
								<li>
									{{ HTML::link($grand->link, $grand->title) }}
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
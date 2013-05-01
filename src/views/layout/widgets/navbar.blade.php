{{-- Define the navbar attributes --}}
<?php $attributes = Html::decorate($navbar->attributes ?: array(), array('class' => 'navbar')); ?>

<div{{ Html::attributes($attributes) }}>
	<div class="container">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".{{ $navbar->id }}-responsive-collapse">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		{{ HTML::link($navbar->url, $navbar->title, array('class' => 'navbar-brand')) }}
		<div class="nav-collapse collapse {{ $navbar->id }}-responsive-collapse">	
		{{ $navbar->menu }}
		{{ $navbar->subMenu }}
		</div>
	</div>
</div>

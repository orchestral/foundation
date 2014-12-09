<?php

$attributes = app('html')->decorate($navbar->attributes ?: [], [
	'class' => 'navbar',
	'role' => 'navigation'
]); ?>

<nav{!! app('html')->attributes($attributes) !!}>
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".{!! $navbar->id !!}-responsive-collapse">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a href="{!! $navbar->url !!}" class="navbar-brand">
			{!! $navbar->title !!}
		</a>
	</div>
	<div class="collapse navbar-collapse {!! $navbar->id !!}-responsive-collapse">
		{!! $navbar->left !!}
		{!! $navbar->right !!}
		{!! $navbar->menu !!}
	</div>
</nav>

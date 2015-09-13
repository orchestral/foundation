<nav{!! HTML::attributable($navbar->get('attributes') ?: [], ['class' => 'navbar', 'role' => 'navigation']) !!}>
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".{!! $navbar->id !!}-responsive-collapse">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a href="{!! $navbar->url !!}" class="navbar-brand">
			{!! $navbar->get('title') !!}
		</a>
	</div>
	<div class="collapse navbar-collapse {!! $navbar->id !!}-responsive-collapse">
		{!! $navbar->get('left') !!}
		{!! $navbar->get('right') !!}
		{!! $navbar->get('menu') !!}
	</div>
</nav>

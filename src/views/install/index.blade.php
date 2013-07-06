@extends('orchestra/foundation::layout.main')


<?php

$databaseConnection = $checklist['databaseConnection'];
unset($checklist['databaseConnection']); ?>

@section('content')

<div class="row">
	<div class="col col-lg-3">
		<div class="list-group">
			<a href="<?php echo handles('orchestra::install'); ?>" class="list-group-item active">
				<?php echo trans('orchestra/foundation::install.steps.requirement'); ?>
			</a>
			<a href="#" class="list-group-item disabled">
				<?php echo trans('orchestra/foundation::install.steps.account'); ?>
			</a>
			<a href="#" class="list-group-item disabled">
				<?php echo trans('orchestra/foundation::install.steps.done'); ?>
			</a>
		</div>

		<div class="progress">
			<div class="progress-bar progress-bar-success" style="width: 0%"></div>
		</div>
	</div>

	<div id="installation" class="col col-lg-6 form-horizontal">
	
		@include('orchestra/foundation::install.index.requirement')
		@include('orchestra/foundation::install.index.database')
		@include('orchestra/foundation::install.index.authentication')

	</div>

</div>

@stop

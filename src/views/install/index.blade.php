@extends('orchestra/foundation::layout.main')

@section('content')

<div class="row">
	<div class="col col-lg-3">
		<div class="list-group">
			<a href="<?php echo handles('orchestra/foundation::install'); ?>" class="list-group-item active">
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

		<h3><?php echo trans('orchestra/foundation::install.system.title'); ?></h3>

		<p><?php echo trans('orchestra/foundation::install.system.description'); ?></p>

		<table class="table table-bordered table-striped requirements">
			<thead>
				<tr>
					<th><?php echo trans('orchestra/foundation::install.system.requirement'); ?></th>
					<th><?php echo trans('orchestra/foundation::install.system.status'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$databaseConnection = $checklist['databaseConnection'];
				unset($checklist['databaseConnection']);

				foreach ($checklist as $name => $requirement) : ?>
				<tr>
					<td>
						<?php echo trans("orchestra/foundation::install.system.{$name}.name", $requirement['data']); ?>
						<?php if ( ! ($requirement['is'] === $requirement['should'])) : ?>
						<div class="alert<?php echo true === $requirement['explicit'] ? ' alert-error ' : ''; ?>">
							<strong><?php echo trans("orchestra/foundation::install.solution"); ?>:</strong>
							<?php echo trans("orchestra/foundation::install.system.{$name}.solution", $requirement['data']); ?>
						</div>
						<?php endif; ?>
					</td>
					<td>
						<?php if ($requirement['is'] === $requirement['should']) : ?>
							<button class="btn btn-success btn-block disabled">
								<?php echo trans('orchestra/foundation::install.status.work'); ?>
							</button>
						<?php else : 
							if (true === $requirement['explicit']) : ?>
								<button class="btn btn-danger btn-block disabled">
									<?php echo trans('orchestra/foundation::install.status.not'); ?>
								</button>
							<?php else : ?>
								<button class="btn btn-warning btn-block disabled">
									<?php echo trans('orchestra/foundation::install.status.still'); ?>
								</button>
							<?php endif;
						endif; ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<h3><?php echo trans('orchestra/foundation::install.database.title'); ?></h3>

		<p>
			<?php echo trans('orchestra/foundation::install.verify', array(
				'filename' => '<code title="'.app_path().'config/database.php'.'">app/config/database.php</code>'
			)); ?>
		</p>

		<fieldset>

			<div class="row">
				<label class="col-lg-3 control-label"><?php echo trans('orchestra/foundation::install.database.type'); ?></label>
				<div class="col-lg-9">
					<input disabled type="text" value="<?php echo $database['driver']; ?>">
				</div>
			</div>

			<?php if (isset($database['host'])) : ?>
			<div class="row">
				<label class="col-lg-3 control-label"><?php echo trans('orchestra/foundation::install.database.host'); ?></label>
				<div class="col-lg-9">
					<input disabled type="text" value="<?php echo $database['host']; ?>">
				</div>
			</div>
			<?php endif; ?>

			<div class="row">
				<label class="col-lg-3 control-label"><?php echo trans('orchestra/foundation::install.database.name'); ?></label>
				<div class="col-lg-9">
					<input disabled type="text" value="<?php echo $database['database']; ?>">
				</div>
			</div>

			<?php if (isset($database['username'])) : ?>
			<div class="row">
				<label class="col-lg-3 control-label"><?php echo trans('orchestra/foundation::install.database.username'); ?></label>
				<div class="col-lg-9">
					<input disabled type="text" value="<?php echo $database['username']; ?>">
				</div>
			</div>
			<?php endif;

			if (isset($database['password'])) : ?>
			<div class="row">
				<label class="col-lg-3 control-label"><?php echo trans('orchestra/foundation::install.database.password'); ?></label>
				<div class="col-lg-9">
					<input disabled type="text" value="<?php echo $database['password']; ?>">
					<p class="help-block"><?php echo trans('orchestra/foundation::install.hide-password'); ?></p>
				</div>
			</div>
			<?php endif; ?>

			<div class="row">
				<label class="col-lg-3 control-label"><?php echo trans('orchestra/foundation::install.connection.status'); ?></label>
				<div class="col-lg-9">
					<?php if (true === $databaseConnection['is']) : ?>
					<button class="btn btn-success disabled input-xlarge">
						<?php echo trans('orchestra/foundation::install.connection.success'); ?>
					</button>
					<?php else : ?>
					<button class="btn btn-danger disabled input-xlarge">
						<?php echo trans('orchestra/foundation::install.connection.fail'); ?>
					</button>
					<?php endif; ?>
				</div>
			</div>

		</fieldset>

		<fieldset>

			<h3><?php echo trans('orchestra/foundation::install.auth.title'); ?></h3>

			<p>
				<?php echo trans('orchestra/foundation::install.verify', array(
					'filename' => HTML::create('code', 'app/config/auth.php', array('title' => app_path().'config/auth.php'))
				)); ?>
			</p>

			<div class="row">
				<label class="col-lg-3 control-label <?php echo 'fluent' === $auth['driver'] ? 'error' : ''; ?>">
					<?php echo trans('orchestra/foundation::install.auth.driver'); ?>
				</label>
				<div class="col-lg-9">
					<input disabled type="text" value="<?php echo $auth['driver']; ?>">
					<?php if ('fluent' === $auth['driver']) : ?>
					<p class="help-block"><?php echo trans('orchestra/foundation::install.auth.requirement.driver'); ?></p>
					<?php endif; ?>
				</div>
			</div>

			<div class="row
				<?php echo false === $authentication ? ' error' : ''; echo 'eloquent' !== $auth['driver'] ? ' hide' : ''; ?>">
				<label class="col-lg-3 control-label">
					<?php echo trans('orchestra/foundation::install.auth.model'); ?>
				</label>
				<div class="col-lg-9">
					<input disabled type="text" value="<?php echo $auth['model']; ?>">
					<?php if (false === $authentication) : ?>
					<p class="help-block">
						<?php echo trans('orchestra/foundation::install.auth.requirement.driver', array(
							'class' => HTML::create('code', 'Orchestra\Model\User')
						)); ?>
					</p>
					<?php endif; ?>
				</div>
			</div>

			<?php if ($installable) : ?>
			<hr>
			<div class="row">
				<div class="col-lg-9 col-offset-3">
					<a href="<?php echo handles('orchestra/foundation::install/create'); ?>" class="btn btn-primary">
						<?php echo trans('orchestra/foundation::label.next'); ?>
					</a>
				</div>
			</div>

			<?php endif; ?>

		</fieldset>

	</div>

</div>

@stop

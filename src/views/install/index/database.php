<div class="row">
	<div class="twelve columns rounded box">
		<h3><?php echo trans('orchestra/foundation::install.database.title'); ?></h3>

		<p>
			<?php echo trans('orchestra/foundation::install.verify', array(
				'filename' => '<code title="'.app_path().'config/database.php'.'">app/config/database.php</code>'
			)); ?>
		</p>

		<div class="row">
			<label class="three columns control-label"><?php echo trans('orchestra/foundation::install.database.type'); ?></label>
			<div class="nine columns">
				<input disabled type="text" value="<?php echo $database['driver']; ?>">
			</div>
		</div>

		<?php if (isset($database['host'])) : ?>
		<div class="row">
			<label class="three columns control-label"><?php echo trans('orchestra/foundation::install.database.host'); ?></label>
			<div class="nine columns">
				<input disabled type="text" value="<?php echo $database['host']; ?>">
			</div>
		</div>
		<?php endif; ?>

		<div class="row">
			<label class="three columns control-label"><?php echo trans('orchestra/foundation::install.database.name'); ?></label>
			<div class="nine columns">
				<input disabled type="text" value="<?php echo $database['database']; ?>">
			</div>
		</div>

		<?php if (isset($database['username'])) : ?>
		<div class="row">
			<label class="three columns control-label"><?php echo trans('orchestra/foundation::install.database.username'); ?></label>
			<div class="nine columns">
				<input disabled type="text" value="<?php echo $database['username']; ?>">
			</div>
		</div>
		<?php endif;

		if (isset($database['password'])) : ?>
		<div class="row">
			<label class="three columns control-label"><?php echo trans('orchestra/foundation::install.database.password'); ?></label>
			<div class="nine columns">
				<input disabled type="text" value="<?php echo $database['password']; ?>">
				<p class="help-block"><?php echo trans('orchestra/foundation::install.hide-password'); ?></p>
			</div>
		</div>
		<?php endif; ?>

		<div class="row">
			<label class="three columns control-label"><?php echo trans('orchestra/foundation::install.connection.status'); ?></label>
			
			<div class="nine columns">
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
	</div>
</div>

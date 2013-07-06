<?php use Illuminate\Support\Facades\HTML; ?>

<div class="row">
	<div class="twelve columns rounded box">
		<h3><?php echo trans('orchestra/foundation::install.auth.title'); ?></h3>

		<p>
			<?php echo trans('orchestra/foundation::install.verify', array(
				'filename' => HTML::create('code', 'app/config/auth.php', array('title' => app_path().'config/auth.php'))
			)); ?>
		</p>

		<div class="row">
			<label class="three columns control-label <?php echo 'fluent' === $auth['driver'] ? 'error' : ''; ?>">
				<?php echo trans('orchestra/foundation::install.auth.driver'); ?>
			</label>
			<div class="nine columns">
				<input disabled type="text" value="<?php echo $auth['driver']; ?>">
				<?php if ('fluent' === $auth['driver']) : ?>
				<p class="help-block"><?php echo trans('orchestra/foundation::install.auth.requirement.driver'); ?></p>
				<?php endif; ?>
			</div>
		</div>

		<div class="row
			<?php echo false === $authentication ? ' error' : ''; echo 'eloquent' !== $auth['driver'] ? ' hide' : ''; ?>">
			<label class="three columns control-label">
				<?php echo trans('orchestra/foundation::install.auth.model'); ?>
			</label>
			<div class="nine columns">
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
			<div class="nine columns offset-by-three">
				<a href="<?php echo handles('orchestra/foundation::install/create'); ?>" class="btn btn-primary">
					<?php echo trans('orchestra/foundation::label.next'); ?>
				</a>
			</div>
		</div>

		<?php endif; ?>
	</div>
</div>

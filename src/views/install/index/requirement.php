<div class="row">
	<div class="twelve columns rounded box">
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
				<?php foreach ($checklist as $name => $requirement) : ?>
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
	</div>
</div>

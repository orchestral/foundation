<?php

$attributes['table'] = HTML::decorate($attributes['table'], array('class' => 'table table-striped')); ?>
<table<?php echo HTML::attributes($attributes['table']); ?>>
	<thead>
		<tr>
<?php foreach ($columns as $col): ?>
			<th<?php echo HTML::attributes($col->headers ?: array()); ?>><?php echo $col->label; ?></th>
<?php endforeach; ?>
		</tr>
	</thead>
	<tbody>
<?php foreach ($rows as $row): ?>
		<tr<?php echo HTML::attributes(call_user_func($attributes['row'], $row) ?: array()); ?>>
<?php foreach ($columns as $col): ?>
			<td<?php echo HTML::attributes(call_user_func($col->attributes, $row)); ?>>
				<?php echo $col->getValue($row); ?>
			</td>
<?php endforeach; ?>
		</tr>
<?php endforeach; if ( ! count($rows) and $empty): ?>
		<tr class="norecords">
			<td colspan="<?php echo count($columns); ?>"><?php echo $empty; ?></td>
		</tr>
<?php endif; ?>
	</tbody>
</table>
<?php echo $pagination ?: ''; ?>

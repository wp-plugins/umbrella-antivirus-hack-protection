<?php Umbrella\Controller::header(); ?>

<h4><?php _e('Writable Files', 'umbrella'); ?></h4>
<p>
</p>
<table class="wp-list-table widefat plugins">
	<thead>
		<tr>
			<th><?php _e('File', 'umbrella'); ?></th>
			<th><?php _e('Chmod', 'umbrella'); ?></th>
			<th><?php _e('Recommended', 'umbrella'); ?></th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<th><?php _e('File', 'umbrella'); ?></th>
			<th><?php _e('Chmod', 'umbrella'); ?></th>
			<th><?php _e('Recommended', 'umbrella'); ?></th>
		</tr>
	</tfoot>

	<tbody id="the-list">

	<?php foreach($warning_list as $list): ?>
		<tr class="alternate">
			<td><?php echo $list['file']; ?></td>
			<td style="color:red"><?php echo $list['chmod']; ?></td>
			<td>644</td>
		</tr>	
	<?php endforeach; ?>			
	</tbody>
</table>
<?php Umbrella\Controller::footer(); ?>
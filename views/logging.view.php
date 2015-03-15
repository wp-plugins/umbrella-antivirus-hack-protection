<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
Umbrella\Controller::header(); ?>

<h4><?php _e('Logs', UMBRELLA__TEXTDOMAIN); ?></h4>
<p>
</p>
<table class="wp-list-table widefat plugins">
	<thead>
		<tr>
			<th><?php _e('Time', UMBRELLA__TEXTDOMAIN); ?></th>
			<th><?php _e('Module', UMBRELLA__TEXTDOMAIN); ?></th>
			<th><?php _e('Message', UMBRELLA__TEXTDOMAIN); ?></th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<th><?php _e('Time', UMBRELLA__TEXTDOMAIN); ?></th>
			<th><?php _e('Module', UMBRELLA__TEXTDOMAIN); ?></th>
			<th><?php _e('Message', UMBRELLA__TEXTDOMAIN); ?></th>
		</tr>
	</tfoot>

	<tbody id="the-list">

	<?php foreach($logs as $log): ?>
		<tr class="alternate">
			<td><?php echo $log->time; ?></td>
			<td><?php echo $log->module; ?></td>
			<td><?php echo $log->message; ?></td>
		</tr>	
	<?php endforeach; ?>			
	</tbody>
</table>
<?php Umbrella\Controller::footer(); ?>
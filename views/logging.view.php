<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
Umbrella\Controller::header(); 
if (isset($refresh_page)): 
?>
<script type="text/javascript">
	location.href='admin.php?page=umbrella-logging';
</script>
<?php endif; ?>


<span id="disable-admin-notices"  style="margin-left: 5px; float: right;">
	<?php if(get_option('umbrella_sp_disable_notices') AND get_option('umbrella_sp_disable_notices') == 1): ?>
		<a href="?page=umbrella-logging&amp;do=enable-admin-notices" class="button button-primary"><?php _e('Enable admin notices', UMBRELLA__TEXTDOMAIN); ?></a>
	<?php else: ?>
		<a href="?page=umbrella-logging&amp;do=disable-admin-notices" class="button"><?php _e('Disable admin notices', UMBRELLA__TEXTDOMAIN); ?></a>
	<?php endif; ?>
</span>

<a href="?page=umbrella-logging&amp;do=empty-logs" id="empty-logs" class="button" style="float: right;"><?php _e('Empty logs', UMBRELLA__TEXTDOMAIN); ?></a>

<h3><?php _e('Logs', UMBRELLA__TEXTDOMAIN); ?></h3>
<?php if (!count($logs) > 0): ?>
<p><?php _e('You have no log entries yet.', UMBRELLA__TEXTDOMAIN); ?></p>
<?php else: ?>
<table class="wp-list-table widefat plugins">
	<thead>
		<tr>
			<th><?php _e('Time', UMBRELLA__TEXTDOMAIN); ?></th>
			<th><?php _e('Module', UMBRELLA__TEXTDOMAIN); ?></th>
			<th><?php _e('Ipaddress', UMBRELLA__TEXTDOMAIN); ?></th>
			<th><?php _e('Message', UMBRELLA__TEXTDOMAIN); ?></th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<th><?php _e('Time', UMBRELLA__TEXTDOMAIN); ?></th>
			<th><?php _e('Module', UMBRELLA__TEXTDOMAIN); ?></th>
			<th><?php _e('Ipaddress', UMBRELLA__TEXTDOMAIN); ?></th>
			<th><?php _e('Message', UMBRELLA__TEXTDOMAIN); ?></th>
		</tr>
	</tfoot>

	<tbody id="the-list">
	<?php foreach($logs as $log): ?>
		<tr class="alternate">
			<td><?php 
			if ($log->admin_notice == 1)
				echo "<strong>" . $log->time . "</strong>";
			else
				echo $log->time; 
			?></td>
			<td><?php echo $log->module; ?></td>
			<td><?php echo $log->visitor_ip; ?></td>
			<td><?php echo $log->message; ?></td>
		</tr>	
	<?php endforeach; ?>			
	</tbody>
</table>
<?php endif; ?>
<?php Umbrella\Controller::footer(); ?>
<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
Umbrella\Controller::header($data); ?>

<div style="float:left;width: 48%">
	<h3><?php _e('Database Information', UMBRELLA__TEXTDOMAIN); ?></h3>

	<table class="wp-list-table widefat plugins">
		<tbody id="the-list">
			<tr class="alternate">
				<th style="text-align:center;font-weight:bold;"><?php _e('User@Host', UMBRELLA__TEXTDOMAIN); ?></th>
				<th style="text-align:center;font-weight:bold;"><?php _e('Database Name', UMBRELLA__TEXTDOMAIN); ?></th>
				<th style="text-align:center;font-weight:bold;"><?php _e('Database Size', UMBRELLA__TEXTDOMAIN); ?></th>
			</tr>
			<tr class="alternate">
				<td style="text-align:center;">'<?php echo $mysql['user']; ?>'@'<?php echo $mysql['host']; ?>'</td>
				<td style="text-align:center;"><?php echo $mysql['name']; ?></td>
				<td style="text-align:center;"><?php echo $mysql['size']; ?> MB</td>
			</tr>		
		</tbody>
	</table>
</div>


<div style="float:right;width: 48%;">
	<h3><?php _e('Create database backup', UMBRELLA__TEXTDOMAIN); ?></h3>
	<p><?php _e('This will export all of your database tables into a downloadable SQL-file.', UMBRELLA__TEXTDOMAIN); ?></p>
	
	<a href="?page=umbrella-database&amp;do=create-backup" class="button button-primary"><?php _e('Create Backup File Now', UMBRELLA__TEXTDOMAIN); ?></a>
	
</div>




<?php Umbrella\Controller::footer(); ?>
<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
Umbrella\Controller::header(); ?>

<p>
	<?php _e('This plugin is doing nothing by default, and that\'s because we want you to know whats happening behind the scenes. 
	With that said, please choose wich modules you want to use with Umbrella. Even if nothing is loaded by default, 
	we recommend you to activate them all for best protection. Cheers =)', UMBRELLA__TEXTDOMAIN); ?>
</p>

<form method="post" action="options.php">

    <?php 
    	settings_fields( 'umbrella-settings' ); 
     	do_settings_sections( 'umbrella-settings' ); 
     	$load_modules = get_option( 'umbrella_load_modules' );
 	?>

 	<table class="wp-list-table widefat plugins">
		<thead>
			<tr>
				<th class="manage-column column-cb check-column"><input type="checkbox"></th>
				<th style="width: 150px;"><?php _e('Module', UMBRELLA__TEXTDOMAIN); ?></th>
				<th><?php _e('Description', UMBRELLA__TEXTDOMAIN); ?></th>
			</tr>
		</thead>

		<tfoot>
			<tr>
				<th class="manage-column column-cb check-column"><input type="checkbox"></th>
				<th style="width: 150px;"><?php _e('Module', UMBRELLA__TEXTDOMAIN); ?></th>
				<th><?php _e('Description', UMBRELLA__TEXTDOMAIN); ?></th>
			</tr>
		</tfoot>

		<tbody id="the-list">

		<?php foreach($available_options as $opt): ?>
			<tr class="alternate <?php

				if (isset( $load_modules[$opt[0]] ) AND $load_modules[$opt[0]] == 1)
					echo 'active';
				else
					echo 'inactive';

			?>">
				<th scope="col" id="cb" class="manage-column column-cb check-column" style="">
					<input name="umbrella_load_modules[<?php echo $opt[0]; ?>]" type="checkbox" value="1" 
					<?php checked( isset( $load_modules[$opt[0]] ) ); ?> />
				</th>
				<td class="settings-name"><?php echo $opt[1]; ?></td>
				<td><?php echo $opt[2]; ?></td>
			</tr>	
		<?php endforeach; ?>			
		</tbody>
	</table>
    
    <style type="text/css">
		tbody tr td.settings-name {
		    font-weight: bold;
		}

    </style>
    <?php submit_button(); ?>

</form>

<?php Umbrella\Controller::footer(); ?>
<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
Umbrella\Controller::header(); ?>

<div style="width: 25%;float: right;">

	<h3><?php _e('Hosting Status', UMBRELLA__TEXTDOMAIN); ?></h3>
	<table class="wp-list-table widefat plugins">
		<tbody id="the-list">
		<tr>
			<th style="font-weight:bold;"><?php _e('Server IP', UMBRELLA__TEXTDOMAIN); ?></th>
			<td><?php echo $ip; ?></td>
		</tr>
		<tr>
			<th style="font-weight:bold;"><?php _e('Server Software', UMBRELLA__TEXTDOMAIN); ?></th>
			<td><?php echo $software; ?></td>
		</tr>
		<tr>
			<th style="font-weight:bold;"><?php _e('Server Protocol', UMBRELLA__TEXTDOMAIN); ?></th>
			<td><?php echo $protocol; ?></td>
		</tr>
		<tr>
			<th style="font-weight:bold;"><?php _e('Domains on IP', UMBRELLA__TEXTDOMAIN); ?></th>
			<td><?php echo count($shared_domains); ?> domains</td>
		</tr>
		</tbody>
	</table>

	<h3><?php _e('Google Safe Browsing', UMBRELLA__TEXTDOMAIN); ?></h3>
	<table class="wp-list-table widefat plugins">
		<tbody id="the-list">
		<tr>
			<th style="font-weight:bold;"><?php _e('Status code', UMBRELLA__TEXTDOMAIN); ?></th>
			<td><?php echo $safebrowsing_status_code; ?>
			<?php if ($safebrowsing_status_code==204): ?>
				<strong style="color:green">OK</strong>
			<?php endif; ?>
			</td>
		</tr>
		</tbody>
	</table>

	<h3><?php _e('Cloudflare', UMBRELLA__TEXTDOMAIN); ?></h3>
	<table class="wp-list-table widefat plugins">
		<tbody id="the-list">
		<tr>
			<th style="font-weight:bold;">
				<?php if (true === Umbrella\Scanner::has_cloudflare()): ?>
					<?php _e('Protected by Cloud Flare', UMBRELLA__TEXTDOMAIN); ?>
				<?php else: ?>
					 <a target="_blank" href="https://www.cloudflare.com/" style="color:green;"><?php _e('Set up @ CloudFlare.com', UMBRELLA__TEXTDOMAIN); ?></a>
				<?php endif; ?>
			</th>
		</tr>
		</tbody>
	</table>

</div>

<div style="width: 70%; float:left;">
	
	<h3 style="clear:both;"><?php _e('Modules', UMBRELLA__TEXTDOMAIN); ?></h3>
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



</div>

<?php Umbrella\Controller::footer(); ?>
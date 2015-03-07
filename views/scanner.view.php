<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
Umbrella\Controller::header(); ?>
<h4><?php _e('File Scanner', UMBRELLA__TEXTDOMAIN); ?></h4>

<p>
	<?php _e('This will scan your core folders for unexpected files or modifications.', UMBRELLA__TEXTDOMAIN); ?>
</p>

<p id="umbrella-scan-console">
	<button id="startscanner" class="button button-primary button-large"><?php _e('Begin scanning', UMBRELLA__TEXTDOMAIN); ?></button>
</p>
<p>
	<div id="progressbar">
		<?php Umbrella\Scanner::progressbar(); ?>
	</div>
</p>

<table class="wp-list-table widefat plugins">
	<thead>
		<tr>
			<th style="width:120px;"><?php _e('Error', UMBRELLA__TEXTDOMAIN); ?></th>
			<th><?php _e('File path', UMBRELLA__TEXTDOMAIN); ?></th>
			<th style="width:300px;"><?php _e('Action', UMBRELLA__TEXTDOMAIN); ?></th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<th style="width:120px;"><?php _e('Error', UMBRELLA__TEXTDOMAIN); ?></th>
			<th><?php _e('File path', UMBRELLA__TEXTDOMAIN); ?></th>
			<th style="width:300px;"><?php _e('Action', UMBRELLA__TEXTDOMAIN); ?></th>
		</tr>
	</tfoot>

	<tbody id="the-list">
	</tbody>
</table>

<style type="text/css">
	#progressbar {
		background: #e1e1e1;
		height: 10px;
	}
	.progress {
		background: #2980b9;
		height: 10px;
		width: 1%;
		float: left;
		visibility: hidden;
	}
</style>
<?php Umbrella\Controller::footer(); ?>
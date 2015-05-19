<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
Umbrella\Controller::header($data);
?>
<div style="width: 45%;float:right;">
	<h3><?php _e('An introduction to Umbrella Network', UMBRELLA__TEXTDOMAIN); ?></h3>
	<p><?php _e('Do you manage a lot of WordPress sites?', UMBRELLA__TEXTDOMAIN); ?> <strong>Then you'll love this.</strong></p> We will <u>soon</u> release our PRO feature <strong>Umbrella Network</strong>. Upgrade to PRO and get access to Umbrella Network where you can <strong>manage all of your security issues, on all of your web sites, from one panel</strong>. Link all of your websites together and we will make monitored security scans on all of your WordPress sites once an hour. If we find anything suspicious, we'll send you an email right away!</p>

	<h3><?php _e('Get a BETA invitation', UMBRELLA__TEXTDOMAIN); ?></h3>
	<p><?php _e('We will soon release our first BETA version. And when we do, we\'ll give out BETA invites to all of our PRO users.', UMBRELLA__TEXTDOMAIN); ?>
	</p>
</div>

<div style="width: 45%;">
	<div id="umbrella_serial_box_1">
		<h3 class="nomargin"><?php _e('Upgrade to PRO', UMBRELLA__TEXTDOMAIN); ?></h3>
		<p style="float:right;margin-top: 10px;"><a href="http://jvz7.com/c/290723/159515" target="_blank" class="button button-primary"><?php _e('Get your license key', UMBRELLA__TEXTDOMAIN); ?></a>
		</p>
		<p style="width: 70%;"><?php _e('Please register at umbrellaplugins.com to receive your license key.', UMBRELLA__TEXTDOMAIN); ?></p>
	</div>	
	<div id="umbrella_serial_box_2">
		<h3><?php _e('Activate your license', UMBRELLA__TEXTDOMAIN); ?></h3>
		
		<form id="validate-key" action="#" class="form">
			<input type="submit" class="button button-primary" value="<?php _e('Use this key', UMBRELLA__TEXTDOMAIN); ?>" style="float:right;">
			<p style="width: 70%;">
				<input name="key" id="license-key" placeholder="Insert license key here" value="<?php echo esc_attr(get_option('umbrella_sp_serial')); ?>" type="text" class="big-input" style="width:100%;">
			</p>
		</form>
	</div>
</div>
<?php
Umbrella\Controller::footer();
?>
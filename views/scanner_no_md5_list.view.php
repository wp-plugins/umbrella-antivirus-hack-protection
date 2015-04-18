<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
Umbrella\Controller::header();
global $wp_version;
?>
<p>
<?php 
if (UMBRELLA__LATEST_WP_VERSION < $wp_version):
?>
	<?php _e('An update for',UMBRELLA__TEXTDOMAIN); ?>
	<strong>WordPress <?php echo $wp_version; ?></strong>
	<?php _e('will be available within a few hours.', UMBRELLA__TEXTDOMAIN); ?>
	</p><p>
	<?php _e('We guess your\'e an early bird and just updated to the latest WordPress version.',UMBRELLA__TEXTDOMAIN); ?>
	<?php _e('Our database is not updated yet, but don\'t worry cause if you just made an update, you\'re core-files should be fine.',UMBRELLA__TEXTDOMAIN); ?>
	</p><p>
	<strong style="color:red"><?php _e('Please also make sure that Umbrella is running the latest version', UMBRELLA__TEXTDOMAIN); ?></strong>
<?php
else:
	_e('This scanner only works with newer versions of WordPress. Please update your core-files.',UMBRELLA__TEXTDOMAIN);
endif;
?>
</p>


<?php Umbrella\Controller::footer(); ?>
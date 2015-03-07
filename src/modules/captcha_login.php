<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Displaying the Captcha Field in the Login Form
add_action('login_form', function (){
  if(class_exists('ReallySimpleCaptcha'))
  {
    $captcha_instance = new ReallySimpleCaptcha();
    $word = $captcha_instance->generate_random_word();
    $prefix = mt_rand();
    $captchaimg = $captcha_instance->generate_image( $prefix, $word );
    $imgpath = plugins_url().'/really-simple-captcha/tmp/'.$captchaimg;

    echo "<input type='hidden' name='umbrella_captcha_prefix' value='{$prefix}'/>";
    echo "<img src='{$imgpath}' style='float: right;position: absolute;margin-left: 190px;margin-top: 11px;' /><input name='umbrella_captcha_text' type='text' />";
  }
});

// Validating the Captcha
add_filter('wp_authenticate_user', function($user, $password) {
	$return_value = $user;

	if(class_exists('ReallySimpleCaptcha')){

		$captcha_instance = new ReallySimpleCaptcha();
		$prefix = $_POST['umbrella_captcha_prefix'];
		if(!$captcha_instance->check( $prefix, $_POST['umbrella_captcha_text'] ))
		{
		  // if there is a mis-match
		  $return_value = new WP_Error( 'loginCaptchaError', 'Captcha Error. Please try again.' );
		}

		// remember to remove the prefix
		$captcha_instance->remove( $prefix );
	}
	return $return_value;

} ,10,2);

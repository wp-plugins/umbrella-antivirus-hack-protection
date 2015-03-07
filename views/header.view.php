<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap about-wrap">

	<h1>
		Umbrella
		<!-- a href="#" class="add-new-h2">More info @ umbrellaantivirus.io</a -->
	</h1>

	<div class="about-text">
		<?php _e('Umbrella helps you protect your WordPress site and checks your plugin and themes for known vulnerabilities. 
More functions are planned and will be launched with the next update.', UMBRELLA__TEXTDOMAIN); ?>
	</div>

	<h2 class="nav-tab-wrapper">
	
		<?php foreach($navbars as $nav): ?>
			<a href="admin.php?page=<?php echo $nav[0]; ?>" class="nav-tab <?php
				if (isset($_GET['page']) AND $_GET['page'] == $nav[0])
					echo 'nav-tab-active'
			?>"><?php echo $nav[1]; ?></a>
		<?php endforeach; ?>

	</h2>

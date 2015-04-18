<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap">
<h1 id="umbrella-title">
	Site Protection
	<!-- a href="#" class="add-new-h2">More info @ umbrellaantivirus.io</a -->
</h1>
<div id="umbrella-site-protection">

	<nav id="umbrella-nav">
		<ul>
			<?php foreach($navbars as $nav): ?>
				<li class="<?php
					if (isset($_GET['page']) AND $_GET['page'] == $nav[0])
						echo 'current'
				?>"><a href="admin.php?page=<?php echo $nav[0]; ?>"><?php echo $nav[1]; ?></a>
				</li>
			<?php endforeach; ?>
		</ul>
	</nav>

	<div class="spacer"></div>

	<?php do_action('admin_notices'); ?>

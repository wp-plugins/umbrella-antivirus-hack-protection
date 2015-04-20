<?php if ( ! defined( 'ABSPATH' ) ) exit;  ?>
<div class="wrap">
<div id="umbrella-site-protection">

	<img id="header-img" src="<?php echo UMBRELLA__PLUGIN_URL; ?>/img/header.png" alt="">

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

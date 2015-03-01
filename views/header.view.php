<div class="wrap about-wrap">

	<h1>Umbrella</h1>

	<div class="about-text">
		WordPress Antivirus and Hack protection. This is just a BETA version. More functions are planned and will be launched soon. Look for an update :)
	</div>

	<h2 class="nav-tab-wrapper">
	
		<?php foreach($navbars as $nav): ?>
			<a href="admin.php?page=<?php echo $nav[0]; ?>" class="nav-tab <?php
				if (isset($_GET['page']) AND $_GET['page'] == $nav[0])
					echo 'nav-tab-active'
			?>"><?php echo $nav[1]; ?></a>
		<?php endforeach; ?>

	</h2>

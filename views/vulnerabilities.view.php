<?php Umbrella\Controller::header(); ?>

<h4><?php _e('Plugin Vulnerabilities', 'umbrella'); ?></h4>
<p>
</p>
<table class="wp-list-table widefat plugins">
	<thead>
		<tr>
			<th><?php _e('Plugin Name', 'umbrella'); ?></th>
			<th><?php _e('Plugin Version', 'umbrella'); ?></th>
			<th style="width:60%;"><?php _e('Vulnerabilities', 'umbrella'); ?></th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<th><?php _e('Plugin Name', 'umbrella'); ?></th>
			<th><?php _e('Plugin Version', 'umbrella'); ?></th>
			<th><?php _e('Vulnerabilities', 'umbrella'); ?></th>
		</tr>
	</tfoot>

	<tbody id="the-list">
		<?php foreach($plugins as $plugin): ?>
		<tr>
			<td>
				<strong><?php echo esc_attr($plugin['Name']); ?></strong><br>
				<em>by <?php echo esc_attr($plugin['Author']); ?></em>
			</td>
			<td><?php echo esc_attr($plugin['Version']); ?></td>
			<td>
				<?php if ($plugin['vulndb']['response']['code'] == '404'): ?>
					No known vulnerabilities.
				<?php 
				else: 
					$vulndb = json_decode($plugin['vulndb']['body']); 
					foreach($vulndb->plugin->vulnerabilities as $v):

					if ($v->fixed_in <= $plugin['Version'])
						$color = 'green';
					else
						$color = 'red';
				?>
					<strong style="color:<?php echo $color; ?>"><?php echo $v->title; ?> <small class="fixed_in">(fixed in <?php echo $v->fixed_in; ?>)</small> </strong><br>
					<?php foreach($v->url as $url): ?>
						<a href="<?php echo $url; ?>"><?php echo $url; ?></a><br>
					<?php endforeach; ?>
				<?php 
					endforeach;
				endif; ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<h4><?php _e('Theme Vulnerabilities', 'umbrella'); ?></h4>
<p>
</p>
<table class="wp-list-table widefat plugins">
	<thead>
		<tr>
			<th><?php _e('Theme Name', 'umbrella'); ?></th>
			<th><?php _e('Theme Version', 'umbrella'); ?></th>
			<th style="width:60%;"><?php _e('Vulnerabilities', 'umbrella'); ?></th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<th><?php _e('Theme Name', 'umbrella'); ?></th>
			<th><?php _e('Theme Version', 'umbrella'); ?></th>
			<th><?php _e('Vulnerabilities', 'umbrella'); ?></th>
		</tr>
	</tfoot>

	<tbody id="the-list">
		<?php foreach($themes as $theme): ?>
		<tr>
			<td>
				<strong><?php echo esc_attr($theme['Name']); ?></strong><br>
				<em>by <?php echo esc_attr($theme['Author']); ?></em>
			</td>
			<td><?php echo esc_attr($theme['Version']); ?></td>
			<td>
				<?php if ($theme['vulndb']['response']['code'] == '404'): ?>
					No known vulnerabilities.
				<?php 
				else: 
					$vulndb = json_decode($theme['vulndb']['body']); 
					foreach($vulndb->theme->vulnerabilities as $v):

					if ($v->fixed_in <= $theme['Version'])
						$color = 'green';
					else
						$color = 'red';
				?>
					<strong style="color:<?php echo $color; ?>"><?php echo $v->title; ?> <small class="fixed_in">(fixed in <?php echo $v->fixed_in; ?>)</small></strong><br>
					<?php foreach($v->url as $url): ?>
						<a href="<?php echo $url; ?>"><?php echo $url; ?></a><br>
					<?php endforeach; ?>
				<?php 
					endforeach;
				endif; ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php Umbrella\Controller::footer(); ?>
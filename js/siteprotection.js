jQuery(document).on('ready', function() {
	jQuery("#umbrella-site-protection .error.umbrella").fadeIn();


	jQuery('#validate-key').submit(function(e){

	    jQuery.ajax({ 
			data: {
				action: 'validate_key',
				key: jQuery("#license-key").val()
			},
			type: 'post',
			url: ajaxurl,
	        success: function(data) {
	  			var obj = JSON.parse(data);
	  			alert(obj.message);

	  			if (obj.status == 1 || obj.status == 4)
	  				location.href = 'admin.php?page=umbrella-site-protection';
	  		}
	    });

	    return false;

	})
});
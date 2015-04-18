jQuery("#startscanner").on('click', function() {

	$ = jQuery;
	$console = $('#umbrella-scan-console');
	$('#umbrella-scan-console button').attr('disabled', 'disabled').removeClass('button-primary');
	$thelist = $('#the-list');

	$('#no-errors-found').fadeOut();
	$('#filescanner').fadeOut();
	$('tbody').empty();

	var data = {
		'action': 'umbrella_filescan',
		'whatever': 1234
	};

	$.post(ajaxurl, data, function(response) {

		console.log(response);
		
		var fileslist = JSON.parse(response);

		$('#umbrella-scan-console button').removeAttr('disabled').addClass('button-primary');

		// If no errors is found.
		if (fileslist.length == 0)
			$('#no-errors-found').fadeIn();

		else {

			$('#filescanner').fadeIn();

			// Else if error is found..
			for (var x = fileslist.length - 1; x >= 0; x--) {
				var buttons = '';
				var file = fileslist[x];

				if (file.response.buttons) {

					for (var i = file.response.buttons.length - 1; i >= 0; i--) {
						buttons += '<a href="' + file.response.buttons[i].href + '" class="button">' + file.response.buttons[i].label + '</a>';
					};
					
				}
				$thelist.append("<tr class='alternate'><td><strong>"+file.response.error.msg+"</strong><br><small>#"+file.response.error.code+"</small></td><td>"+file.file+"</td><td>"+buttons+"</td></tr>");
			};
		}

		
	});

});

function umbrella_check_file( file, percent, index)
{
	$.get( "admin.php?page=umbrella-scanner&action=check_file&file=" + file, function( data ) {
		
		$console.text("Scanning: " + file);
		$("#progress" + percent).css('visibility', 'visible');
		var obj = JSON.parse(data);
		var buttons = "";

		if(obj.error)
		{

			if (obj.buttons) {

				for (var i = obj.buttons.length - 1; i >= 0; i--) {
					buttons += '<a href="' + obj.buttons[i].href + '" class="button">' + obj.buttons[i].label + '</a>';
				};
				
			}

			$thelist.append("<tr class='alternate'><td><strong>"+obj.error.msg+"</strong><br><small>#"+obj.error.code+"</small></td><td>"+obj.file+"</td><td>"+buttons+"</td></tr>");
		}

	});
}


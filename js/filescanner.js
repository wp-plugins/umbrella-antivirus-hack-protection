jQuery("#startscanner").on('click', function() {

	$ = jQuery;
	$console = $('#umbrella-scan-console');
	$console.text('Scanner initialized. Downloading files list.');
	$thelist = $('#the-list');

	$.get( "admin.php?page=umbrella-scanner&action=get_files", function( data ) {

		var obj = JSON.parse(data);

		for (var i =  0; i <= obj.length - 1; i++) {
			percent = i / obj.length;
			percent = percent * 100;
			percent = Math.round(percent);
			umbrella_check_file(obj[i], percent, i);
		};

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


// INFO: logud() REMOVES COOKIES AND RELOADS THE PAGE
function logud() {
  console.log("User logged out...");
	document.cookie = 'id=; expires=Thu, 01 Jan 1970 00:00:00 UTC;';
	window.location.href = "";
}


// INFO: validateForm() CHECKS THAT THE USER TYPED AND ID OR MADE A SELECTION
function validateForm() {
  console.log("Login form used...");
	return ($('#lb_usr').val() != '' || $('#lb_sel').val() != null);
}


// INFO: DYNAMICALLY SHOWS AND HIDES DIVS WITH SHOPTION
$('.not_title').click(function () {
  console.log(".not_title clicked: " + $(this).find(".code").text());
	if (!$(this).next().hasClass('shopped')) {
		$(this).next().toggle();
	} else {
		if ($(this).next().css('display') == 'none') {
			$(this).next().show();
			$(this).next().next().show();
		} else {
			$(this).next().hide();
			$(this).next().next().hide();
		}
	}
});


// INFO: RETRIEVES SHOPTIONS THROUGH CODE AND ACTIVITY SCRIPTS
$('.code').click(function () {
  console.log(".code clicked: " + $(this).text());
	if (!$(this).parent().next().hasClass('shopped')) {
		var shopped_activities = '<img src="res/logo_shop.gif" style="height:80px;width:80px !important;" class="centered"/><p>KU\'s servere er sløve, men hvis loading af resultater tager urimeligt langt tid (30-60s), er der nok noget andet galt.<br />Skriv til mig i så fald: <a href="https://www.facebook.com/davidsvane" target="_blank" style="display:inline">David Svane</a> 😁</p>';
		var input = $(this).text();
		var input_fix = input.replace(/\W/g, '');

		$(this).parent().after('<tr class="shopped" id="shop_' + input_fix + '" style="display:none;"><td colspan="4" style="padding:1em 0;">' + shopped_activities + '</td></tr>');

		$.post("js/get_shop_codes.php", {c: input}, function(data) {
      console.log("Shop code lookup result: " + data);
			var obj = JSON.parse(data);
			if (obj.length > 0) {
				for (var i = 0; i < obj.length; i++) {
					$.post("js/get_shop_activities.php", {c: input, a: obj[i]}, function(data) {
            console.log("Shop activity lookup result: " + data);
            var classes = JSON.parse(data);
						$('#shop_' + input_fix + ' td').html('<table></table>');
						$('#shop_' + input_fix + ' table').append();
						$('#shop_' + input_fix + ' table').append("<tr class='shop_titles'><td>Aktivitet</td><td>Beskrivelse</td><td>Type</td><td>Dag</td><td>Dato</td><td>Tid</td><td>Slut</td><td>Lokale</td><td>Underviser</td></tr>");
						for (var i = 0; i < classes.length; i++) {
							$('#shop_' + input_fix + ' table').append(classes[i]);
						}
					});
				}
			} else {
        $('#shop_' + input_fix + ' td').html('<p>Skriv dit KU-ID til <a href="https://facebook.com/davidsvane" target="_blank" style="display: inline;">David Svane</a>, og sig at der ikke blev fundet nogle "Shop Codes", hvis du mener at der burde komme resultater frem.<br />Slår du fællesforelæsninger eller unikke undervisningstimer op, vil der nok ikke komme andet frem end denne besked.</p>');
      }
		});
	}
});

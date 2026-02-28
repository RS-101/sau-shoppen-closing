<?php

	require 'pages/dropdown_options.php';

?>

<div id="login_screen" class="center_full">
	<div id="rendered_login" class="center_ab">

		<!-- FRONT PAGE MAIN TITLE -->
		<h1 class="centered"><b>SAU</b>Shoppen</h1>

		<!-- LOGIN FORM -->
		<form action="index.php" onsubmit="return validateForm()" method="post">

			<div id="form_options">

				<!-- LOGIN WITH KU ID -->
				<div id="opt_1">
					<input type="text" name="id" id="lb_usr" class="centered inputs" pattern="[A-Za-z0-9]{6}" placeholder="KU ID" maxlength="6" autofocus></input>
				</div>

			</div>

			<input type="submit" class="centered" value="Vis skema" id="lb_btn"></input>

		</form>

	</div>

	<?php

		require 'pages/page_front.php';

	?>
</div>

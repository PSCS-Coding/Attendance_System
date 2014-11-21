<html>
	<title>test</title>
	<body>
		<?php
			session_start();

			$_SESSION['prevURL'] = $_SERVER['REQUEST_URI'];
			
			//make this $_SESSION['adminSet'] if it's an admin-only page
			if(!$_SESSION['set'])
				{
					header("location: main_login.php");
				}
		?>
	</body>
</html>
<DOCTYPE html>
<html>
<head>
<title>*Sigh*</title>
</head>
<body>
<?php print_r($_POST); ?>
<form id="main" method="post" action="simple.php">
<input type="hidden" name="nic" value="awesome">
<input type="submit" name="submit" value="submit">
</form>
<?php
function functionBad() {
echo 'The function was called.';
echo '<br />';
echo '<input type="submit" form="main" name="go" value="go">';
}
if (isset($_POST['submit'])) {
functionBad();
}
if (isset($_POST['go'])) {
functionBad();
echo '<br />';
echo 'Go was set and the function was called.';
}
?>
</body>
</html> 
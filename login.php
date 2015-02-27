        <?php
        require_once("connection.php");
        
        $LoginPhpDateTime = new dateTime();
        $currentLoginDate = $LoginPhpDateTime->format('dym');

        if ($LoginResult = $db_server->query("SELECT * FROM login WHERE username='pscs'"))
{
        $LoginRow = $LoginResult->fetch_assoc();
	
        $LoginResult->free();
}
            $studentPW = $LoginRow['password'];
            $adminPW = $LoginRow['adminPass'];
            // Appdends date to password
            $SecureAdminPW = $adminPW;
            $SecureAdminPW .= crypt($currentLoginDate, 'M7');
            $SecureStudentPW = $studentPW;
            $SecureStudentPW .= crypt($currentLoginDate, 'M7');
            $crypt = crypt('adenz8r3ry8nyinynzyi', 'P9');
        if (isset($_COOKIE["login"])) {
          
            // Check if cookie diffrent from student or admin
            if ($_COOKIE["login"] == $SecureAdminPW || $_COOKIE["login"] == $SecureStudentPW || $_COOKIE["login"] == $crypt) { 
            // Leave blank   
} else {
    echo '<META http-equiv="refresh" content="0;URL=secondary_login.php">';
} 
          if (!empty($userlevel) && $_COOKIE["login"] == $SecureStudentPW && $userlevel == "admin") {
            echo '<META http-equiv="refresh" content="0;URL=secondary_login.php">'; 
          }
        } else {
            echo '<META http-equiv="refresh" content="0;URL=secondary_login.php">';
        }
        ?>
        <?php
        require_once("connection.php");
        
        $phpdatetime = new dateTime();
        $currentDate = $phpdatetime->format('dym');

        if ($result = $db_server->query("SELECT * FROM login WHERE username='pscs'"))
{
        $row = $result->fetch_assoc();
	
        $result->free();
}
            $studentPW = $row['password'];
            $adminPW = $row['adminPass'];
            // Appdends date to password
            $SecureAdminPW = $adminPW;
            $SecureAdminPW .= crypt($currentDate, 'M7');
            $SecureStudentPW = $studentPW;
            $SecureStudentPW .= crypt($currentDate, 'M7');
            $crypt = crypt('adenz8r3ry8nyinynzyi', 'P9');

        if (isset($_COOKIE["login"])) {
          
            // Check if cookie diffrent from student or admin
            if ($_COOKIE["login"] == $SecureAdminPW || $_COOKIE["login"] == $SecureStudentPW || $_COOKIE["login"] == $crypt) { 
            // Leave blank   
} else {
    echo '<META http-equiv="refresh" content="0;URL=secondary_login.php">';
} 
          if ($_COOKIE["login"] == $SecureStudentPW && $userlevel == "admin") {
            echo '<META http-equiv="refresh" content="0;URL=secondary_login.php">'; 
          }
        } else {
            echo '<META http-equiv="refresh" content="0;URL=secondary_login.php">';
        }
        ?>
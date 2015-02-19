        <?php
        // Get Connection.php
        require_once('connection.php');
        //Querying logintest database
        if ($result = $db_server->query("SELECT * FROM login WHERE username='pscs'"))
        {
        $row = $result->fetch_assoc();
        $result->free();
        }
    // Set passwords for comparison later in document
    $adminpass = $row['adminPass'];
    $studentpass = $row['password'];
    $md5 = md5('adenz8r3ry8nyinynzyi');

        //Starting IF statements
        if (!empty($_COOKIE["login"])) {
  
            if ($_COOKIE["login"] == $adminpass) {
                
                // Give Full Access
                
            } elseif ($_COOKIE["login"] == $studentpass) {
                
                if ($userlevel == "admin") {
                    echo '<META http-equiv="refresh" content="0;URL=secondary_login.php">';   
                }
                
            }
            
            
        } else {
echo '<META http-equiv="refresh" content="0;URL=secondary_login.php">';
}

// Check if cookie diffrent from student & admin
if ($_COOKIE["login"] == $adminpass || $_COOKIE["login"] == $studentpass || $_COOKIE["login"] == $md5) {
    
    // Leave blank
    
} else {
    echo '<META http-equiv="refresh" content="0;URL=secondary_login.php">';
}
        ?>
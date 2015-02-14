        <?php
        // Set passwords for comparison later in document
        $adminpass = md5("admin1387409");
        $studentpass = md5("student634729779");
        $md5 = "5f588e3830e410ca27828a9d4136de94";
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
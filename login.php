        <?php
        if (isset($_COOKIE["login"])) {
  
            if ($_COOKIE["login"] == "admin") {
                
                // Give Full Access
                
            } elseif ($_COOKIE["login"] == "student") {
                
                if ($userlevel == "admin") {
                    echo '<META http-equiv="refresh" content="0;URL=secondary_login.php">';   
                }
                
            }
            
            
        } else {
echo '<META http-equiv="refresh" content="0;URL=secondary_login.php">';
}
        ?>
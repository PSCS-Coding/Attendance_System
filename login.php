        <?php
        if (isset($_COOKIE["loginV2"])) {
  
            if (password_verify('admin12873912', $_COOKIE["loginV2"])) {
                
                // Give Full Access
                
            } elseif (password_verify('student87162387', $_COOKIE["loginV2"])) {
                
                if ($userlevel == "admin") {
                    echo '<META http-equiv="refresh" content="0;URL=secondary_login.php">';   
                }
                
            }
            
            
        } else {
echo '<META http-equiv="refresh" content="0;URL=secondary_login.php">';
}
        ?>
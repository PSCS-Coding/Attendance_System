        <?php
    if (!empty($_COOKIE["loginV2"])) {
  
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
// If login is not equal to student or admin redirect
if (password_verify('admin12873912', $_COOKIE["loginV2"]) || password_verify('student87162387', $_COOKIE["loginV2"])) {
// Leave Empty    
}else {
echo '<META http-equiv="refresh" content="0;URL=secondary_login.php">';
}
        ?>
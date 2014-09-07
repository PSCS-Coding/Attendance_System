// ADD A NEW Facilitator			
if (isset($_POST['addnew'])) {
		if($_POST['advisor']=="Advisor?"){
				echo "Please choose an advisor status.";
		} else {
                
                
                
                <!--<select name='advisor'><option>Advisor?</option>
		<!--START OF GET OPTIONS AND QUERY STUFF-->
	        <?php $advisorget = $db_server->query("SELECT * FROM staff ORDER BY info ASC");
		      while ($advisor_option = $advisorget->fetch_assoc()) {
	        ?>  <option value= '<?php echo $advisor_option['info']; ?> '> <?php echo $advisor_option['info']; ?></option>
		<?php } ?></select><br />-->
                
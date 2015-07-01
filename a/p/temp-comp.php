////////DELETE FUNCTION/////////
if (!empty($_POST['Delete'])) {

$student_id = $_POST['sid'];

//MySqli Update Query
$results = $mysqli->query("UPDATE studentdata SET current='0' WHERE studentid = $student_id");

//MySqli Delete Query
//$results = $mysqli->query("DELETE FROM products WHERE ID=24");

if($results){
    print 'Success! record updated / deleted'; 
}else{
    print 'Error : ('. $mysqli->errno .') '. $mysqli->error;
}  

}

/////// INSERT FUNCTION //////////
if (!empty($_POST['addnew'])) {
    
//VALUES TO BE INSERTED INTO THE STUDENT DATA TABLE
$first_name = '"'.$mysqli->real_escape_string('AAron').'"';
$last_name = '"'.$mysqli->real_escape_string('Astion').'"';
$start_date = '"'.$mysqli->real_escape_string('2009-09-01').'"';
$advisor = '"'.$mysqli->real_escape_string('2009-09-01').'"';

    
//MySqli Insert Query
$insert_row = $mysqli->query("INSERT INTO studentdata (firstname, lastname, startdate, advisor) VALUES($first_name, $last_name, $start_date, $advisor)");

if($insert_row){
    print 'Success! ID of last inserted record is : ' .$mysqli->insert_id .'<br />'; 
}else{
    die('Error : ('. $mysqli->errno .') '. $mysqli->error);
}
    
}

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

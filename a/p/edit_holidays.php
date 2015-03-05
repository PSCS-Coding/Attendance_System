<html>
<head>
    <title>Edit Holidays</title>
    <?php require_once('header.php'); ?>
</head>
<body class="adminpage edit-holidays">
<!-- UPDATE FUNCTIONS -->
<?php
// Header Info
$HeaderStatus = null;
$HeaderInfo = "Update Holidays";
// ADD A NEW HOLIDAY
if (isset($_POST['addnewholiday'])) {
$date = strtotime($_POST['date']);
$stmt = $db_server->prepare("INSERT INTO holidays (holidayname, date) VALUES (?, FROM_UNIXTIME(?))");
$stmt->bind_param('ss', $_POST['holidayname'] , $date);
$stmt->execute();
$stmt->close();
$HolidayName = $_POST['holidayname'];
$HeaderStatus = "Sussess";
$HeaderInfo = "Sussessfully added holiday: $HolidayName";
}

// EDIT (UPDATE) A HOLIDAY
if (isset($_POST['saveholiday'])) {
 $date = strtotime($_POST['date']);
 $stmt = $db_server->prepare("UPDATE holidays SET holidayname = ? , date = FROM_UNIXTIME(?) WHERE id = ?");
      $stmt->bind_param('ssi', $_POST['holidayname'], $date, $_POST['id']);
      $stmt->execute();
      $stmt->close();
    }

// DELETE A HOLIDAY
if(isset($_POST['deleteholiday'])) {
    $stmt = $db_server->prepare("DELETE FROM holidays WHERE id = ?");
    $stmt->bind_param('i', $_POST['id']);
    $stmt->execute();
    $stmt->close();
$HeaderStatus = "Error";
$HeaderInfo = "Deleted Holiday.";
    }



// GET THE LIST OF HOLIDAYS
    $holidayresult = $db_server->query("SELECT * FROM holidays ORDER BY date");
     ?>
                <div id="TopHeader" class="<?php echo $HeaderStatus; ?>">
              <h1 class="Myheader"><?php echo $HeaderInfo; ?></h1>
                </div>
            <div align="center" id="main">
<div class="admintable">
<form style="margin-bottom:1em;" action="" method="post">
    <input type="text" name="holidayname" placeholder="Holiday Name" required size="15">
    <input type="text" name="date" id="date" placeholder="Holiday Date" required size="15">
    <input type="submit" name="addnewholiday" value="Add Holiday" />
</form>

 <table>
   <tr>
      <th>Holiday Name</th>
      <th>Date</th>
      <th>Edit</th>
      <th>Delete</th>
   </tr>
<?php
// Make list of holidays
while ($list = mysqli_fetch_assoc($holidayresult)) { ?>

<form action="" method="post">
<input type="hidden" name="id" value="<?php echo $list['id']; ?>">
    <tr>
        <?php $editme = "edit-" . $list['id'];
        if (isset($_POST[$editme])) {
        $adjusteddate = new DateTime($list['date']);
        ?>
        <td><input type="text" name="holidayname" class="textbox" value="<?php echo $list['holidayname']; ?>" required size="15"></td>
        <td><input type="text" name="date" class="textbox" id="editdate" value="<?php echo $adjusteddate->format('m-d-Y'); ?>" required size="15"></td>
        <td><button type="submit" name="saveholiday" value="<?php echo $list['id']; ?>">Save</button></td>
        <?php } else { ?>
        <td><?php echo $list['holidayname']; ?></td>
        <td><?php echo $list['date']; ?></td>

        <td><input type="submit" name="edit-<?php echo $list['id']; ?>" value="Edit"></td>
        <?php } ?>
        <td><button type="submit" name="deleteholiday" value="<?php echo $list['holidayname']; ?>">Delete</button></td>
    </tr>
</form>
<?php
} // end while
?>
</table>
  <!-- date picker javascript -->
<script src="js/pikaday.js"></script>
<script>
    var picker = new Pikaday({ field: document.getElementById('date') });
</script>
<script src="js/pikaday.js"></script>
<script>
    var picker = new Pikaday({ field: document.getElementById('editdate') });
</script>
    </div>
                    </div>
<script>
       $(document).ready(function() {
           $('#TopHeader').delay(1500);
           setTimeout(function() {
               $('#TopHeader').removeClass();
               $('#TopHeader .MyHeader').text('Update Holidays');
           }, 1700);


       });
</script>
</body>
</html>

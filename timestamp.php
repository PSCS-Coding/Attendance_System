    <!DOCTYPE html>
    <html>
    <head>
        <title>Timestamp</title>
    </head>
    <body>

<?php

    require_once('connection.php');
    require_once('function.php');

    // Query events
    $EventResult = $db_server->query("SELECT * FROM events ORDER BY eventid");
    $GlobalsResult = $db_server->query("SELECT * FROM globals");

    // Make list of all events
while ($EventList = mysqli_fetch_assoc($EventResult)) {
    ?>

        <!-- Allign Text Left -->
        <div style="text-allign: left;">

        <?php
            if ($EventList['tempreturntime'] != "0000-00-00 00:00:00") {
            echo "Either this has worked, or you did not make the database table correctly.";
            break;
            } else {
            $myTS = $EventList['timestamp'];
            $UpdatedTS = substr($myTS, 0, -9);
            $SQLReturnTime = $EventList['returntime'];
            $myReturnTime = $UpdatedTS.' '.$SQLReturnTime;
            $myINT = $EventList['eventid'];
                // Updated database

if ($EventList['statusid'] == 2 || $EventList['statusid'] == 3 || $EventList['statusid'] == 6) {
    if ($SQLReturnTime >= "01:00:00" && $SQLReturnTime <="03:30:00") {
            echo $SQLReturnTime;
            echo "--";
            $NotBrokenRT = new DateTime($SQLReturnTime);
            $NotBrokenRT ->add(new DateInterval('PT12H'));
            $myReturnTime = $NotBrokenRT;
            $myReturnTime = $myReturnTime->format('H:i:s');
            $myReturnTime = $UpdatedTS.' '.$myReturnTime;
            echo $myReturnTime;
    }
    }
            $NewRT = $db_server->prepare("UPDATE events SET tempreturntime = ? WHERE eventid = ?");
               $NewRT->bind_param('si', $myReturnTime, $myINT);
               $NewRT->execute();
        ?>

        </div>

<?php } // Ends if for new events thingy ?>
        <?php } // Ends while loop for events ?>
    </body>
    </html>

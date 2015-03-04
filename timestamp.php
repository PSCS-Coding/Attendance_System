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


    // Make list of all events

while ($EventList = mysqli_fetch_assoc($EventResult)) {

    ?>

        <!-- Allign Text Left -->
        <div style="text-allign: left;">

        <?php

            $myTS = $EventList['timestamp'];
            $UpdatedTS = substr($myTS, 0, -9);
            $SQLReturnTime = $EventList['returntime'];
            $myReturnTime = $UpdatedTS.' '.$SQLReturnTime;

        ?>

        <?php echo $EventList['info']; ?> - NEW RETURN TIME:
        <?php echo $myReturnTime; ?>

        </div>

<?php } // Ends while loop for events ?>


    </body>
    </html>

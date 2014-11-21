<!DOCTYPE html>
<html lang="en">

<head>

    <!-- jQuery -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    
    <!-- Bootstrap Core CSS -->
     <link href="style.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="style.css" rel="stylesheet">
      

    <!-- Custom Fonts -->
    <link href="font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">


</head>

<body>

    <!-- Navigation -->
    <font color="#999">
    <a id="menu-toggle" href="#">Click!</i></a>
    <nav id="sidebar-wrapper">
        <ul class="sidebar-nav">
            <a id="menu-close" href="#" class="btn btn-light btn-lg pull-right toggle"><i class="fa fa-times"></i></a>
            <li class="sidebar-brand">
                <a href="#">Change Log</a>
            </li>
        </ul>
    </nav>
    </font>


    <script>
    // Closes the sidebar menu
    $("#menu-close").click(function(e) {
        e.preventDefault();
        $("#sidebar-wrapper").toggleClass("active");
    });

    // Opens the sidebar menu
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#sidebar-wrapper").toggleClass("active");
    });
    </script>

</body>

</html>
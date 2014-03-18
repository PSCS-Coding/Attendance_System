<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Auto Complete</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.9.1.js"></script>
  <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
  <script>
  $(function() {
    var availableTags = [
      "9:00",
      "9:30",
      "9:50",
      "10:00",
      "10:30",
      "10:50",
      "11:00",
      "11:30",
      "12:00",
      "12:15",
      "12:30"
    ];
    $( "#tags" ).autocomplete({
      source: availableTags
    });
  });
  </script>
</head>
<body>
 
<div class="ui-widget">
  <label for="tags">I will be back at: </label>
  <input id="tags">
</div>
 
 
</body>
</html>
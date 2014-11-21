<script type=javascript>
// LOAD TABS FOR ADMIN PAGE
// LOAD TAB 1 (Students)
$(document).ready(function(){
  $("#students").hide();
$( "#button1" ).on( "click", function() {
    $("#globals").hide("slow");
    $("#holidays").hide("slow");
    $("#passwords").hide("slow");
    $("#allottedhours").hide("slow");
    $("#facilitators").hide("slow");
    $("#students").toggle("slow");
  });

  // LOAD TAB 2 (Facilitators)
  $("#facilitators").hide();
  $("#button2").click(function(){
    $("#globals").hide("slow");
    $("#holidays").hide("slow");
    $("#passwords").hide("slow");
    $("#allottedhours").hide("slow");
    $("#students").hide("slow");
    $("#facilitators").slideToggle("slow");
});
  
  // LOAD TAB 3 (Allotted-Hours)
  $("#allottedhours").hide();
  $("#button3").click(function(){
    $("#globals").hide("slow");
    $("#holidays").hide("slow");
    $("#passwords").hide("slow");
    $("#facilitators").hide("slow");
    $("#students").hide("slow");
    $("#allottedhours").slideToggle("slow");
});
  
  // LOAD TAB 4 (PASSWORDS)
  $("#passwords").hide();
  $("#button4").click(function(){
    $("#globals").hide("slow");
    $("#holidays").hide("slow");
    $("#allottedhours").hide("slow");
    $("#facilitators").hide("slow");
    $("#students").hide("slow");
    $("#passwords").slideToggle("slow");
});
  
  // LOAD TAB 5 (HOLIDAYS)
  $("#holidays").hide();
  $("#button5").click(function(){
    $("#globals").hide("slow");
    $("#passwords").hide("slow");
    $("#allottedhours").hide("slow");
    $("#facilitators").hide("slow");
    $("#students").hide("slow");
    $("#holidays").slideToggle("slow");
});
  // LOAD TAB 6 (GLOBALS)
  $("#globals").hide();
  $("#button6").click(function(){
    $("#holidays").hide("slow");
    $("#passwords").hide("slow");
    $("#allottedhours").hide("slow");
    $("#facilitators").hide("slow");
    $("#students").hide("slow");
    $("#globals").slideToggle("slow");
});
      });
</script>
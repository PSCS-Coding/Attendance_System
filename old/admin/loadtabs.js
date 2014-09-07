// LOAD TABS FOR ADMIN PAGE
// LOAD TAB 1 (Students)
$(document).ready(function(){
  $("#showdiv1").hide();
$( "#button1" ).on( "click", function() {
    $("#showdiv6").hide("slow");
    $("#showdiv5").hide("slow");
    $("#showdiv4").hide("slow");
    $("#showdiv3").hide("slow");
    $("#showdiv2").hide("slow");
    $("#showdiv1").toggle("slow");
  });

  // LOAD TAB 2 (Facilitators)
  $("#showdiv2").hide();
  $("#button2").click(function(){
    $("#showdiv6").hide("slow");
    $("#showdiv5").hide("slow");
    $("#showdiv4").hide("slow");
    $("#showdiv3").hide("slow");
    $("#showdiv1").hide("slow");
    $("#showdiv2").slideToggle("slow");
});
  
  // LOAD TAB 3 (Allotted-Hours)
  $("#showdiv3").hide();
  $("#button3").click(function(){
    $("#showdiv6").hide("slow");
    $("#showdiv5").hide("slow");
    $("#showdiv4").hide("slow");
    $("#showdiv2").hide("slow");
    $("#showdiv1").hide("slow");
    $("#showdiv3").slideToggle("slow");
});
  
  // LOAD TAB 4 (PASSWORDS)
  $("#showdiv4").hide();
  $("#button4").click(function(){
    $("#showdiv6").hide("slow");
    $("#showdiv5").hide("slow");
    $("#showdiv3").hide("slow");
    $("#showdiv2").hide("slow");
    $("#showdiv1").hide("slow");
    $("#showdiv4").slideToggle("slow");
});
  
  // LOAD TAB 5 (HOLIDAYS)
  $("#showdiv5").hide();
  $("#button5").click(function(){
    $("#showdiv6").hide("slow");
    $("#showdiv4").hide("slow");
    $("#showdiv3").hide("slow");
    $("#showdiv2").hide("slow");
    $("#showdiv1").hide("slow");
    $("#showdiv5").slideToggle("slow");
});
  // LOAD TAB 6 (GLOBALS)
  $("#showdiv6").hide();
  $("#button6").click(function(){
    $("#showdiv5").hide("slow");
    $("#showdiv4").hide("slow");
    $("#showdiv3").hide("slow");
    $("#showdiv2").hide("slow");
    $("#showdiv1").hide("slow");
    $("#showdiv6").slideToggle("slow");
});
      });
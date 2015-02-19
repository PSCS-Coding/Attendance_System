$(document).ready(function(){ 
    $('#offloc').keydown(function () {
        var max = 25;
        var len = $(this).val().length;
        if (len >= max) {
            $('#offloc').addClass('deletefave');           
        } 
        else {
            var ch = max - len;
            $('#offloc').removeClass('deletefave');          
        }
    });    
});

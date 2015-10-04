$(document).ready(function(){
    function clear_notifs(notifications){
        $.ajax({
           type: "POST",
           url: "../ajax/layout.php",
           data: {notifications:notifications},
           success: function(msg){
             $("#list_notifications").html(msg);
           }
        });
    }

    function clear_notif(idNotification){
        $.ajax({
            type: "POST",
            url: "../ajax/layout.php",
            data: "idNotification="+idNotification,
            success: function(msg){
                $("#notification_"+idNotification).html(msg);
            }
        });
    }
});
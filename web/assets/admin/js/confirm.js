'use strict'

//Gestion de la fenêtre pour le lien de suppression
$(document).ready(function() {
    var theHREF;

    $("#dialog-confirm").dialog({
        resizable: false,
        height:160,
        width:500,
        autoOpen: false,
        modal: true,
        buttons: {
            "Oui": function() {
                $(this).dialog("close");
                window.location.href = theHREF;
            },
            "Annuler": function() {
                $(this).dialog("close");
            }
        }
    });

    $("a.confirmModal").click(function(e) {
        e.preventDefault();
        theHREF = $(this).attr("href");
        $("#dialog-confirm").dialog("open");
    });
});

//Gestion de la fenêtre pour le lien de la publication
$(document).ready(function() {
    var theHREF;

    $("#dialog-confirm-modif").dialog({
        resizable: false,
        height:160,
        width:500,
        autoOpen: false,
        modal: true,
        buttons: {
            "Oui": function() {
                $(this).dialog("close");
                window.location.href = theHREF;
            },
            "Annuler": function() {
                $(this).dialog("close");
            }
        }
    });

    $("a.confirmModalp").click(function(e) {

        e.preventDefault();
        theHREF = $(this).attr("href");
        $("#dialog-confirm-modif").dialog("open");
    });
});

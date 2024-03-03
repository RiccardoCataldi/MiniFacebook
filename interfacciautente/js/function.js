// Avvisa su eventuali errori nel login. Se non ve ne sono reindirizza al proprio feed.
function submitLoginForm() {
    let email = document.getElementById("username").value;
    let password = document.getElementById("password").value;
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "backend/loginphp.php", true);


    xhr.onload = function () {
        let output = xhr.responseText.trim()

        if (output === "success") {
            window.location.href = "frontend/feed.php";
        } else {
            $('#erroreCredenziali').modal('show');
        }
    }
    xhr.send(new FormData(document.getElementById("loginForm")));
    return false;
}

// Manda il rating di un utente al database
function publishRating(emailPost, dataPubblicazione, rating) {
    // Get the message from the input field
    let ratingValue = rating;

    $.ajax({
        url: '../backend/rating.php',
        type: 'POST',
        data: {
            emailPost: emailPost,
            dataPubblicazione: dataPubblicazione,
            ratingValue: ratingValue
        },

        success: function (response) {
            //alert(response);
            if (response.trim() === 'success') {
                // Refresh the page
                location.reload();

            } else {
                alert('Errore durante la pubblicazione del messaggio');
            }
        }
    })

}

// Pubblica un messaggio attraverso una chiamata AJAX al backend
function publishMessage() {
    // Get the message from the input field
    let message = $('#form143').val();

    // Get the image file from the input field
    let image = $('#imageInput')[0].files[0];

    let città = $('#città').val();

    if (message == '' && image == undefined) {
        alert('Inserisci un messaggio o una foto');
        return; // Interrompi la pubblicazione se la condizione non è soddisfatta
    }

    if (città == 'Scegli città') {
        città = null;
    }

    if (image == undefined) {
        image = null;

    }
    //condizione per controllare la lunghezza del messaggio
    if (image == undefined && message.length > 100) {
        alert('Il messaggio supera la lunghezza massima consentita di 100 caratteri.');
        return; // Interrompi la pubblicazione se la condizione non è soddisfatta
    } else if (image != undefined && message.length > 50) {
        alert('La descrione dell\'immagine supera la lunghezza massima consentita di 50 caratteri.');
        return; // Interrompi la pubblicazione se la condizione non è soddisfatta
    }
    // Create a FormData object
    let formData = new FormData();

    // Append message and image to FormData
    formData.append('message', message);
    formData.append('image', image);
    formData.append('città', città);

    console.log(formData, message, image, città)
    $.ajax({
        url: '../backend/post.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            //alert(response);
            if (response.trim() === 'success') {
                // Refresh the page
                location.reload();

            } else {
                alert('Errore durante la pubblicazione del messaggio');
            }
        }
    })

}

// Funzione per cancellare un post
function delete_post(email, dataPubblicazione) {

    if (confirm("Sei sicuro di voler eliminare il post?")) {

        $.ajax({
            url: '../backend/delete_post.php',
            type: 'POST',
            data: {
                email: email,
                dataPubblicazione: dataPubblicazione
            },
            success: function (response) {
                //alert(response);
                if (response.trim() === 'success') {
                    // Refresh the page
                    location.reload();

                } else {
                    alert('Errore durante la pubblicazione del messaggio');
                }
            }
        })
    }




}

//Funzione che pubblica un commento
function publishComment(emailPost, dataPubblicazione, commentId, valutazioneId) {
    // Get the message from the input field

    let comment = $('#' + commentId).val();
    let valutazione = $('#' + valutazioneId).val();

    if (comment == '' && (valutazione == null || valutazione == 'Scegli valutazione')) {
        alert('Inserisci un commento');
        return; // Interrompi la pubblicazione se la condizione non è soddisfatta
    }

    if (comment.length > 100) {
        alert('Il commento supera la lunghezza massima consentita di 100 caratteri.');
        return; // Interrompi la pubblicazione se la condizione non è soddisfatta
    }

    if (valutazione == null || valutazione == 'Scegli valutazione') {
        valutazione = null;
    }

    $.ajax({
        url: '../backend/commentPost.php',
        type: 'POST',
        data: {
            comment: comment,
            emailPost: emailPost,
            dataPubblicazione: dataPubblicazione,
            valutazione: valutazione
        },

        success: function (response) {
            //alert(response);
            if (response.trim() == 'success') {
                // Refresh the page
                location.reload();

            } else {
                alert('Superato il numero massimo di 5 commenti per post');
            }
        }
    })

}

//Funzione che cancella un commento
function delete_comment(emailPost, dataPubblicazione, dataCommento) {

    if (confirm("Sei sicuro di voler eliminare il commento?")) {

        $.ajax({
            url: '../backend/delete_comment.php',
            type: 'POST',
            data: {
                emailPost: emailPost,
                dataPubblicazione: dataPubblicazione,
                dataCommento: dataCommento
            },
            success: function (response) {
                //alert(response);
                if (response.trim() === 'success') {
                    // Refresh the page
                    location.reload();

                } else {
                    alert('Errore durante la cancellazione del commento');
                }
            }
        })
    }
}

//Funzione che mostra il post di tipo testo in un dialog
function handleAnchorClickTesto(emailPost, dataPubblicazione, testo) {

    // Set dialog position close to the anchor tag
    $("#dialog").dialog({
        //remove titlebar

        position: {
            my: "left top",
            at: "left bottom",
            of: event.target
        },

        // Set up the content dynamically when opening the dialog
        open: function () {
            // Set the content of the dialog box
            $(this).html('<p><strong>Email:</strong> ' + emailPost + '</p><p><strong>Data Pubblicazione:</strong> ' + dataPubblicazione + '</p><p><strong>Testo:</strong> ' + testo + '</p>');
        }
    })
    $("#ui-dialog-title-dialog").hide();
    $(".ui-dialog-titlebar").removeClass('ui-widget-header');;
}

//Funzione che mostra il post di tipo foto in un dialog
function handleAnchorClickFoto(emailPost, dataPubblicazione, testo, foto) {

    // Set dialog position close to the anchor tag
    $("#dialog").dialog({
        //remove titlebar

        position: {
            my: "left top",
            at: "left bottom",
            of: event.target
        },

        // Set up the content dynamically when opening the dialog
        open: function () {
            // Set the content of the dialog box
            $(this).html('<p><strong>Email:</strong> ' + emailPost + '</p><p><strong>Data Pubblicazione:</strong> ' + dataPubblicazione + '</p><p><strong>Descrizione:</strong> ' + testo + '</p><img src="' + foto + '" width="100%" height="100%">');
        }
    })
    $("#ui-dialog-title-dialog").hide();
    $(".ui-dialog-titlebar").removeClass('ui-widget-header');;
}

//Funzione che controlla se l'utente ha selezionato un file per l'immagine del profilo
function checkAndSubmit() {
    var file = document.getElementById("profilePicture").files[0];

    if (file == undefined) {
        alert("Nessun file selezionato");
    } else {
        document.getElementById("profilePictureForm").submit();
    }
}




document.addEventListener("DOMContentLoaded", function () {
  var selectNazione = document.getElementById("selectNazione");
  var selectProvincia = document.getElementById("selectProvincia");
  var selectCitta = document.getElementById("selectCitta");

  // Funzione per caricare dinamicamente le nazioni
  function loadNazioni() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "../backend/load_nazioni.php", true);

    xhr.onload = function () {
      if (xhr.status == 200) {
        selectNazione.innerHTML = xhr.responseText;
      }
    };

    xhr.send();
  }

  // Carica le nazioni al caricamento della pagina
  loadNazioni();

  // Gestisci il cambio nella selezione della nazione
  selectNazione.addEventListener("change", function () {
    var selectedNazione = selectNazione.value;
    selectProvincia.disabled = true;
    selectCitta.disabled = true;

    if (selectedNazione !== "") {
      var xhr = new XMLHttpRequest();
      xhr.open("GET", "../backend/load_province.php?nazione=" + selectedNazione, true);

      xhr.onload = function () {
        if (xhr.status == 200) {
          selectProvincia.innerHTML = xhr.responseText;
          selectProvincia.disabled = false;
        }
      };

      xhr.send();
    }
  });

  // Gestisci il cambio nella selezione della provincia
  selectProvincia.addEventListener("change", function () {
    var selectedProvincia = selectProvincia.value;
    selectCitta.disabled = true;

    if (selectedProvincia !== "") {
      var xhr = new XMLHttpRequest();
      xhr.open("GET", "../backend/load_citta.php?provincia=" + selectedProvincia, true);

      xhr.onload = function () {
        if (xhr.status == 200) {
          selectCitta.innerHTML = xhr.responseText;
          selectCitta.disabled = false;
        }
      };

      xhr.send();
    }
  });

  // Event listener per il submit del form
  document.getElementById("registrationForm").addEventListener("submit", function (event) {
    // Verifica se la residenza è selezionata
    var residenceCountry = document.getElementById("selectNazione").value;
    var residenceProvince = document.getElementById("selectProvincia").value;
    var residenceCity = document.getElementById("selectCitta").value;

    // Verifica se il luogo di nascita è selezionato
    var birthCountry = document.getElementById("selectNazioneNascita").value;
    var birthProvince = document.getElementById("selectProvinciaNascita").value;
    var birthCity = document.getElementById("selectCittaNascita").value;

    var residenceWarning = document.getElementById("residenceWarning");
    var birthplaceWarning = document.getElementById("birthplaceWarning");

    // Logica warning per il luogo di residenza
    if ((residenceCountry && residenceProvince && residenceCity) || (!residenceCountry && !residenceProvince && !residenceCity)) {
      residenceWarning.style.display = "none";
    } else {
      residenceWarning.style.display = "block";
      residenceWarning.innerHTML = "Si prega di fornire tutte le informazioni sulla residenza (o nessuna).";
      event.preventDefault(); // Impedisce l'invio del form
    }

    // Logica warning per il luogo di nascita
    if ((birthCountry && birthProvince && birthCity) || (!birthCountry && !birthProvince && !birthCity)) {
      birthplaceWarning.style.display = "none";
    } else {
      birthplaceWarning.style.display = "block";
      birthplaceWarning.innerHTML = "Si prega di fornire tutte le informazioni sul luogo di nascita (o nessuna).";
      event.preventDefault(); // Impedisce l'invio del form
    }
  });

});

document.addEventListener("DOMContentLoaded", function () {
  var selectNazioneNascita = document.getElementById("selectNazioneNascita");
  var selectProvinciaNascita = document.getElementById("selectProvinciaNascita");
  var selectCittaNascita = document.getElementById("selectCittaNascita");

  // Funzione per caricare dinamicamente le nazioni di nascita
  function loadNazioniNascita() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "../backend/load_nazioni.php", true);

    xhr.onload = function () {
      if (xhr.status == 200) {
        selectNazioneNascita.innerHTML = xhr.responseText;
      }
    };

    xhr.send();
  }

  // Carica le nazioni di nascita al caricamento della pagina
  loadNazioniNascita();

  // Gestisci il cambio nella selezione della nazione di nascita
  selectNazioneNascita.addEventListener("change", function () {
    var selectedNazioneNascita = selectNazioneNascita.value;
    selectProvinciaNascita.disabled = true;
    selectCittaNascita.disabled = true;

    if (selectedNazioneNascita !== "") {
      var xhr = new XMLHttpRequest();
      xhr.open("GET", "../backend/load_province.php?nazione=" + selectedNazioneNascita, true);

      xhr.onload = function () {
        if (xhr.status == 200) {
          selectProvinciaNascita.innerHTML = xhr.responseText;
          selectProvinciaNascita.disabled = false;
        }
      };

      xhr.send();
    }
  });

  // Gestisci il cambio nella selezione della provincia di nascita
  selectProvinciaNascita.addEventListener("change", function () {
    var selectedProvinciaNascita = selectProvinciaNascita.value;
    selectCittaNascita.disabled = true;

    if (selectedProvinciaNascita !== "") {
      var xhr = new XMLHttpRequest();
      xhr.open("GET", "../backend/load_citta.php?provincia=" + selectedProvinciaNascita, true);

      xhr.onload = function () {
        if (xhr.status == 200) {
          selectCittaNascita.innerHTML = xhr.responseText;
          selectCittaNascita.disabled = false;
        }
      };

      xhr.send();
    }
  });


});

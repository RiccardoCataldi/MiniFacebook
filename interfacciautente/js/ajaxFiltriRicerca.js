
document.addEventListener("DOMContentLoaded", function () {
    var filtroSelect = document.getElementById("filtro");
    var parametroInput = document.getElementById("inputSearch");
    var paramContainer = document.getElementById("paramContainer");

    // Creazione di una tendina per le opzioni
    var parametroSelect = document.createElement("select");
    parametroSelect.className = "form-control mr-2";

    // Gestione dell'evento change sulla tendina di filtro
    filtroSelect.addEventListener("change", function () {
        var selectedFilter = filtroSelect.value;

        // Pulisci il contenuto del campo di input e del contenitore della tendina
        parametroInput.value = "";
        paramContainer.innerHTML = "";

        if (selectedFilter === "") {
            // Nascondi la tendina e reimposta il campo di input
            paramContainer.style.display = "none";
            parametroInput.style.display = "block";
        } else {
            // Creazione della richiesta XMLHttpRequest
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    if (selectedFilter === "sesso" || selectedFilter === "hobby" || selectedFilter === "citta" || selectedFilter === "eta") {
                        console.log(xhr.responseText);
                        var options = JSON.parse(xhr.responseText);
                        parametroSelect.innerHTML = ""; // Pulisci la tendina

                        // placeholder message
                        var placeholderOption = document.createElement("option");
                        placeholderOption.value = "";
                        placeholderOption.text = "Seleziona filtro";
                        parametroSelect.add(placeholderOption);

                        for (var i = 0; i < options.length; i++) {
                            var option = document.createElement("option");
                            option.value = options[i];
                            option.text = options[i];
                            parametroSelect.add(option);
                        }
                        // Nascondi il campo di input e aggiungi la tendina al suo posto
                        parametroInput.style.display = "none";
                        paramContainer.style.display = "block";
                        paramContainer.appendChild(parametroSelect);
                    }
                }
            };

            // Configurazione della richiesta
            xhr.open("GET", "../backend/get_filteredSearch_dropdown_data.php?filter=" + selectedFilter, true);

            // Invio della richiesta
            xhr.send();
        }
    });

    // Aggiunta: Aggiornamento del campo di input quando si seleziona un valore dalla tendina
    parametroSelect.addEventListener("change", function () {
        var selectedValue = parametroSelect.value;
        parametroInput.value = selectedValue;
    });
});
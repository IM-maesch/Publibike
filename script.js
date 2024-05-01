// Formulardaten abrufen
const formList = document.querySelectorAll('form'); // Alle Formulare auf der Seite in der Variable formList speichern

formList.forEach(form => {
  form.addEventListener('submit', function(event) {
    event.preventDefault(); 

    const formData = new FormData();
    // Iteration durch alle Elemente im Formular
    Array.from(form.elements).forEach(element => { // Array.from() wandelt ein Array-ähnliches Objekt in ein echtes Array um (hier: eine NodeList). 
      // Ignorieren von Elementen ohne 'name'-Attribut
      if (element.name) {
          formData.append(element.name, element.value);
      }
    });

    // Senden der Daten an den Server
    send('api.php', formData)
      .then(antwortDaten => console.log(antwortDaten))
      .catch(fehler => console.error(fehler));
      
  });
});


// Funktion zum Senden von Daten an den Server
async function send(url, formData) {
    try {
        const antwort = await fetch(url, {
            method: 'POST', // Methode: POST
            body: formData, // Hier verwenden wir FormData direkt
            // Keine 'Content-Type' Header notwendig; der Browser setzt einen passenden Wert
        });

        if (!antwort.ok) {
            throw new Error('Netzwerkantwort war nicht ok.');
        }

        const antwortDaten = await antwort.json(); // Antwort in JSON umwandeln
        return antwortDaten; // Antwort zurückgeben
    } catch (fehler) {
        console.error('Fehler beim Senden der Daten: ', fehler);
    }
}



// Abrufen aller daten durch den einfachen Aufruf von api.php
fetch('api.php').
  then(response => response.json()).
  then(data => console.log(data)).
  catch(error => console.error(error));

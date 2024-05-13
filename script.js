let blub = document.querySelector('#blub');

///////////////
// Don't touch it //
///////////////
async function holeDaten(url) {
    try {
        let response = await fetch(url);
        let data = await response.json();
        return data;
    }
    catch (error) {
        console.log(error);
    }
}
///////////////
// Don't touch it //
///////////////


// dom loaded async
document.addEventListener('DOMContentLoaded', async () => {
  let url = 'https://727502-4.web.fhgr.ch/ETL/04_unload.php'
  let data = await holeDaten(url);
  console.log(data);
  console.log(data[105])
  console.log(data[105][0])
  console.log(data[105][0].timestamp)
  console.log(data[105][0].standortaktivitaet)

  data[217].forEach (element => {
    
    blub.innerHTML += element.timestamp + ' - ' + element.standortaktivitaet + '<br>'

  })
})
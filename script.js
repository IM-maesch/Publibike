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



const ctx = document.getElementById('myChart');

  new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Fribourg', 'Bern', 'Zurich', 'Chur'],
      datasets: [{
        label: '# of Votes',
        data: [52, 19, 3, 5],
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });





  /* JS von Nick*/

async function main() {
    let data = await fetchData();
    console.log(data);

    let date = data.data.date;
    let temp_chur = data.data.chur;
    let temp_bern = data.data.bern;

    const ctx = document.getElementById('temperatureChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: date,
            datasets: [{
                label: 'Temperature in Chur',
                data: temp_chur,
                backgroundColor: 'red',
                borderColor: 'red',
                borderWidth: 1
            },
            {
                label: 'Temperature in Bern',
                data: temp_bern,
                backgroundColor: 'blue',
                borderColor: 'blue',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: 'day',
                        displayFormats: {
                            day: 'DD.MM HH:M'
                        }
                    },
                    ticks: {
                        source: 'labels',
                        maxTicksLimit: 10,
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Temperature (°C)'
                    }
                }
            }
        }
    });

}

main();


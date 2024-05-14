let blub = document.querySelector('#blub');

// --------------------------
// Don't touch this
// Function to fetch data from the URL
// --------------------------
async function holeDaten(url) {
    try {
        let response = await fetch(url);
        let data = await response.json();
        return data;
    } catch (error) {
        console.log(error);
    }
}
// --------------------------
// Don't touch this
// --------------------------

// DOMContentLoaded event listener
document.addEventListener('DOMContentLoaded', async () => {
    // URL to fetch data from
    let url = 'https://727502-4.web.fhgr.ch/ETL/04_unload.php';

    // Fetch data from the URL
    let data = await holeDaten(url);

    // Log the fetched data for debugging
    console.log(data);

    // Initialize arrays to hold labels and datasets
    let labels = [];
    let datasets = [];

    // Loop through each entry in the data
    // Loop through each entry in the data
Object.entries(data).forEach(([standort_id, entries]) => {
  // Sort entries by timestamp
  entries.sort((a, b) => new Date(a.timestamp) - new Date(b.timestamp));

  // Initialize an array to hold standortaktivitaet values for the current standort_id
  let standortaktivitaetValues = [];

  // Push labels (timestamps) and standortaktivitaet values to respective arrays
  entries.forEach(entry => {
      labels.push(entry.timestamp);
      standortaktivitaetValues.push(entry.standortaktivitaet);
  });

  // Create a dataset object for the current standort_id
  datasets.push({
      label: `Standort ${standort_id}`, // Label for the dataset
      data: standortaktivitaetValues, // Standortaktivitaet values
      borderColor: `rgb(${Math.random() * 255}, ${Math.random() * 255}, ${Math.random() * 255})`, // Random color for each line
      fill: false // Do not fill area under the line
  });
});


    // Get the canvas element by its id
    const ctx = document.getElementById('standortaktivitaet');

    // Create the chart
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels, // Timestamps for x-axis
            datasets: datasets // Array of dataset objects
        },
        options: {
          elements: {
              point: {
                  
              }
          },
            scales: {
              x: {
                categoryPercentage: 0.8
              },
              y: {
                  beginAtZero: true
              }
            }
        }
    });
});


  





  /* JS von Nick

async function main() {
    let data = await fetchData();
    console.log(data);
    console.log("moin");

    let date = data.data.date;
    let temp_chur = data.data.chur;
    let temp_bern = data.data.bern;

    const ctx = document.getElementById('standortaktivitaet').getContext('2d');
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

main();*/


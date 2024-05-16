let blub = document.querySelector('#blub'); //Beni-Hommage

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

const ctx = document.getElementById('standortaktivitaet');

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

  // Define colors for each group
  const groupColors = {
      'Fribourg': '#73D9EF',
      'Bern': '#946AEE',
      'Zürich': '#66D793',
      'Chur': '#CED766'
  };

  // Loop through each group in the data
  Object.entries(data).forEach(([groupName, groupData]) => {
      // Extract timestamps and standortaktivitaet from the groupData
      let timestamps = groupData.timestamps;
      let standortaktivitaet = groupData.standortaktivitaet;

      // Create a dataset object for the current group
      datasets.push({
          label: groupName, // Label for the dataset
          data: standortaktivitaet, // Standortaktivitaet values
          borderColor: groupColors[groupName], // Assign color based on group name
          fill: false // Do not fill area under the line
      });

      // Add timestamps to the labels array
      labels = timestamps;
  });

    // Create the chart
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels, // Timestamps for x-axis
            datasets: datasets // Array of dataset objects
        },
        options: {
            scales: {
              x: {
                type: 'time', // Use time scale for x-axis
                time: {
                  unit: 'day', // Display timestamps by day
                    tooltipFormat: 'DD.MM.YYYY, HH:mm', // Format for tooltips (optional)
                    displayFormats: {
                      day: 'DD.MM.YYYY' // Format for the axis labels
                  }
                },
                ticks: {
                    color: '#CED766' // Color of the x-axis labels
                },
                beginAtZero: true
            },
              y: {
                ticks: {
                    color: '#CED766' // Color of the y-axis labels
                },
                  beginAtZero: true,              
                }
              },
            plugins: {
                legend: {
                    labels: {
                        color: '#CED766' // Color of the legend labels
                    }
                }
            }
        }
    });
});

// Function to handle zooming
function handleZoom(event) {
    // Access the chart instance
    let chart = Chart.getChart(ctx);

    // Determine the direction of the zoom based on the event's deltaY
    let zoomDirection = event.deltaY > 0 ? -1 : 1;

    // Calculate the zoom factor (you can adjust this value based on your preference)
    let zoomFactor = 0.1; // Example: Zoom by 10% for each scroll

    // Calculate the new minimum and maximum values for the x-axis
    let xAxis = chart.scales['x'];
    let newMin = xAxis.min + zoomDirection * (xAxis.max - xAxis.min) * zoomFactor;
    let newMax = xAxis.max - zoomDirection * (xAxis.max - xAxis.min) * zoomFactor;

    // Update the x-axis scale with the new minimum and maximum values
    xAxis.options.min = newMin;
    xAxis.options.max = newMax;

    // Update the chart
    chart.update();

    // Prevent the default scroll behavior (e.g., page scrolling)
    event.preventDefault();
}

// Add event listener for mousewheel event (for zooming)
ctx.addEventListener('wheel', handleZoom);
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
            scales: {
              x: {
                type: 'time', // Use time scale for x-axis
                time: {
                  unit: 'day', // Display timestamps by day
                    tooltipFormat: 'll', // Format for tooltips (optional)
                    displayFormats: {
                      day: 'YYYY-MM-DD' // Format for the axis labels
                  }
                },
                beginAtZero: true
            },
              y: {
                  beginAtZero: true
              }
            }
        }
    });
});

// Function to handle zooming
function handleZoom(event) {
    // Check if the shift key is pressed (or any other condition to trigger zoom)
    if (event.shiftKey) {
        // Access the chart instance
        let chart = Chart.getChart(ctx); // ctx is the canvas context defined earlier

        // Implement zoom logic here
        // For example, you could adjust the chart's options
        // For simplicity, let's just increase the borderWidth of the first dataset
        chart.data.datasets[0].borderWidth += 1;

        // Update the chart
        chart.update();
    }
}


// Add event listener for mousewheel event (for zooming)
document.getElementById('myChart').addEventListener('wheel', handleZoom);

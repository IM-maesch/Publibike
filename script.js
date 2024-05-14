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


// Define colors for each standort_id
const standortColors = {
  105: '#73D9EF', // Fribourg
  217: '#946AEE', // Bern
  233: '#73D9EF', // Fribourg (same color as 105)
  353: '#946AEE', // Bern (same color as 217)
  506: '#73D9EF', // Fribourg (same color as 105)
  513: '#66D793', // Zürich
  872: '#CED766', // Chur
  873: '#CED766'  // Chur (same color as 872)
};


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

  // Loop through each group in the data
  Object.entries(data).forEach(([groupName, groupData]) => {
      // Extract timestamps and standortaktivitaet from the groupData
      let timestamps = groupData.timestamps;
      let standortaktivitaet = groupData.standortaktivitaet;

      // Create a dataset object for the current group
      datasets.push({
          label: groupName, // Label for the dataset
          data: standortaktivitaet, // Standortaktivitaet values
          borderColor: `rgb(${Math.random() * 255}, ${Math.random() * 255}, ${Math.random() * 255})`, // Random color for each line
          fill: false // Do not fill area under the line
      });

      // Add timestamps to the labels array
      labels = timestamps;
  });
  

// // DOMContentLoaded event listener
// document.addEventListener('DOMContentLoaded', async () => {
//     // URL to fetch data from
//     let url = 'https://727502-4.web.fhgr.ch/ETL/04_unload.php';

//     // Fetch data from the URL
//     let data = await holeDaten(url);

//     // Log the fetched data for debugging
//     console.log(data);

//     // Initialize arrays to hold labels and datasets
//     let labels = [];
//     let datasets = [];

//     // Loop through each entry in the data
//     // Loop through each entry in the data

// Object.entries(data).forEach(([standort_id, entries]) => {
//   // Sort entries by timestamp
//   entries.sort((a, b) => new Date(a.timestamp) - new Date(b.timestamp));

//   // Initialize an array to hold standortaktivitaet values for the current standort_id
//   let standortaktivitaetValues = [];

//   // Push labels (timestamps) and standortaktivitaet values to respective arrays
//   entries.forEach(entry => {
//       labels.push(entry.timestamp);
//       standortaktivitaetValues.push(entry.standortaktivitaet);
//   });

//   // Create a dataset object for the current standort_id
//   datasets.push({
//       label: `Standort ${standort_id}`, // Label for the dataset
//       data: standortaktivitaetValues, // Standortaktivitaet values
//       borderColor: standortColors[standort_id], // Assign color based on standort_id
//       fill: false // Do not fill area under the line
//   });
// });


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




const myChart = new Chart(ctx, { ... });


import Chart from 'chart.js/auto';


const ctx = document.getElementById('myChart');

  new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Fribourg', 'Bern', 'Zurich', 'Chur'],
      datasets: [{
        label: '# of Votes',
        data: [12, 19, 3, 5],
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
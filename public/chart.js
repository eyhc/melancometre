window.chartColors = {
	red: 'rgb(255, 99, 132)',
	orange: 'rgb(255, 159, 64)',
	yellow: 'rgb(255, 205, 86)',
	green: 'rgb(75, 192, 192)',
	blue: 'rgb(54, 162, 235)',
	purple: 'rgb(153, 102, 255)',
	grey: 'rgb(201, 203, 207)'
};

var xhr;
function update_chart() {
    var form = new FormData();
    if (xhr != undefined && xhr != null) { xhr.abort(); }

    xhr = new XMLHttpRequest();
    xhr.open('POST', 'call.php');

    form.append('action', 'get_data');
    xhr.send(form);

    xhr.addEventListener('readystatechange', function() {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            var obj = JSON.parse(xhr.responseText);
            change_data_chart(obj.data_labels, obj.labels, obj.general, obj.moral, obj.energy, obj.suicidal_ideas);
        }
    });
}


// Graphs
var ctx = document.getElementById('myChart')
// eslint-disable-next-line no-unused-vars
var myChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: [],
    datasets: [
      {
          data: [],
          label: "General",
          backgroundColor: 'transparent',
          borderColor: window.chartColors.green,
          pointBackgroundColor: window.chartColors.green,
          //borderWidth: 4,
          fill: false
      },
      {
          data: [],
          label: "Moral",
          backgroundColor: 'transparent',
          borderColor: window.chartColors.purple,
          pointBackgroundColor: window.chartColors.purple,
          //borderWidth: 4,
          fill: false
      },
      {
          data: [],
          label: "Energy",
          backgroundColor: 'transparent',
          borderColor: window.chartColors.red,
          pointBackgroundColor: window.chartColors.red,
          //borderWidth: 4,
          fill: false
      },
      {
          data: [],
          label: "Suicidal ideas",
          backgroundColor: 'transparent',
          borderColor: window.chartColors.orange,
          pointBackgroundColor: window.chartColors.orange,
          //borderWidth: 4,
          fill: false
      }
  ]
  },
  options: {
    scales: {
      yAxes: [{
        ticks: {
          beginAtZero: true
        }
      }]
    },
    legend: {
      display: true
    }
  }
})


function change_data_chart(dl, l, g, m, e, si) {
    myChart.data.labels = l;
    myChart.data.datasets[0].label = dl.g;
    myChart.data.datasets[1].label = dl.m;
    myChart.data.datasets[2].label = dl.e;
    myChart.data.datasets[3].label = dl.si;
    myChart.data.datasets[0].data = g;
    myChart.data.datasets[1].data = m;
    myChart.data.datasets[2].data = e;
    myChart.data.datasets[3].data = si;
    myChart.update();
};

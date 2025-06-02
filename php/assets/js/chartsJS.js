const ctx1 = document.getElementById('chart-1');

 new Chart(ctx1, {
    type: 'polarArea',
    data: {
      labels: ['Chocolate Tiramisu', 'Cinnamon & Honey Tea', 'Red Velvet Milkshake', 'Americano', 'Mint Cookies & Cream Milkshake', 'Smores'],
      datasets: [{
        label: '# of Orders',
        data: [15, 20, 5, 17, 50, 25]
      }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        resizeDelay: 0,
        onResize: function(chart,size)
        {
            chart.update();
        }
    }
  });


  const ctx2 = document.getElementById('chart-2');

  new Chart(ctx2, {
    type: 'bar',
    data: {
      labels: ['Chocolate Tiramisu', 'Cinnamon & Honey Tea', 'Red Velvet Milkshake', 'Americano', 'Mint Cookies & Cream Milkshake', 'Smores'],
      datasets: [{
        label: 'Earnings',
        data: [440, 240, 360, 240, 320, 300],
        backgroundColor: [
            '#543310','#543310','#543310','#543310','#543310','#543310'
        ]
      }]
    },
    options: {
        responsive: true,
        maintainAspectRatio:false,
        resizeDelay: 0,
        onResize: function(chart,size)
        {
            chart.update();
        }
    }
  });
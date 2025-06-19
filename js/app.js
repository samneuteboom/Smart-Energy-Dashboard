let widgetId = 0;

function addWidget(type) {
  const dashboard = document.getElementById('dashboard');

  const container = document.createElement('div');
  container.className = 'widget';
  container.innerHTML = `
    <canvas id="chart-${widgetId}" width="400" height="200"></canvas>
    <button class="remove-btn" onclick="removeWidget(this)">Verwijder</button>
  `;
  dashboard.appendChild(container);

  const ctx = document.getElementById(`chart-${widgetId}`).getContext('2d');

  const data = {
    labels: ['Ma', 'Di', 'Wo', 'Do', 'Vr'],
    datasets: [{
      label: 'Energieverbruik (kWh)',
      data: [12, 19, 3, 5, 2],
      backgroundColor: 'rgba(75, 192, 192, 0.5)',
      borderColor: 'rgba(75, 192, 192, 1)',
      borderWidth: 1
    }]
  };

  const config = {
    type: type,
    data: data,
    options: {
      responsive: true,
      scales: {
        y: { beginAtZero: true }
      }
    }
  };

  new Chart(ctx, config);
  widgetId++;
}

function removeWidget(button) {
  const widget = button.parentElement;
  widget.remove();
}

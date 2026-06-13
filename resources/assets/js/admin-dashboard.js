'use strict';

document.addEventListener('DOMContentLoaded', function () {
  const data = window.dashboardData || {};
  const shared = {
    chart: { toolbar: { show: false }, fontFamily: 'Public Sans' },
    dataLabels: { enabled: false },
    grid: { borderColor: '#eef0f5', strokeDashArray: 5 },
    tooltip: { theme: 'dark' }
  };
  const traffic = document.querySelector('#trafficChart');
  if (traffic) new ApexCharts(traffic, {
    ...shared, chart: { ...shared.chart, type: 'area', height: 310 },
    series: [{ name: 'Views', data: data.values || [] }], colors: ['#5b5bd6'],
    stroke: { curve: 'smooth', width: 3 },
    fill: { type: 'gradient', gradient: { opacityFrom: .32, opacityTo: .02, stops: [0, 90, 100] } },
    xaxis: { categories: data.labels || [], labels: { style: { colors: '#8b91a0' } }, axisBorder: { show: false }, axisTicks: { show: false } },
    yaxis: { labels: { style: { colors: '#8b91a0' } } }
  }).render();
  const sources = document.querySelector('#sourceChart');
  if (sources) new ApexCharts(sources, {
    ...shared, chart: { ...shared.chart, type: 'donut', height: 310 },
    series: data.sourceValues || [], labels: data.sourceLabels || [],
    colors: ['#5b5bd6', '#8b8bf2', '#21b6a8', '#f3b64b', '#ee7183'], stroke: { width: 0 },
    legend: { position: 'bottom', fontSize: '12px' },
    plotOptions: { pie: { donut: { size: '72%', labels: { show: true, total: { show: true, label: 'Visits' } } } } }
  }).render();
});

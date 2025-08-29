<template>
  <section class="dashboard">
    <h1 class="dashboard__title">Dashboard</h1>

    <div class="cards">
      <div class="card" v-for="(count, status) in perStatus" :key="status">
        <div class="card__title">{{ status }}</div>
        <div class="card__value">{{ count }}</div>
      </div>
    </div>

    <div class="chart">
      <canvas id="categoryChart"></canvas>
    </div>
  </section>
</template>

<script>
import { StatsAPI } from '../api';
import { Chart, ArcElement, Tooltip, Legend, BarController, BarElement, CategoryScale, LinearScale } from 'chart.js';
Chart.register(ArcElement, Tooltip, Legend, BarController, BarElement, CategoryScale, LinearScale);

export default {
  name: 'Dashboard',
  data() {
    return { perStatus: {}, perCategory: {}, chart: null };
  },
  async mounted() {
    const stats = await StatsAPI.get();
    this.perStatus = stats.perStatus || {};
    this.perCategory = stats.perCategory || {};

    const labels = Object.keys(this.perCategory);
    const data = Object.values(this.perCategory);

    const ctx = document.getElementById('categoryChart').getContext('2d');
    this.chart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels,
        datasets: [{ label: 'Tickets per Category', data }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: true } },
        scales: { y: { beginAtZero: true } }
      }
    });
  },
  beforeUnmount() {
    if (this.chart) this.chart.destroy();
  }
}
</script>
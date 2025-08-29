<template>
  <div :class="['app', theme]">
    <nav class="nav">
      <a class="nav__link" href="/tickets" @click.prevent="$router.push('/tickets')">Tickets</a>
      <a class="nav__link" href="/dashboard" @click.prevent="$router.push('/dashboard')">Dashboard</a>
<!--      <button class="nav__toggle" @click="toggleTheme">{{ // theme === 'dark' ? 'Light' : 'Dark' }} Mode</button>-->
    </nav>
    <main class="container">
      <router-view />
    </main>
  </div>
</template>

<script>
export default {
  name: 'App',
  data() {
    return { theme: localStorage.getItem('theme') || 'light' };
  },
  methods: {
    toggleTheme() {
      this.theme = this.theme === 'dark' ? 'light' : 'dark';
      localStorage.setItem('theme', this.theme);
    }
  }
}
</script>
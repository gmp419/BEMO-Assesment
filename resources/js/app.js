import './bootstrap';
import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import App from './App.vue';
import TicketsList from './pages/TicketsList.vue';
import TicketDetail from './pages/TicketDetail.vue';
import Dashboard from './pages/Dashboard.vue';
import './style.css';

const routes = [
    { path: '/', redirect: '/tickets' },
    { path: '/tickets', component: TicketsList },
    { path: '/tickets/:id', component: TicketDetail, props: true },
    { path: '/dashboard', component: Dashboard },
];

const router = createRouter({ history: createWebHistory(), routes });

createApp(App).use(router).mount('#app');
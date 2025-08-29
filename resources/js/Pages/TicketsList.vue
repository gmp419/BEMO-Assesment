<template>
  <section class="ticket-list">
    <header class="ticket-list__header">
      <h1 class="ticket-list__title">Tickets</h1>
      <div class="ticket-list__actions">
        <input class="ticket-list__search" v-model="filters.q" placeholder="Search subject/body" @input="fetchTickets(1)" />
        <select class="ticket-list__select" v-model="filters.status" @change="fetchTickets(1)">
          <option value="">All Statuses</option>
          <option>open</option>
          <option>pending</option>
          <option>closed</option>
        </select>
        <select class="ticket-list__select" v-model="filters.category" @change="fetchTickets(1)">
          <option value="">All Categories</option>
          <option>billing</option>
          <option>technical</option>
          <option>sales</option>
          <option>account</option>
          <option>shipping</option>
        </select>
        <button class="ticket-list__btn" @click="openNew = true">New Ticket</button>
        <button class="ticket-list__btn" @click="exportCSV">Export CSV</button>
      </div>
    </header>

    <div class="ticket-table">
      <div class="ticket-table__head">
        <div>Subject</div>
        <div>Status</div>
        <div>Category</div>
        <div>Confidence</div>
        <div>Note</div>
        <div>Actions</div>
      </div>
      <div v-for="t in tickets.data" :key="t.id" class="ticket-table__row" :class="{'ticket-table__row--noted': !!t.note}">
        <div>
          <a href="#" @click.prevent="$router.push(`/tickets/${t.id}`)">{{ t.subject }}</a>
        </div>
        <div><span class="status" :class="`status--${t.status}`">{{ t.status }}</span></div>
        <div>
          <select class="inline-select" v-model="t.category" @change="updateCategory(t)">
            <option :value="null">—</option>
            <option>billing</option>
            <option>technical</option>
            <option>sales</option>
            <option>account</option>
            <option>shipping</option>
          </select>
        </div>
        <div>{{ t.confidence?.toFixed?.(2) ?? '' }}</div>
        <div>
          <span v-if="t.note" class="badge" :title="t.note">📝</span>
        </div>
        <div>
          <button class="ticket-list__btn" @click="classify(t)" :disabled="loadingId===t.id">
            <span v-if="loadingId===t.id" class="spinner"></span>
            Classify
          </button>
        </div>
      </div>
    </div>

    <footer class="ticket-list__footer">
      <button class="ticket-list__btn" :disabled="page<=1" @click="fetchTickets(page-1)">Prev</button>
      <span>Page {{ page }} of {{ tickets.last_page || 1 }}</span>
      <button class="ticket-list__btn" :disabled="page>=tickets.last_page" @click="fetchTickets(page+1)">Next</button>
    </footer>

    <!-- New Ticket Modal -->
    <div v-if="openNew" class="modal">
      <div class="modal__content">
        <h3>New Ticket</h3>
        <label>Subject
          <input v-model="form.subject" />
        </label>
        <label>Body
          <textarea v-model="form.body" rows="5"></textarea>
        </label>
        <div class="modal__actions">
          <button class="ticket-list__btn" @click="createTicket">Create</button>
          <button class="ticket-list__btn" @click="openNew=false">Cancel</button>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { TicketsAPI } from '../api';

export default {
  name: 'TicketsList',
  data() {
    return {
      tickets: { data: [], last_page: 1 },
      page: 1,
      filters: { q: '', status: '', category: '', per_page: 10 },
      openNew: false,
      form: { subject: '', body: '' },
      loadingId: null,
    };
  },
  created() { this.fetchTickets(1); },
  methods: {
    async fetchTickets(p) {
      this.page = p;
      const res = await TicketsAPI.list({ ...this.filters, page: p });
      this.tickets = res;
    },
    async createTicket() {
      await TicketsAPI.create({ ...this.form });
      this.openNew = false;
      this.form = { subject: '', body: '' };
      await this.fetchTickets(1);
    },
    async classify(t) {
      this.loadingId = t.id;
      try {
        await TicketsAPI.classify(t.id);
        setTimeout(() => this.fetchTickets(this.page), 600);
      } finally {
        this.loadingId = null;
      }
    },
    async updateCategory(t) {
      await TicketsAPI.patch(t.id, { category: t.category });
    },
    exportCSV() {
      const rows = [
        ['id','subject','status','category','confidence'],
        ...this.tickets.data.map(t => [t.id, t.subject, t.status, t.category ?? '', t.confidence ?? ''])
      ];
      const csv = rows.map(r => r.map(v => '"' + String(v).replaceAll('"','""') + '"').join(',')).join('\n');
      const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url; a.download = 'tickets.csv'; a.click();
      URL.revokeObjectURL(url);
    }
  }
}
</script>



<template>
  <section class="ticket-detail" v-if="ticket">
    <header class="ticket-detail__header">
      <h2 class="ticket-detail__title">{{ ticket.subject }}</h2>
      <div>
        <select v-model="ticket.status" @change="save({ status: ticket.status })">
          <option>open</option>
          <option>pending</option>
          <option>closed</option>
        </select>
      </div>
    </header>

    <article class="ticket-detail__body">{{ ticket.body }}</article>

    <div class="ticket-detail__row">
      <label>Category
        <select v-model="ticket.category" @change="save({ category: ticket.category })">
          <option :value="null">—</option>
          <option>billing</option>
          <option>technical</option>
          <option>sales</option>
          <option>account</option>
          <option>shipping</option>
        </select>
      </label>
    </div>

    <div class="ticket-detail__row">
      <label>Internal Note
        <textarea rows="4" v-model="ticket.note" @blur="save({ note: ticket.note })"></textarea>
      </label>
    </div>

    <div class="ticket-detail__row">
      <div class="ticket-detail__meta">
        <div><strong>Confidence:</strong> {{ ticket.confidence?.toFixed?.(2) ?? '—' }}</div>
        <div><strong>Explanation:</strong> {{ ticket.explanation ?? '—' }}</div>
      </div>
      <button class="ticket-list__btn" @click="runClassify" :disabled="loading">
        <span v-if="loading" class="spinner"></span>
        Run Classification
      </button>
    </div>
  </section>
</template>

<script>
import { TicketsAPI } from '../api';

export default {
  name: 'TicketDetail',
  props: ['id'],
  data() {
    return { ticket: null, loading: false };
  },
  created() { this.load(); },
  methods: {
    async load() {
      const id = this.$route.params.id;
      this.ticket = await TicketsAPI.get(id);
    },
    async save(payload) {
      const id = this.$route.params.id;
      this.ticket = await TicketsAPI.patch(id, payload);
    },
    async runClassify() {
      this.loading = true;      try {
        const id = this.$route.params.id;
        await TicketsAPI.classify(id);
        setTimeout(this.load, 700);
      } finally {

        this.loading = false;
      }
    }
  }
}
</script>
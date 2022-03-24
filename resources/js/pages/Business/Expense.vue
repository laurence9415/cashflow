<template>
  <div>
    <div class="mb-4">
      <form @submit.prevent="handleSaveBusinessExpense">
        <label>Expense</label><br />
        <v-radio-group v-model="form.status" row>
          <v-radio label="In" value="In"></v-radio>
          <v-radio label="Out" value="Out"></v-radio>
        </v-radio-group>
        <v-text-field label="Amount" v-model="form.amount"></v-text-field>
        <v-textarea
          solo
          name="input-7-4"
          label="Description"
          v-model="form.description"
        ></v-textarea>
        <v-btn type="submit" elevation="2">Save</v-btn>
      </form>
    </div>
    <div>
      <v-data-table
        :headers="headers"
        :items="expenses"
        :items-per-page="5"
        class="elevation-1"
        :loading="loading"
        loading-text="Loading Data... Please wait"
      >
      </v-data-table>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    businessId: {},
  },
  data: () => ({
    expenses: [],
    headers: [
      { text: "Description", value: "description" },
      { text: "Status", value: "status" },
      { text: "Amount", value: "amount" },
      { text: "Created At", value: "created_at" },
    ],
    form: {},
    errors: {},
    loading: false,
    filter: {
      date: "today",
    },
  }),
  mounted() {
    console.log(this.businessId);
    this.getBusinessExpenses();
  },
  methods: {
    getBusinessExpenses() {
      this.loading = true;
      this.$axios
        .get(`/api/businesses/${this.businessId}/expenses`, {
          params: this.filter,
        })
        .then((response) => (this.expenses = response.data))
        .finally(() => (this.loading = false));
    },
    handleSaveBusinessExpense() {
      this.loading = true;
      this.$axios
        .post(`/api/businesses/${this.businessId}/expenses`, this.form)
        .then((response) => {
          this.form = {};
        })
        .catch((error) => {
          if (error.response.status === 422) {
            this.errors = error.response.data.errors;
          }
        })
        .finally(() => (this.loading = false));
    },
  },
};
</script>

<style>
</style>
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
        <template v-slot:item.is_paid="{ item }">
          <v-icon large color="green darken-2" v-if="item.is_paid">
            mdi-thumb-up
          </v-icon>
          <v-icon large color="red darken-2" v-else> mdi-thumb-down </v-icon>
        </template>

        <template v-slot:item.date="{ item }">
          {{ moment(item.date).format("LL") }}
        </template>

        <template v-slot:item.actions="{ item }">
          <v-btn
            @click="handleUpdateIsPaid(item)"
            v-if="item.is_paid"
            class="mx-2"
            fab
            dark
            x-small
            color="red"
          >
            <v-icon dark> mdi-thumb-down </v-icon>
          </v-btn>
          <v-btn
            @click="handleUpdateIsPaid(item)"
            v-else
            class="mx-2"
            fab
            dark
            x-small
            color="success"
          >
            <v-icon dark> mdi-thumb-up </v-icon>
          </v-btn>
        </template>
      </v-data-table>
    </div>
  </div>
</template>

<script>
var moment = require("moment");

export default {
  props: {
    businessId: {},
  },
  data: () => ({
    moment: moment,
    expenses: [],
    headers: [
      { text: "Description", value: "description" },
      { text: "Status", value: "status" },
      { text: "Amount", value: "amount" },
      { text: "Paid", value: "is_paid" },
      { text: "Date", value: "date" },
      { text: "Actions", value: "actions" },
    ],
    form: {},
    errors: {},
    loading: false,
    filter: {
      date: "All",
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
    handleUpdateIsPaid(item) {
      this.loading = true;
      this.$axios
        .put(
          `/api/businesses/${this.businessId}/expenses/${item.id}/update-is-paid`
        )
        .then((response) => this.getBusinessExpenses())
        .finally(() => (this.loading = false));
    },
  },
};
</script>

<style>
</style>
<template>
  <div>
    <v-card class="p-4 mb-4">
      <v-card-title class="text-h5">
        CASHFLOWS FOR TODAY | &nbsp
        <span class="text-red-500">{{ totalAmount }}</span></v-card-title
      >
      <v-card-text>
        <div class="text-lg">
          <div v-for="cashflow in cashflows" :key="cashflow.id">
            <label>{{ cashflow.description }}</label>
            <label>{{ cashflow.amount }}</label>
          </div>
        </div>
      </v-card-text>
    </v-card>
    <v-card class="p-4">
      <div class="grid grid-cols-3 gap-x-4">
        <div>
          <v-menu
            :close-on-content-click="false"
            :nudge-right="40"
            transition="scale-transition"
            offset-y
            min-width="auto"
          >
            <template v-slot:activator="{ on, attrs }">
              <v-text-field
                v-model="form.date"
                label="Picker without buttons"
                prepend-icon="mdi-calendar"
                readonly
                v-bind="attrs"
                v-on="on"
                :error-messages="errors['date'] ? errors['date'] : ''"
              ></v-text-field>
            </template>
            <v-date-picker
              v-model="form.date"
              @input="menu2 = false"
            ></v-date-picker>
          </v-menu>
        </div>
        <div>
          <v-btn @click="handleAddForm" elevation="2">Add Form</v-btn>
        </div>
      </div>
      <div v-for="(form, index) in form.forms" :key="index">
        <v-form>
          <v-container>
            <v-row>
              <v-col cols="12" md="4">
                <v-text-field
                  v-model="form.description"
                  :counter="10"
                  label="Description"
                  required
                  :error-messages="
                    errors[`forms.${index}.description`]
                      ? errors[`forms.${index}.description`]
                      : ''
                  "
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="4">
                <v-text-field
                  v-model="form.amount"
                  :counter="10"
                  label="Amount"
                  required
                  :error-messages="
                    errors[`forms.${index}.amount`]
                      ? errors[`forms.${index}.amount`]
                      : ''
                  "
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="4">
                <v-btn @click="handleRemoveForm(index)" elevation="2"
                  >Remove</v-btn
                >
              </v-col>
            </v-row>
          </v-container>
        </v-form>
      </div>
      <v-btn @click="handleStoreForms" elevation="2">Save</v-btn>
    </v-card>
  </div>
</template>

<script>
export default {
  data: () => ({
    active: "In",
    cashflows: [],
    forms: [],
    totalAmount: 0,
    errors: {},
    form: {
      forms: [],
    },
  }),
  mounted() {
    this.getCashflows();
  },
  methods: {
    getCashflows() {
      const params = {
        date: "today",
      };
      this.$axios.get(`/api/cashflows`, { params }).then((response) => {
        this.cashflows = response.data;
        if (this.cashflows.length > 0) {
          this.totalAmount = this.cashflows
            .map((cashflow) => parseFloat(cashflow.amount))
            .reduce(
              (previousValue, currentValue) => previousValue + currentValue
            );
        }
      });
    },
    handleAddForm() {
      this.form.forms.push({
        amount: "",
        description: "",
      });
    },
    handleRemoveForm(index) {
      this.form.forms = this.form.forms.filter((form, key) => key !== index);
    },
    handleStoreForms() {
      this.$axios
        .post(`/api/cashflows`, this.form)
        .then((response) => {
          this.getCashflows();
          this.form = {
            forms: [],
          };
          this.$emit("update");
        })
        .catch((error) => {
          if (error.response.status === 422)
            this.errors = error.response.data.errors;
        });
    },
  },
};
</script>

<style lang="scss">
.v-messages__message {
  color: red;
}
</style>
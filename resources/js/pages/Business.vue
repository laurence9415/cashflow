<template>
  <div>
    <div class="mb-4">
      <form @submit.prevent="handleSaveBusiness">
        <label>Business Name</label><br />
        <input type="text" v-model="form.name" placeholder="Business Name" />
        <v-btn type="submit" elevation="2">Save</v-btn>
      </form>
    </div>

    <div>
      <v-simple-table>
        <template v-slot:default>
          <thead>
            <tr>
              <th class="text-left">Name</th>
              <th class="text-left">Created At</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="business in businesses" :key="business.name">
              <td>{{ business.name }}</td>
              <td>{{ business.created_at }}</td>
              <td>
                <router-link :to="`/businesses/${business.id}/expenses`"
                  >Expenses</router-link
                >
              </td>
            </tr>
          </tbody>
        </template>
      </v-simple-table>
    </div>

    <router-view></router-view>
  </div>
</template>

<script>
export default {
  data: () => ({
    businesses: [],
    form: {},
    errors: {},
    loading: false,
  }),
  mounted() {
    this.getBusiness();
  },
  methods: {
    getBusiness() {
      this.$axios
        .get(`/api/businesses`)
        .then((response) => (this.businesses = response.data));
    },
    handleSaveBusiness() {
      this.loading = true;
      this.$axios
        .post(`/api/businesses`, this.form)
        .then((response) => {
          console.log(response.data);
        })
        .catch((error) => {
          if (error.response.status === 422)
            this.errors = error.response.data.error;
        })
        .finally(() => (this.loading = false));
    },
  },
};
</script>

<style>
</style>
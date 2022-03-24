<template>
  <v-app>
    <div class="bg-gray-700">
      <div class="flex justify-center text-black">
        <div class="app-width">
          <div class="flex justify-between items-center h-16">
            <label class="text-3xl text-gray-300">Cashflow</label>
            <div>
              <router-link
                v-for="link in links"
                :key="link.path"
                :to="link.path"
                class="m-4"
                :class="active === link.path ? 'text-xl' : ''"
                @click.native="handleActivePath(link)"
                >{{ link.title }}</router-link
              >
              <v-menu offset-y bottom left>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn dark icon v-bind="attrs" v-on="on">
                    <v-avatar color="red" size="36">
                      <span class="white--text text-h5">{{ initial }}</span>
                    </v-avatar>
                  </v-btn>
                </template>
                <v-list>
                  <v-list-item v-for="(item, index) in items" :key="index">
                    <v-list-item-title
                      class="cursor-pointer hover:bg-yellow-400 p-2"
                      @click="handleMenuClick(item)"
                      >{{ item.title }}</v-list-item-title
                    >
                  </v-list-item>
                </v-list>
              </v-menu>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="flex justify-center bg-gray-200">
      <div class="app-width py-4">
        <router-view @update="getData()"></router-view>
      </div>
    </div>
  </v-app>
</template>

<script>
export default {
  data: () => ({
    links: [
      {
        path: "/dashboard",
        title: "Dashboard",
      },
      {
        path: "/cashflow",
        title: "Cashflow",
      },
      {
        path: "/businesses",
        title: "Business",
      },
    ],
    active: "/dashboard",
    items: [{ title: "Logout" }],
    initial: "",
    summary: {},
    chartData: [],
    chartOptions: {
      chart: {
        title: "",
        subtitle: "",
      },
    },
  }),
  mounted() {
    this.active = this.$route.path;
    this.initial = this.$auth.name
      .split(" ")
      .map((word) => word[0])
      .filter((letter, key) => key <= 1)
      .join("");
    this.getData();
  },
  methods: {
    getData() {
      this.$axios.get(`/api/cashflows/summary`).then((response) => {
        this.summary = response.data;
        this.chartData = response.data.chart_data;
      });
    },
    handleActivePath(link) {
      this.active = link.path;
    },
    handleMenuClick(item) {
      if (item.title === "Logout") {
        this.$axios.post(`/api/logout`).then((reponse) => {
          window.location.href = "/login";
        });
      }
    },
  },
};
</script>

<style lang="scss">
.app-width {
  width: 1200px;
}
</style>
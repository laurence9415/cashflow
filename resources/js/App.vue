<template>
  <v-app>
    <div class="bg-yellow-400">
      <div class="flex justify-center text-black">
        <div class="app-width">
          <div class="flex justify-between items-center h-16">
            <label class="text-3xl">Cashflows ni Dexter</label>
            <div>
              <router-link
                v-for="link in links"
                :key="link.path"
                :to="link.path"
                class="m-4"
                :class="active === link.path ? 'text-white' : ''"
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

    <div class="flex justify-center">
      <div class="app-width">
        <router-view></router-view>
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
    ],
    active: "Dashboard",
    items: [{ title: "Logout" }],
    initial: "",
  }),
  mounted() {
    this.active = this.$route.path;
    this.initial = this.$auth.name
      .split(" ")
      .map((word) => word[0])
      .filter((letter, key) => key <= 1)
      .join("");
  },
  methods: {
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
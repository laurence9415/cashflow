<template>
  <div class="my-2">
    <v-row>
      <v-col>
        <v-card
          class="mx-auto p-4 flex justify-between text-white"
          color="#34a8eb"
          outlined
        >
          <v-card-title class="text-white"> Overall In </v-card-title>
          <!-- <span class="text-2xl"></span> -->
          <v-card-subtitle
            ><span class="text-white text-4xl">{{
              summary.overall_in
            }}</span></v-card-subtitle
          >
        </v-card>
      </v-col>
      <v-col>
        <v-card class="mx-auto p-4 flex" color="#e83a31" outlined>
          <v-card-title class="text-white"> Overall Out </v-card-title>
          <!-- <span class="text-2xl"></span> -->
          <v-card-subtitle
            ><span class="text-white text-4xl">{{
              summary.overall_out
            }}</span></v-card-subtitle
          >
        </v-card>
      </v-col>
    </v-row>
    <v-row>
      <v-col>
        <v-card :loading="loadingChart" class="h-80">
          <GChart
            v-if="!loadingChart"
            class="h-full"
            type="LineChart"
            :data="chartData"
            :options="chartOptions"
          />
        </v-card>
      </v-col>
    </v-row>
    <v-row class="fill-height">
      <v-col>
        <v-sheet height="64">
          <v-toolbar flat>
            <v-btn fab text small color="grey darken-2" @click="prev">
              <v-icon small> mdi-chevron-left </v-icon>
            </v-btn>
            <v-toolbar-title v-if="$refs.calendar">
              {{ $refs.calendar.title }}
            </v-toolbar-title>
            <v-btn fab text small color="grey darken-2" @click="next">
              <v-icon small> mdi-chevron-right </v-icon>
            </v-btn>

            <v-spacer></v-spacer>
            <v-menu bottom right>
              <template v-slot:activator="{ on }">
                <div>
                  <label class="mx-2"
                    >In:
                    <span
                      ><b>{{ totalNiSulodKwarta }}</b></span
                    ></label
                  >
                  |
                  <label class="mx-2"
                    >Out:
                    <span
                      ><b>{{ totalNiGawasKwarta }}</b></span
                    ></label
                  >
                </div>
              </template>
            </v-menu>
          </v-toolbar>
        </v-sheet>
        <v-sheet height="600">
          <v-calendar
            ref="calendar"
            v-model="focus"
            color="primary"
            :events="cashflows"
            :event-color="getEventColor"
            :type="type"
            @click:event="showEvent"
            @change="updateRange"
          ></v-calendar>
          <v-menu
            v-model="selectedOpen"
            :close-on-content-click="false"
            :activator="selectedElement"
            offset-x
          >
            <v-card
              color="grey lighten-4"
              min-width="350px"
              flat
              v-if="selectedEventData"
            >
              <v-toolbar :color="selectedEvent.color" dark>
                <v-toolbar-title
                  v-html="`Total ${selectedEvent.name}`"
                ></v-toolbar-title>
                <v-spacer></v-spacer>
              </v-toolbar>
              <v-card-text v-if="selectedEventData">
                <v-simple-table fixed-header height="500px">
                  <template v-slot:default>
                    <thead>
                      <tr>
                        <th class="text-left">Description</th>
                        <th class="text-left">Amount</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="item in selectedEventData" :key="item.name">
                        <td>{{ item.description }}</td>
                        <td>{{ item.amount }}</td>
                        <td>
                          <v-col cols="12" sm="3">
                            <v-btn
                              icon
                              color="error"
                              @click="handleDeleteCashflow(item)"
                            >
                              <v-icon>mdi-delete</v-icon>
                            </v-btn>
                          </v-col>
                        </td>
                      </tr>
                    </tbody>
                  </template>
                </v-simple-table>
              </v-card-text>
              <v-card-actions>
                <v-btn text color="secondary" @click="selectedOpen = false">
                  Close
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-menu>
        </v-sheet>
      </v-col>
    </v-row>
  </div>
</template>
<script>
import moment from "moment";

export default {
  data: () => ({
    moment: moment,
    cashflows: [],
    focus: "",
    type: "month",
    typeToLabel: {
      month: "Month",
      week: "Week",
      day: "Day",
      "4day": "4 Days",
    },
    selectedEvent: {},
    selectedEventData: [],
    selectedElement: null,
    selectedOpen: false,
    events: [],
    colors: [
      "blue",
      "indigo",
      "deep-purple",
      "cyan",
      "green",
      "orange",
      "grey darken-1",
    ],
    names: [
      "Meeting",
      "Holiday",
      "PTO",
      "Travel",
      "Event",
      "Birthday",
      "Conference",
      "Party",
    ],
    totalNiGawasKwarta: 0,
    totalNiSulodKwarta: 0,
    summary: {},
    chartData: [],
    chartOptions: {
      chart: {
        title: "",
        subtitle: "",
      },
      pointSize: 5,
    },
    loadingChart: false,
  }),
  mounted() {
    this.$refs.calendar.checkChange();
    this.getCashFlows();
    this.getTotalCashflows().then();
    this.getData();
  },
  methods: {
    getData() {
      this.loadingChart = true;
      this.$axios
        .get(`/api/cashflows/summary`)
        .then((response) => {
          this.summary = response.data;
          this.chartData = response.data.chart_data;
        })
        .finally(() => (this.loadingChart = false));
    },
    getCashFlows() {
      let params = {};
      if (this.focus) {
        params.month = moment(this.focus).format("M");
        params.year = moment(this.focus).format("Y");
      }
      this.$axios
        .get(`/api/cashflows/total-cashflow`, { params })
        .then((response) => {
          this.cashflows = response.data;
        });
    },
    async getCashflowDetails() {
      const response = await this.$axios.get(`/api/cashflows`, {
        params: {
          date: this.selectedEvent.date,
          deduction: this.selectedEvent.deduction ? 1 : 0,
        },
      });

      this.selectedEventData = response.data;
      return response;
    },
    async getTotalCashflows() {
      let params = {};
      if (this.focus) {
        params.month = moment(this.focus).format("M");
        params.year = moment(this.focus).format("Y");
      }
      const response = await this.$axios.get(`/api/cashflows/total`, {
        params,
      });
      this.totalNiGawasKwarta = response.data.totalNiGawasKwarta;
      this.totalNiSulodKwarta = response.data.totalNiSulodKwarta;
    },
    viewDay({ date }) {
      this.focus = date;
      this.type = "day";
    },
    getEventColor(event) {
      return event.color;
    },
    prev() {
      this.$refs.calendar.prev();
      this.getCashFlows();
      this.getTotalCashflows();
    },
    next() {
      this.$refs.calendar.next();
      this.getCashFlows();
      this.getTotalCashflows();
    },
    showEvent({ nativeEvent, event }) {
      const open = () => {
        this.selectedEvent = event;
        this.selectedElement = nativeEvent.target;
        this.getCashflowDetails().then((response) => {
          requestAnimationFrame(() =>
            requestAnimationFrame(() => (this.selectedOpen = true))
          );
        });
      };

      if (this.selectedOpen) {
        this.selectedOpen = false;
        requestAnimationFrame(() => requestAnimationFrame(() => open()));
      } else {
        open();
      }

      nativeEvent.stopPropagation();
    },
    updateRange({ start, end }) {
      // const events = [];
      // const min = new Date(`${start.date}T00:00:00`);
      // const max = new Date(`${end.date}T23:59:59`);
      // const days = (max.getTime() - min.getTime()) / 86400000;
      // const eventCount = this.rnd(days, days + 20);
      // for (let i = 0; i < eventCount; i++) {
      //   const allDay = this.rnd(0, 3) === 0;
      //   const firstTimestamp = this.rnd(min.getTime(), max.getTime());
      //   const first = new Date(firstTimestamp - (firstTimestamp % 900000));
      //   const secondTimestamp = this.rnd(2, allDay ? 288 : 8) * 900000;
      //   const second = new Date(first.getTime() + secondTimestamp);
      //   events.push({
      //     name: this.names[this.rnd(0, this.names.length - 1)],
      //     start: first,
      //     end: second,
      //     color: this.colors[this.rnd(0, this.colors.length - 1)],
      //     timed: !allDay,
      //   });
      // }
      // console.log(events, "events here");
      // this.events = events;
    },
    rnd(a, b) {
      return Math.floor((b - a + 1) * Math.random()) + a;
    },
    handleDeleteCashflow(cashflow) {
      console.log(cashflow);
      this.$swal
        .fire({
          title: `Delete Cashflow ${cashflow.description}?`,
          text: "You won't be able to revert this!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, delete it!",
        })
        .then((result) => {
          if (result.isConfirmed) {
            this.$axios
              .delete(`/api/cashflows/${cashflow.id}`)
              .then((response) => {
                this.$swal.fire(
                  "Deleted!",
                  `Cashflow ${cashflow.description} has been deleted`,
                  "success"
                );
                this.getCashFlows();
                this.$emit("update");
              });
          }
        });
    },
  },
  computed: {
    totalSelectedEventDetails() {
      if (this.selectedEventData.length === 0) return 0;

      return this.selectedEventData
        .map((data) => parseFloat(data.amount))
        .reduce((prevValue, currentValue) => {
          return prevValue + currentValue;
        });
    },
  },
};
</script>
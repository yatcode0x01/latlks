<template>
  <main class="container">
    <section>
      <div style="padding: 20px 20px 0px 20px">
        <div class="row">
          <div class="col-sm-6">
            <h3 class="sider">Dashboard > Poll > Show</h3>
          </div>
          <div class="col-sm-6 text-end" style="padding-top: 5px"></div>
        </div>
        <hr />
      </div>
      <div class="content" style="padding: 20px 20px 20px 20px">
        <form style="background: transparent; box-shadow: none; width: 100%">
          <div class="row">
            <div class="col-sm-4">
              <label>Polling Title / Question</label>
            </div>
            <div class="col-sm-8">
              <input type="text" class="w-100" v-model="title" readonly />
            </div>
          </div>

          <div class="row">
            <div class="col-sm-4">
              <label>Description</label>
            </div>
            <div class="col-sm-8">
              <input type="text" class="w-100" v-model="description" readonly />
            </div>
          </div>

          <div class="row">
            <div class="col-sm-4">
              <label>Deadline</label>
            </div>
            <div class="col-sm-8">
              <input
                type="date"
                class="w-100"
                :value="deadline"
                @input="(event) => (deadline = event.target.value)"
                readonly
              />
            </div>
          </div>

          <div class="row">
            <div class="col-sm-4">
              <label>Choice</label>
            </div>
            <div class="col-sm-8">
              <Bar
                :chart-options="chartOptions"
                :chart-data="chartData"
                :chart-id="chartId"
                :dataset-id-key="datasetIdKey"
                :plugins="plugins"
                :css-classes="cssClasses"
                :styles="styles"
                :width="width"
                :height="height"
              />
            </div>
          </div>
          <div class="row mt-5">
            <div class="col-sm-6" style="padding-top: 7px">
              <a href="/dashboard/poll" class="btn btn-secondary">Back</a>
            </div>
          </div>
        </form>
      </div>
    </section>
  </main>
</template>

<script>
import { Bar } from "vue-chartjs";
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
} from "chart.js";

ChartJS.register(
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale
);
export default {
  components: { Bar },
  props: {
    chartId: {
      type: String,
      default: "bar-chart",
    },
    datasetIdKey: {
      type: String,
      default: "label",
    },
    width: {
      type: Number,
      default: 400,
    },
    height: {
      type: Number,
      default: 400,
    },
    cssClasses: {
      default: "",
      type: String,
    },
    styles: {
      type: Object,
      default: () => {},
    },
    plugins: {
      type: Object,
      default: () => {},
    },
  },
  mounted() {
    let token = localStorage.getItem("token");

    if (!token) {
      return this.$router.push("/login");
    }

    let id = this.$route.params.id;
    this.$axios.defaults.headers.common = { Authorization: `Bearer ${token}` };
    this.$axios
      .get(`/poll/${id}`)
      .then((res) => {
        console.log(res);
        this.title = res.data.data.title;
        this.description = res.data.data.description;
        this.deadline = res.data.data.deadline;

        for (let i = 0; i < res.data.data.results.length; i++) {
          this.tmp_choice.push(res.data.data.results[i].count);
          this.chartData.labels.push(res.data.data.results[i].choice);
        }

        this.chartData.datasets.push({
          data: this.tmp_choice
        });

      })
      .catch((err) => {
        console.log(err);
      });
  },
  data() {
    return {
      title: "",
      description: "",
      deadline: "",
      tmp_choice: [],
      tmp_label: [],
      chartData: {
        labels: [],
        datasets: [{ data: [] }],
      },
      chartOptions: {
        responsive: true,
      },
      error: false,
      errorMessage: "",
    };
  },
  methods: {
    addItem() {
      this.choices.push({
        value: "",
      });
    },
  },
};
</script>

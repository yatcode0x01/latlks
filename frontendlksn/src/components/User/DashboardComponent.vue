<template>
    <main class="container">
      <section>
        <div style="padding: 20px 20px 0px 20px;">
          <div class="row">
            <div class="col-sm-6">
              <h3 class="sider">Dashboard</h3>
            </div>
            <div class="col-sm-6 text-end" style="padding-top: 5px;">
            </div>
          </div>
          <hr class="mb-1" />
        </div>
        <div class="alert alert-danger mt-1 ms-1 me-1" style="width: 97%;" v-if="error == true">
            {{ errorMessage }}
        </div>
        <a class="poll-item mt-1" v-for="item in data" :key="item.id" @click="show(item.id, item.is_past)">
          <div class="poll-title">{{ item.title }}</div>
          <div class="deadline">Deadline : {{ item.deadline }}</div>
          <span v-if="item.is_past == false" class="badge bg-primary ms-1">Active Vote</span>
          <span v-if="item.is_past == true" class="badge bg-danger ms-1">Non Active Vote</span>

          <span v-if="item.is_voted == true" class="badge bg-success ms-1">Vote Successfully!</span>
          <span v-if="item.is_voted == false" class="badge bg-warning ms-1">Haven't Voted Yet!</span>

          <div class="choice" v-if="item.is_voted == true">
          <div
            class="choice-item"
            v-for="choice in item.results"
            :key="choice.id"
          >
            <div class="choice-option">
              {{ choice.choice }} | {{ choice.count }} Voters
            </div>
            <progress :value="choice.point" max="100">
              {{ choice.point }}
            </progress>
            <div class="progress-value">{{ choice.point }}%</div>
          </div>
        </div>
        </a>
      </section>
    </main>
  </template>
  
  <script>
  export default {
    mounted() {
      let token = localStorage.getItem("token");
  
      if (!token) {
        return this.$router.push("/login");
      }
  
      this.$axios.defaults.headers.common = { Authorization: `Bearer ${token}` };
      this.$axios
        .get("/poll")
        .then((res) => {
          this.data = res.data.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    data() {
      return {
        data: [],
        error: false,
        errorMessage: ''
      };
    },
    methods: {
        show(id, is_past) {
            if (is_past === true) {
                this.errorMessage = 'This Polling Time Has Ended!'
                this.error = true
                setTimeout(function() {
                    this.error = false
                }, 2000);
                return false
            }

            return this.$router.push(`/user/poll/${id}`);
        }
    }
  };
  </script>
<template>
  <main class="container">
    <section>
      <div style="padding: 20px 20px 0px 20px;">
        <div class="row">
          <div class="col-sm-6">
            <h3 class="sider">Dashboard</h3>
          </div>
          <div class="col-sm-6 text-end" style="padding-top: 5px;">
            <a href="/dashboard/poll/create" class="btn btn-outline-dark">Create New Poll</a>
          </div>
        </div>
        <hr />
      </div>
      <a class="poll-item mt-1" v-for="item in data" :key="item.id" @click="show(item.id)">
        <div class="poll-title">{{ item.title }}</div>
        <div class="choice">
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
        console.log(res.data.data);
        this.data = res.data.data;
      })
      .catch((err) => {
        console.log(err);
      });
  },
  data() {
    return {
      data: [],
    };
  },
  methods:  {
    show(id) {
          this.$router.push(`/dashboard/poll/show/${id}`);
    }
  }
};
</script>
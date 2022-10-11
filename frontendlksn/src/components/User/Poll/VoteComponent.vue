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
        <div class="poll-item">
          <div class="poll-title">{{ title }}</div>
          <div class="deadline">Deadline : {{ deadline }}</div>
          <span v-if="is_past == false" class="badge bg-primary ms-1">Active Vote</span>
          <span v-if="is_past == true" class="badge bg-danger ms-1">Non Active Vote</span>

          <span v-if="is_voted == true" class="badge bg-success ms-1">Vote Successfully!</span>
          <span v-if="is_voted == false" class="badge bg-warning ms-1">Haven't Voted Yet!</span>
          <div class="choice d-block mt-2">
            <div class="choice-item" v-for="(choice, key) in choices" :key="key">
              <button type="button" class="btn btn-primary w-100 mb-1" @click="vote(choice.choice_id)">{{ choice.choice }}</button>
            </div>
          </div>
          <div class="row mt-5">
            <div class="col-sm-6" style="padding-top: 7px">
              <a href="/user" class="btn btn-secondary">Back</a>
            </div>
          </div>
        </div>
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
      const id = this.$route.params.id;
      this.$axios.defaults.headers.common = { Authorization: `Bearer ${token}` };
      this.$axios
        .get("/poll/"+ id)
        .then((res) => {
          this.title = res.data.data.title;
          this.deadline = res.data.data.deadline;
          this.is_past = res.data.data.is_past;
          this.is_voted = res.data.data.is_voted;
          this.choices = res.data.data.results;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    data() {
      return {
        title: '',
        deadline: '',
        is_past: '',
        is_voted: '',
        choices: [],
        error: false,
        errorMessage: ''
      };
    },
    methods: {
        vote (choice_id) {
          const id = this.$route.params.id;
          this.$axios.post(`/poll/${id}/vote/${choice_id}`).then(() => {
            this.$router.go('/user')
          }).catch(err => {
              this.error = true
              this.errorMessage = err.response.data.message
          });
        }
    }
  };
  </script>
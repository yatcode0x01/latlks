<template>
  <main class="container">
    <section>
      <div style="padding: 20px 20px 0px 20px">
        <div class="row">
          <div class="col-sm-6">
            <h3 class="sider">Dashboard > User Management</h3>
          </div>
          <div class="col-sm-6 text-end" style="padding-top: 5px">
          </div>
        </div>
        <hr />
      </div>
      <div class="content" style="padding: 20px 20px 20px 20px">
        <form
          style="background: transparent; box-shadow: none; width: 100%"
          @submit.prevent="store()"
        >
          <div class="alert alert-success mt-1" v-if="success === true">
            Create New User Successfully!
          </div>
          <div class="alert alert-danger mt-1" v-if="error === true">
            {{ errorMessage }}
          </div>
          <div class="row">
            <div class="col-sm-4">
              <label>Username</label>
            </div>
            <div class="col-sm-8">
              <input type="text" class="w-100" v-model="username" />
            </div>
          </div>

          <div class="row">
            <div class="col-sm-4">
              <label>Password</label>
            </div>
            <div class="col-sm-8">
              <input type="text" class="w-100" v-model="password" />
            </div>
          </div>

          <div class="row">
            <div class="col-sm-4">
              <label>Role</label>
            </div>
            <div class="col-sm-8">
              <select v-model="role">
                <option value="" selected>Choose Role</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-4">
              <label>Division</label>
            </div>
            <div class="col-sm-8">
              <select v-model="division_id">
                <option value="" selected>Choose Division</option>
                <option v-for="(division, key) in division" :key="key" :value="division.id">{{ division.name }}</option>
              </select>
            </div>
          </div>

          <div class="row mt-5">
            <div class="col-sm-6" style="padding-top: 7px">
              <a href="/dashboard/poll" class="btn btn-secondary">Back</a>
            </div>
            <div class="col-sm-6 text-end">
              <button type="submit" class="btn btn-primary">Create New</button>
            </div>
          </div>
        </form>
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

    this.$axios.defaults.headers.common = { Authorization: `Bearer ${token}` };
    this.$axios
    .get("/v1/division")
    .then((res) => {
      this.division = res.data.data;
    })
    .catch((err) => {
      console.log(err);
    });
  },
  data() {
    return {
      division: [],
      error: false,
      success: false,
      username: '',
      password: '',
      role: '',
      division_id: '',
      errorMessage: "",
    };
  },
  methods: {
    store() {
        let token = localStorage.getItem("token");
        this.$axios.defaults.headers.common = { Authorization: `Bearer ${token}` };

        this.$axios.post('/user/', {
            username: this.username,
            password: this.password,
            role: this.role,
            division_id: this.division_id
        }).then(() => {
            this.error = false
            this.success = true
        }).catch(err => {
            this.success = false
            this.error = true
            this.errorMessage = err.response.data.message
        });
    },
  },
};
</script>
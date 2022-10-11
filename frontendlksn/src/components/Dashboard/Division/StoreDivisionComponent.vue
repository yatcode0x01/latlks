<template>
    <main class="container">
      <section>
        <div style="padding: 20px 20px 0px 20px">
          <div class="row">
            <div class="col-sm-6">
              <h3 class="sider">Dashboard > Division > Create</h3>
            </div>
            <div class="col-sm-6 text-end" style="padding-top: 5px"></div>
          </div>
          <hr />
        </div>
        <div class="content" style="padding: 20px 20px 20px 20px">
          <form style="background: transparent;box-shadow: none; width: 100%;" @submit.prevent="store()">
            <div class="alert alert-danger mt-1" v-if="error === true">{{ errorMessage }}</div>
            <div class="form-field">
                <label>Division Name</label>
                <input type="text" class="ms-3" v-model="name"/>
            </div>

            <div class="row mt-5">
                <div class="col-sm-6" style="padding-top: 7px;">
                    <a href="/dashboard/division" class="btn btn-secondary">Back</a>
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
    },
    data() {
      return {
        name: '',
        error: false,
        errorMessage: ''
      };
    },
    methods: {
      store() {
        let token = localStorage.getItem("token");
        this.$axios.defaults.headers.common = { Authorization: `Bearer ${token}` };

        this.$axios.post('/v1/division/', {
            name: this.name,
        }).then(() => {
            this.$router.push('/dashboard/division')
        }).catch(err => {
            this.error = true
            this.errorMessage = err.response.data.message
        });
      },
    },
  };
  </script>
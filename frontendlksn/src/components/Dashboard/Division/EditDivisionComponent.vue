<template>
    <main class="container">
      <section>
        <div style="padding: 20px 20px 0px 20px">
          <div class="row">
            <div class="col-sm-6">
              <h3 class="sider">Dashboard > Division > Edit</h3>
            </div>
            <div class="col-sm-6 text-end" style="padding-top: 5px">
              <a href="/dashboard/division/create" class="btn btn-outline-dark">Create New Division</a>
            </div>
          </div>
          <hr />
        </div>
        <div class="content" style="padding: 20px 20px 20px 20px">
          <form style="background: transparent;box-shadow: none; width: 100%;" @submit.prevent="update()">
            <div class="alert alert-danger mt-1" v-if="error === true">{{ errorMessage }}</div>
            <div class="form-field">
                <label>Division Name</label>
                <input type="text" class="ms-3" :value="divisionName" @input="event => divisionName = event.target.value"/>
            </div>

            <div class="row mt-5">
                <div class="col-sm-6" style="padding-top: 7px;">
                    <a href="/dashboard/division" class="btn btn-secondary">Back</a>
                </div>
                <div class="col-sm-6 text-end">
                    <button type="submit" class="btn btn-primary">Update</button>
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

      let id = this.$route.params.id;
      this.$axios.defaults.headers.common = { Authorization: `Bearer ${token}` };
      this.$axios
        .get(`/v1/division/${id}`)
        .then((res) => {
          this.divisionName = res.data.data.name;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    data() {
      return {
        divisionName: '',
        error: false,
        errorMessage: ''
      };
    },
    methods: {
      update() {
        console.log(this.divisionName);
        let token = localStorage.getItem("token");
        let id = this.$route.params.id;
        this.$axios.defaults.headers.common = { Authorization: `Bearer ${token}` };

        this.$axios.put('/v1/division/'+ id, {
            name: this.divisionName,
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
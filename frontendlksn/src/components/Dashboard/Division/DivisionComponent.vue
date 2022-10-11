<template>
  <main class="container">
    <section>
      <div style="padding: 20px 20px 0px 20px">
        <div class="row">
          <div class="col-sm-6">
            <h3 class="sider">Dashboard > Division</h3>
          </div>
          <div class="col-sm-6 text-end" style="padding-top: 5px">
            <a href="/dashboard/division/create" class="btn btn-outline-dark">Create New Division</a>
          </div>
        </div>
        <hr />
      </div>
      <div class="content" style="padding: 20px 20px 20px 20px">
        <table>
          <thead>
            <tr>
              <th>No</th>
              <th>Division Name</th>
              <th>Division Member</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, i) in data" :key="item.id">
              <td>{{ i + 1 }}</td>
              <td>{{ item.name }}'s Division</td>
              <td>{{ item.count_member }} People's</td>
              <td>
                <button type="button" class="btn btn-primary ms-1" @click="edit(item.id)">Edit</button>
                <button type="button" class="btn btn-danger ms-1" @click="remove(item.id)">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
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
      error: false,
      errorMessage: ''
    };
  },
  methods: {
    edit(id) {
        this.$router.push(`/dashboard/division/edit/${id}`);
    },
    remove(id) {
        this.$axios.delete('/v1/division/'+id).then(() => {
            this.$router.go()
        }).catch(err => {
            this.error = true
            this.errorMessage = err.response.data.message
        });
    }
  },
};
</script>
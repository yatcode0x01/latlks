<template>
  <div id="app">
    <header v-if="loggedIn === true">
      <nav>
        <a href="#" class="navbar-brand">>_YukPILIH</a>
        <ul v-if="role === 'admin'">
          <router-link tag="li" active-class="active" to="/dashboard" exact><a>Dashboard</a></router-link>
          <router-link tag="li" active-class="active" to="/dashboard/division"><a>Division</a></router-link>
          <router-link tag="li" active-class="active" to="/dashboard/poll"><a>Poll</a></router-link>
          <router-link tag="li" active-class="active" to="/dashboard/usermanagement"><a>User Management</a></router-link>
          <router-link tag="li" active-class="active" to="/dashboard/trash"><a>Trash</a></router-link>
          <li style="float: right; margin-right: 100px">
            <a @click="logout">Sign Out</a>
          </li>
        </ul>

        <ul v-if="role === 'user'">
          <router-link tag="li" active-class="active" to="/user" exact><a>Dashboard</a></router-link>
          <li style="float: right; margin-right: 100px">
            <a @click="logout">Sign Out</a>
          </li>
        </ul>
        
      </nav>
    </header>
    <router-view />
  </div>
</template>

<script>
export default {
  name: "App",
  data() {
    return {
      role: null,
      loggedIn: false,
    };
  },
  mounted() {
    let token = localStorage.getItem("token");
    this.role = localStorage.getItem("role");

    if (!token) {
      return this.$router.push("/login");
    }
    this.loggedIn = true;
  },
  methods: {
    logout() {
      let token = localStorage.getItem("token");
      this.$axios.defaults.headers.common = {'Authorization': `Bearer ${token}`}
      this.$axios
        .post("/auth/logout")
        .then((res) => {
          console.log(res)
          localStorage.removeItem('token');
          this.$router.go('/login')
        })
        .catch((err) => {
          console.log(err)
        });
    },
  },
};
</script>
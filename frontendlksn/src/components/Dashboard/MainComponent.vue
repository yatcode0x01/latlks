<template>
  <router-view />
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
      data: [],
    };
  },
};
</script>
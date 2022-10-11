<template>
    <form class="text-start mx-auto p-2" @submit.prevent="login()">
        <h1 class="text-primary">>_YukPILIH <br/> Sign In</h1>
        <div class="alert alert-danger mt-1" v-if="error === true">{{ errorMessage }}</div>
        <div class="form-field mt-3">
            <input type="text" class="w-100" v-model="username" placeholder="Username" onfocus="this.value=''">
        </div>
        <div class="form-field mt-2">
            <input type="password" class="w-100" v-model="password" placeholder="Password" onfocus="this.value=''">
        </div>
        <div class="form-field mt-2 text-center">
            <button type="submit" class="btn btn-primary w-100 mb-1">Sign In</button>
            <span>- or -</span>
            <a href="#" class="text-primary d-block mt-1">Forgot Password?</a>
        </div>
    </form>
</template>

<script>
    export default {
        mounted() {
            let token = localStorage.getItem('token');
            if (token) {
                return this.$router.push('/dashboard')
            }
            return this.$router.push('/login')
        },
        data() {
            return {
                username: '',
                password: '',
                error: false,
                errorMessage: ''
            }
        },
        methods: {
            login() {
                this.$axios.post('/auth/login', {
                    username: this.username,
                    password: this.password
                }).then(res => {
                    this.error = false
                    let token = res.data.data.access_token;
                    localStorage.setItem('token', token)
                    this.$axios.defaults.headers.common = {'Authorization': `Bearer ${token}`}
                    this.$axios
                        .post("/auth/me")
                        .then((res) => {
                            localStorage.setItem('role', res.data.data.role)
                            this.$router.go('/dashboard')
                        })
                        .catch((err) => {
                        console.log(err)
                        });
                }).catch(err => {
                    this.error = true
                    this.errorMessage = err.response.data.message
                });
            },
        }
    }
    </script>
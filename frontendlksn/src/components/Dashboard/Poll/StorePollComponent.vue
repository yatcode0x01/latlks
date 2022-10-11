<template>
  <main class="container">
    <section>
      <div style="padding: 20px 20px 0px 20px">
        <div class="row">
          <div class="col-sm-6">
            <h3 class="sider">Dashboard > Poll > Create</h3>
          </div>
          <div class="col-sm-6 text-end" style="padding-top: 5px"></div>
        </div>
        <hr />
      </div>
      <div class="content" style="padding: 20px 20px 20px 20px">
        <form
          style="background: transparent; box-shadow: none; width: 100%"
          @submit.prevent="store()"
        >
          <div class="alert alert-danger mt-1" v-if="error === true">
            {{ errorMessage }}
          </div>
          <div class="row">
            <div class="col-sm-4">
              <label>Polling Title / Question</label>
            </div>
            <div class="col-sm-8">
              <input type="text" class="w-100" v-model="title" />
            </div>
          </div>

          <div class="row">
            <div class="col-sm-4">
              <label>Description</label>
            </div>
            <div class="col-sm-8">
              <input type="text" class="w-100" v-model="description" />
            </div>
          </div>

          <div class="row">
            <div class="col-sm-4">
              <label>Deadline</label>
            </div>
            <div class="col-sm-8">
              <input type="date" class="w-100" v-model="deadline" />
            </div>
          </div>

          <div class="row">
            <div class="col-sm-4">
              <label>Choice</label>
            </div>
            <div class="col-sm-8">
              <input
                type="text"
                class="w-100"
                v-for="(choice, key) in choices"
                v-model="choice.value"
                @change="addItem()"
                :key="key"
              />
              <button type="button" class="btn btn-danger w-100 mt-1" style="font-size: 13px;" @click="removeChoice()" v-if="choices.length > 1">Remove Choice Fields</button>
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

    this.addItem();
  },
  data() {
    return {
      title: "",
      description: "",
      deadline: "",
      choices: [],
      error: false,
      errorMessage: "",
    };
  },
  methods: {
    addItem() {
      this.choices.push({
        value: "",
      });
    },
    removeChoice() {
        this.choices.pop();
        this.choices.pop();
        this.addItem();
    },
    store() {
      let token = localStorage.getItem("token");
      this.$axios.defaults.headers.common = {
        Authorization: `Bearer ${token}`,
      };

      let dataChoice = [];
      for (let i = 0; i < this.choices.length; i++) {
        dataChoice.push(this.choices[i].value);
      }

      var FormData = require("form-data");
      var data = new FormData();
      
      data.append("title", this.title);
      data.append("description", this.description);
      data.append("deadline", this.deadline);

      for (let i = 0; i < this.choices.length; i++) {
        if (this.choices[i].value != '') {
            data.append("choice[]", this.choices[i].value);
        }
      }

      this.$axios
        .post("/poll", data)
        .then(() => {
          this.$router.push("/dashboard/poll");
        })
        .catch((err) => {
          this.error = true;
          this.errorMessage = err.response.data.message;
        });
    },
  },
};
</script>
<template>
  <div class="auth-container">
    <h2>Login</h2>
    <form @submit.prevent="login">
      <div class="form-group">
        <label for="email">Email</label>
        <input v-model="email" type="email" id="email" required />
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input v-model="password" type="password" id="password" required />
      </div>
      <button type="submit">Login</button>
      <p v-if="error" class="error">{{ error }}</p>
    </form>
  </div>
</template>

<script>
import axios from "axios";
// import { inject } from 'vue';
// import { mapState, mapMutations } from 'vuex';

export default {
  setup() {
    // const loggedIn = inject('loggedIn');
  },

  computed: {
    // ...mapState(['user']),
  },
  data() {
    return {
      email: "",
      password: "",
      error: "",
    };
  },

  methods: {
    // ...mapMutations(['setUser']),
    async login() {
      this.error = null;
      try {
        // First, get the CSRF cookie. This is necessary for Laravel's CSRF protection.
        await axios.get("/sanctum/csrf-cookie");
        console.log("test start");
        // Then, send a POST request to the /login endpoint with the email and password.
        const res = await axios.post("/login", {
          email: this.email,
          password: this.password,
        });

        if (res.status === 200 || res.status === 204) {
          this.$parent.loggedIn = true;
          localStorage.setItem("user", JSON.stringify(res.data));
          this.$router.push("/");
        } else {
          // If the request authentication fails, set an error message to be displayed to the user.
          this.error = "Invalid login credentials";
        }
      } catch (err) {
        // If there's a network error or other error, set an error message to be displayed to the user.
        this.error = "Some network error occurred, please try again later.";
      }
    },
  },
};
</script>

<style scoped>
.auth-container {
  max-width: 400px;
  margin: 50px auto;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  text-align: left;
}
h2 {
  text-align: center;
  margin-bottom: 20px;
}
.form-group {
  margin-bottom: 15px;
}
label {
  display: block;
  margin-bottom: 5px;
}
input {
  width: 100%;
  padding: 8px;
  box-sizing: border-box;
}
button {
  width: 100%;
  padding: 10px;
  background-color: #42b983;
  border: none;
  color: white;
  font-size: 16px;
  cursor: pointer;
}
button:hover {
  background-color: #339966;
}
.error {
  color: red;
  margin-top: 10px;
  text-align: center;
}
</style>

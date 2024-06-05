<template>
  <div class="auth-container">
    <h2>Login</h2>
    <form @submit.prevent="login">
      <div class="form-group">
        <label for="email">Email</label>
        <input v-model="email" type="email" id="email" required v-validate="'email'" @blur="validateInput" />
        <p v-if="emailError" class="error">{{ emailError }}</p>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input v-model="password" type="password" id="password" required v-validate="'password'" @blur="validateInput" />
        <p v-if="passwordError" class="error">{{ passwordError }}</p>
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
  directives: {
    validate: {
      inserted: function (el, binding) {
        el.addEventListener('input', function () {
          switch (binding.value) {
            case 'email':{
              // Simple email validation
              const emailRegex = /^\S+@\S+\.\S+$/;
              if (!emailRegex.test(el.value)) {
                alert('Invalid email');
              }
              break;
            }
            case 'password':
              // Simple password validation
              if (el.value.length < 8) {
                alert('Password must be at least 8 characters');
              }
              break;
          }
        });
      }
    }
  },
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
      emailError: '',
      passwordError: '',
    };
  },

  methods: {
    // ...mapMutations(['setUser']),
    async login() {
      this.error = null;
      try {
        // First, get the CSRF cookie. This is necessary for Laravel's CSRF protection.
        await axios.get("/sanctum/csrf-cookie");
        // Then, send a POST request to the /login endpoint with the email and password.
        const res = await axios.post("/login", {
          email: this.email,
          password: this.password,
        });
        console.log("login response is: ", res);
        // Check if the response data indicates a successful login.
        if (res.data && res.data.user) {
          // this.$parent.loggedIn = true;
          // When the login is successful, call the setUser mutation with the user data.
          this.$store.commit("setUser", res.data.user);
          localStorage.setItem("user", JSON.stringify(res.data.user));
          this.$router.push("/");
        } else {
          // If the request authentication fails, set an error message to be displayed to the user.
          this.error = "Invalid login credentials";
        }
      } catch (err) {
        // If there's a network error or other error, set an error message to be displayed to the user.
        this.error =
          err.response && err.response.data && err.response.data.message
            ? err.response.data.message
            : "Some network error occurred, please try again later.";
      }
    },
    validateInput(event) {
      const inputType = event.target.id;
      switch (inputType) {
        case 'email': {
          // Simple email validation
          const emailRegex = /^\S+@\S+\.\S+$/;
          if (!emailRegex.test(this.email)) {
            this.emailError = 'Invalid email';
          } else {
            this.emailError = '';
          }
          break;
        }
        case 'password':
          // Simple password validation
          if (this.password.length < 8) {
            this.passwordError = 'Password must be at least 8 characters';
          } else {
            this.passwordError = '';
          }
          break;
      }
    },
  },
};
</script>

<style scoped>
.auth-container {
  max-width: 800px;
  margin: 50px auto;
  padding: 100px;
  border: 5px  #ccc;
  border-radius: 8px;
  box-shadow: 0 0 0px rgba(0, 0, 0, 0.1);
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

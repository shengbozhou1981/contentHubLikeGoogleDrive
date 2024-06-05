<template>
  <div class="auth-container">
    <h2>Register</h2>
    <form @submit.prevent="register">
      <div class="form-group">
        <label for="name">Name</label>
        <input v-model="name" type="text" id="name" required @blur="validateInput" />
        <p v-if="nameError" class="error">{{ nameError }}</p>
      </div>
      <div class="form-group">
        <label for="email">Email</label>
        <input v-model="email" type="email" id="email" required @blur="validateInput" />
        <p v-if="emailError" class="error">{{ emailError }}</p>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input v-model="password" type="password" id="password" required @blur="validateInput" />
        <p v-if="passwordError" class="error">{{ passwordError }}</p>
      </div>
      <div class="form-group">
        <label for="password_confirmation">Confirm Password</label>
        <input
          v-model="password_confirmation"
          type="password"
          id="password_confirmation"
          required
          @blur="validateInput"
        />
        <p v-if="passwordConfirmationError" class="error">{{ passwordConfirmationError }}</p>
      </div>
      <button type="submit">Register</button>
      <p v-if="error" class="error">{{ error }}</p>
    </form>
  </div>
</template>


<script>
import axios from "axios";

export default {
  data() {
    return {
      name: "",
      email: "",
      password: "",
      password_confirmation: "",
      error: "",
      nameError: '',
      emailError: '',
      passwordError: '',
      passwordConfirmationError: '',
    };
  },
  methods: {
    register() {
      axios.get("/sanctum/csrf-cookie").then(() => {
        console.log("test start...");
        axios
          .post("/register", {
            name: this.name,
            email: this.email,
            password: this.password,
            password_confirmation: this.password_confirmation,
          })
          .then((response) => {
            console.log("register callback enter....");
            console.log("register response is: ", response);
            if (response.status === 200) {
              // Handle successful registration, e.g., show success message
              this.errors = {}; // Clear errors
              localStorage.setItem("user", JSON.stringify(response.data));
              this.$router.push("/login"); // Optionally redirect
            } else if (response.status === 400) {
              // Handle invalid request, e.g., show error message
              this.errors =
                "Invalid request. Please check your details and try again.";
            } else {
              // Handle other status codes, e.g., show error message
              this.errors =
                "An unexpected error occurred. Please try again later.";
            }
          })
          .catch((error) => {
            if (error.response && error.response.status === 422) {
              // Handle validation errors
              this.errors = error.response.data.errors;
            } else {
              // Handle other errors
              this.errors =
                "Registration failed. Please check your details and try again.";
            }
          });
      });
    },
    validateInput(event) {
      const inputType = event.target.id;
      switch (inputType) {
        case 'name':
          // Simple name validation
          if (this.name.length < 2) {
            this.nameError = 'Name must be at least 2 characters';
          } else {
            this.nameError = '';
          }
          break;
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
        case 'password_confirmation':
          // Check if password and confirmation match
          if (this.password !== this.password_confirmation) {
            this.passwordConfirmationError = 'Passwords do not match';
          } else {
            this.passwordConfirmationError = '';
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

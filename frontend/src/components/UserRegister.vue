<template>
  <div class="min-w-xl mx-auto p-24 border-5 border-gray-300 rounded-lg shadow-sm text-left">
    <h2 class="text-center mb-5">Register</h2>
    <form @submit.prevent="register" class="space-y-4">
      <div class="max-w-md mx-auto">
        <label for="name" class="block mb-2">Name</label>
        <input
          v-model="name"
          type="text"
          id="name"
          required
          @blur="validateInput"
          class="w-full p-2 border rounded"
        />
        <p v-if="nameError" class="mt-2 text-center text-red-500">{{ nameError }}</p>
      </div>
      <br>
      <div class="max-w-md mx-auto">
        <label for="email" class="block mb-2">Email</label>
        <input
          v-model="email"
          type="email"
          id="email"
          required
          @blur="validateInput"
          class="w-full p-2 border rounded"
        />
        <p v-if="emailError" class="mt-2 text-center text-red-500">{{ emailError }}</p>
      </div>
      <br>
      <div class="max-w-md mx-auto">
        <label for="password" class="block mb-2">Password</label>
        <input
          v-model="password"
          type="password"
          id="password"
          required
          @blur="validateInput"
          class="w-full p-2 border rounded"
        />
        <p v-if="passwordError" class="mt-2 text-center text-red-500">{{ passwordError }}</p>
      </div>
      <br>
      <div class="max-w-md mx-auto">
        <label for="password_confirmation" class="block mb-2">Confirm Password</label>
        <input
          v-model="password_confirmation"
          type="password"
          id="password_confirmation"
          required
          @blur="validateInput"
          class="w-full p-2 border rounded"
        />
        <p v-if="passwordConfirmationError" class="mt-2 text-center text-red-500">
          {{ passwordConfirmationError }}
        </p>
      </div>
      <br>
      <br>
      <button type="submit" class="w-full py-2 bg-green-500 text-white rounded cursor-pointer hover:bg-green-600">Register</button>
      <p v-if="error" class="mt-2 text-center text-red-500">{{ error }}</p>
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
      nameError: "",
      emailError: "",
      passwordError: "",
      passwordConfirmationError: "",
    };
  },
  methods: {
    register() {
      axios.get("/sanctum/csrf-cookie").then(() => {
        axios
          .post("/register", {
            name: this.name,
            email: this.email,
            password: this.password,
            password_confirmation: this.password_confirmation,
          })
          .then((response) => {
            if (response.data) {
              this.errors = {};
              this.$router.push("/login");
            } else if (response.status === 400) {
              this.errors =
                "Invalid request. Please check your details and try again.";
            } else {
              this.errors =
                "An unexpected error occurred. Please try again later.";
            }
          })
          .catch((error) => {
            if (error.response && error.response.status === 422) {
              this.errors = error.response.data.errors;
            } else {
              this.errors =
                "Registration failed. Please check your details and try again.";
            }
          });
      });
    },
    validateInput(event) {
      const inputType = event.target.id;
      switch (inputType) {
        case "name":
          if (this.name.length < 2) {
            this.nameError = "Name must be at least 2 characters";
          } else {
            this.nameError = "";
          }
          break;
        case "email": {
          const emailRegex = /^\S+@\S+\.\S+$/;
          if (!emailRegex.test(this.email)) {
            this.emailError = "Invalid email";
          } else {
            this.emailError = "";
          }
          break;
        }
        case "password":
          if (this.password.length < 8) {
            this.passwordError = "Password must be at least 8 characters";
          } else {
            this.passwordError = "";
          }
          break;
        case "password_confirmation":
          if (this.password !== this.password_confirmation) {
            this.passwordConfirmationError = "Passwords do not match";
          } else {
            this.passwordConfirmationError = "";
          }
          break;
      }
    },
  },
};
</script>

<style scoped>
div {
  max-width: 800px;
  border: 5px #ccc;
  border-radius: 8px;
  box-shadow: 0 0 0px rgba(0, 0, 0, 0.1);
  text-align: center;
  margin: 0 auto; /* Add this line */
}
/* Responsive styles */
@media screen and (max-width: 800px) {
  .div {
    padding: 50px;
  }
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

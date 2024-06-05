<template>
  <div id="app">
    <header>
      <nav class="nav-bar">
        <img src="/logo.png" alt="Company Logo" class="logo">
        <router-link class="nav-item large-font"  to="/">Guard.me Content Hub</router-link>
        <div class="nav-right">
          <router-link v-if="!loggedIn" class="nav-item" to="/login"
            >Login</router-link
          >
          <router-link v-if="!loggedIn" class="nav-item" to="/register"
            >Register</router-link
          >
          <button v-if="loggedIn" class="nav-item" @click="logout">
            Logout
          </button>
        </div>
      </nav>
    </header>
    <main>
      <router-view />
    </main>
  </div>
</template>

<script>
import { provide } from "vue";
import { logout as logoutUser } from "@/services/authService";
import { useRouter } from "vue-router";
import { computed } from 'vue';
import { useStore } from 'vuex';

export default {
  setup() {
    const store = useStore();
    const router = useRouter();
    // Use a Vuex state property instead of localStorage directly.
    const loggedIn = computed(() => store.state.user !== null);

    provide('loggedIn', loggedIn);

    const logout = async () => {
      await logoutUser();
      store.commit('clearUser'); 
      localStorage.removeItem("user");
      loggedIn.value = false;
      router.push("/login");
    };

    return { loggedIn, logout };
  },
};
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}

header {
  background-color: #008000; /* darker background */
  padding: 20px; /* more padding */
  color: white;
}

nav a {
  margin: 0 15px;
  color: white;
  text-decoration: none;
}

nav a.router-link-exact-active {
  font-weight: bold;
}

nav a:hover {
  color: #42b983; /* hover effect */
}

main {
  padding: 20px;
}

.nav-bar {
  display: flex;
  justify-content: space-between;
  align-items: center; /* align items vertically */
  height: 60px; /* more height */
}

.nav-bar .logo {
  height: 100%;
  object-fit: contain; /* contain the logo within its height */
}

.nav-right {
  display: flex;
  gap: 10px;
}

.nav-item {
  margin-right: 10px;
}

.large-font {
  font-size: 50px;
}
</style>
<template>
  <div id="app">
    <header>
      <nav class="nav-bar">
    <router-link class="nav-item" to="/">Guard.me Content Hub</router-link>
    <div class="nav-right">
      <router-link v-if="!loggedIn" class="nav-item" to="/login">Login</router-link>
      <router-link v-if="!loggedIn" class="nav-item" to="/register">Register</router-link>
      <button v-if="loggedIn" class="nav-item" @click="logout">Logout</button>
    </div>
  </nav>
    </header>
    <main>
      <router-view />
    </main>
  </div>
</template>

<script>
import { ref } from 'vue';
import { logout as logoutUser } from '@/services/authService';
import { useRouter } from 'vue-router';

export default {
  setup() {
    const router = useRouter();
    const loggedIn = ref(localStorage.getItem('user') !== null);

    const logout = async () => {
      await logoutUser();
      localStorage.removeItem('user');
      loggedIn.value = false;
      router.push('/login');
    };

    return { loggedIn, logout };
  }
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
  background-color: #42b983;
  padding: 15px;
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

main {
  padding: 20px;
}

.nav-bar {
  display: flex;
  justify-content: space-between;
}

.nav-right {
  display: flex;
  gap: 10px;
}

.nav-item {
  margin-right: 10px;
}
</style>

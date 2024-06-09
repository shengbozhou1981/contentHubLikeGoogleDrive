<template>
  <div id="app">
    <header>
      <nav class="nav-bar">
        <img src="/logo.png" alt="Company Logo" class="logo" />
        <component
          v-for="item in navItems"
          :key="item.name"
          :is="item.link.startsWith('http') ? 'a' : 'router-link'"
          :to="item.link.startsWith('http') ? undefined : item.link"
          :href="item.link.startsWith('http') ? item.link : undefined"
          class="block"
        >
          <i :class="item.icon"></i>
          {{ item.name }}
        </component>
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
import { computed } from "vue";
import { useStore } from "vuex";

export default {
  data() {
    return {
      show: false,
      navItems: [
        { name: "Home", link: "https://www.guard.me/", icon: "fas fa-hdd" },
        { name: "My Drive", link: "/", icon: "fas fa-hdd" },
        { name: "Recent", link: "/recent", icon: "fas fa-clock" },
        { name: "Trash", link: "/trash", icon: "fas fa-trash" },
        // Add more nav items here
      ],
    };
  },
  methods: {
    createNewFolder() {
      console.log("Creating new folder...");
      this.show = false;
    },
    uploadFile() {
      console.log("Uploading file...");
      this.show = false;
    },
    uploadFolder() {
      console.log("Uploading folder...");
      this.show = false;
    },
    // 其他方法
  },
  setup() {
    const store = useStore();
    const router = useRouter();
    // Use a Vuex state property instead of localStorage directly.
    const loggedIn = computed(() => store.state.user !== null);

    provide("loggedIn", loggedIn);

    const logout = async () => {
      await logoutUser();
      store.commit("clearUser");
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

.sidebar {
  position: fixed;
  left: 0;
  top: 0;
  width: 180px;
  height: 100%;
  background-color: #f5f5f5;
  padding: 20px;
}

.sidebar ul {
  list-style: none;
  display: flex;
  flex-direction: column;

  align-items: start;
  margin: 0;
}

.sidebar li {
  margin: 20px 0;
}

.sidebar a {
  color: #333;
  text-decoration: none;
}

.sidebar a:hover {
  color: #007bff;
}

.sidebar i {
  margin-right: 10px;
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
  margin-right: 15px;
}

.large-font {
  font-size: 50px;
}
</style>

<template>
  <div id="app" class="d-flex flex-column min-vh-100">
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
          <i
            :class="item.icon"
            :style="{ fontSize: '24px', marginRight: '5px' }"
          ></i>
          <span :style="{ fontSize: '24px' }">{{ item.name }}</span>
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
    <main class="flex-grow-1">
      <router-view />
    </main>
    <footer>
      <div class="d-flex justify-content-between" id="social-container">
        <div class="align-self-start tech-support">
          <a href="https://www.guard.me/technicalsupport.php" class="btn-danger"
            >Technical Support</a
          >
        </div>

        <div class="d-flex align-self-start" id="social-media-links">
          <a
            v-for="link in socialLinks"
            :key="link.id"
            :title="link.title"
            :href="link.url"
            target="_blank"
          >
            <em :class="link.icon"></em>
          </a>
        </div>
      </div>

      <div class="row justify-content-center" id="bottom-text">
        <!-- ... -->
      </div>
    </footer>
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
      socialLinks: [
        {
          id: "subscribe",
          title: "Mailing List",
          url: "https://www.guard.me/mailinglist",
          icon: "fas fa-envelope",
        },
        {
          id: "linkedin",
          title: "LinkedIn",
          url: "https://www.linkedin.com/company/740181",
          icon: "fab fa-linkedin",
        },
        {
          id: "twitter",
          title: "Twitter",
          url: "https://www.twitter.com/GuardmeIns",
          icon: "fab fa-twitter",
        },
        {
          id: "facebook",
          title: "Facebook",
          url: "https://www.facebook.com/guardme-1707874979502087/",
          icon: "fab fa-facebook-f",
        },
        {
          id: "youtube",
          title: "Youtube",
          url: "https://www.youtube.com/channel/UCJZ72TmsipZFrMmLi5k2V1w",
          icon: "fab fa-youtube",
        },
        // Add more social links here
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
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}
main {
  /* This will make the main content grow to fill the available space, pushing the footer to the bottom */
  flex-grow: 1;
  /* This will enable scrolling inside the main content if it overflows */
  overflow: auto;
}

footer {
  /* Set the height of the footer based on your needs */
  height: 50px;
  background-color: #f5f5f5;
  text-align: center;
}

#social-container {
  justify-content: center; /* This will center the items horizontally */
  align-items: center; /* This will center the items vertically */
}

.tech-support {
  margin-right: 20px; /* Adjust this value based on your needs */
}

.tech-support a {
  font-size: 20px; /* Adjust this value based on your needs */
  text-transform: uppercase; /* This will make the text uppercase */
}
.d-flex {
  display: flex;
}

.flex-column {
  flex-direction: column;
}

.min-vh-100 {
  min-height: 100vh;
}

.flex-grow-1 {
  flex-grow: 1;
  overflow: auto;
}

#social-media-links a {
  color: #007bff;
  margin-right: 20px;
}
#social-media-links a em {
  font-size: 30px; /* Adjust this value based on your needs */
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

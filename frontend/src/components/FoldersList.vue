<template>
    <div>
      <h3>Folders List</h3>
      <ul>
        <li v-for="folder in folders" :key="folder.id">
          <span @click="editFolder(folder)" class="text-blue-500 cursor-pointer">{{ folder.name }}</span>
          <button @click="deleteFolder(folder.id)" class="ml-2 text-red-500">Delete</button>
        </li>
      </ul>
    </div>
  </template>
  
  <script>
  import { ref } from 'vue';
  
  export default {
    props: {
      folders: Array,
    },
    setup(props, { emit }) {
      const editFolder = (folder) => {
        emit('editFolder', folder);
      };
  
      const deleteFolder = (folderId) => {
        emit('deleteFolder', folderId);
      };
  
      return {
        editFolder,
        deleteFolder,
      };
    },
  };
  </script>
  
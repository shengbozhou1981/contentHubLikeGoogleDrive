<template>
    <div>
      <h3>Files List</h3>
      <ul>
        <li v-for="file in files" :key="file.id">
          <span @click="editFile(file)" class="text-blue-500 cursor-pointer">{{ file.name }}</span>
          <button @click="deleteFile(file.id)" class="ml-2 text-red-500">Delete</button>
        </li>
      </ul>
    </div>
  </template>
  
  <script>
  import { ref } from 'vue';
  
  export default {
    props: {
      files: Array,
    },
    setup(props, { emit }) {
      const editFile = (file) => {
        emit('editFile', file);
      };
  
      const deleteFile = (fileId) => {
        emit('deleteFile', fileId);
      };
  
      return {
        editFile,
        deleteFile,
      };
    },
  };
  </script>
  
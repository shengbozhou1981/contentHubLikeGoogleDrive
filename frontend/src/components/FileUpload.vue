<template>
    <div v-if="showOptions">
      <label for="fileUpload">Upload File:</label>
      <input id="fileUpload" style="width: 100%; padding: 10px; font-size: 16px; cursor: pointer;" type="file" @change="handleFileUpload"/>
      <br>
      <label for="selectedFolderId">Select Folder:</label>
      <select id="selectedFolderId" v-model="selectedFolderId" style="width: 100%; padding: 10px; font-size: 16px; cursor: pointer;">
        <option value="">Select Folder</option>
        <option
          v-for="folder in flatFolders"
          :key="folder.id"
          :value="folder.id"
        >
          {{ folder.name }}
        </option>
      </select>
      <br>
      <br>
      
      <button @click="uploadNewFile" style="width: 50%; padding: 10px; font-size: 16px; cursor: pointer;">Upload File</button>
      <button @click="$emit('updateShowOptions', false)" class="p-2 text-lg bg-red-500 text-white mt-4" style="width: 50%; padding: 10px; font-size: 16px; text-align: center;">Cancel</button>

    </div>
  </template>
  
  <script>
  import { ref } from 'vue';
  
  export default {
    props: ['flatFolders','showOptions'],
    setup(props, { emit }) {
      const selectedFile = ref(null);
      const selectedFolderId = ref('');
  
      const handleFileUpload = (event) => {
        selectedFile.value = event.target.files[0];
      };
  
      const uploadNewFile = () => {
        if (!selectedFile.value) return;
  
        const formData = new FormData();
        formData.append('file', selectedFile.value);
        formData.append('name', selectedFile.value.name);
        formData.append('folder_id', selectedFolderId.value);
        formData.append('type', selectedFile.value.type);
        emit('uploadFile', formData);
        selectedFile.value = null;
        selectedFolderId.value = '';
      };
  
      return {
        selectedFile,
        selectedFolderId,
        handleFileUpload,
        uploadNewFile,
      };
    },
  };
  </script>
  <style scoped>
  div {
    /* max-width: 800px; */
    margin: 20px auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    text-align: left;
  }
  /* button {
    width: 100%;
    padding: 10px;
    background-color: #42b983;
    border: none;
    color: white;
    font-size: 16px;
    cursor: pointer;
  } */
  </style>
<template>
    <div>
      <label for="fileUpload">Upload File:</label>
      <input id="fileUpload" type="file" @change="handleFileUpload" />
  
      <label for="selectedFolderId">Select Folder:</label>
      <select id="selectedFolderId" v-model="selectedFolderId">
        <option value="">Select Folder</option>
        <option
          v-for="folder in flatFolders"
          :key="folder.id"
          :value="folder.id"
        >
          {{ folder.name }}
        </option>
      </select>
  
      <button @click="uploadNewFile">Upload File</button>
    </div>
  </template>
  
  <script>
  import { ref } from 'vue';
  
  export default {
    props: {
      flatFolders: Array,
    },
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
        console.log('File Uploaded:', formData);
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
  
<template>
    <div>
      <label for="folderName">Folder Name:</label>
      <input
        id="folderName"
        v-model="folderName"
        placeholder="New Folder Name"
      />
  
      <label for="parentFolderId">Parent Folder:</label>
      <select id="parentFolderId" v-model="parentFolderId">
        <option value="">Select a folder</option>
        <option
          v-for="folder in flatFolders"
          :key="folder.id"
          :value="folder.id"
        >
          {{ folder.name }}
        </option>
      </select>
  
      <button @click="createNewFolder">Create Folder</button>
    </div>
  </template>
  
  <script>
  import { ref } from 'vue';
  
  export default {
  props: ['flatFolders'],
  setup(props, { emit }) {
    const folderName = ref('');
    const parentFolderId = ref('');

    const createNewFolder = () => {
      const folderData = {
        name: folderName.value,
        parent_id: parentFolderId.value,
      };
      emit('createFolder', folderData);
      folderName.value = '';
      parentFolderId.value = '';
    };

    return {
      folderName,
      parentFolderId,
      createNewFolder,
    };
  },
};
  </script>
  
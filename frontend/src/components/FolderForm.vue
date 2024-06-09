<template>
  <div v-if="showOptions">
    <label for="folderName" class="text-lg">Folder Name:</label>
    <input
      id="folderName"
      v-model="folderName"
      placeholder="New Folder Name"
      class="p-2 text-lg w-full"
      style="width: 93%; padding: 10px; font-size: 16px; text-align: left;"
    />
<br>
<br>
    <label for="parentFolderId" class="text-lg">Parent Folder:</label>
    <select id="parentFolderId" v-model="parentFolderId" class="p-2 text-lg w-full" style="width: 100%; padding: 10px; font-size: 16px; text-align: left;">
      <option value="">Select a folder</option>
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
    <button  @click="createNewFolder" class="p-2 text-lg bg-blue-500 text-white mt-4" style="width: 50%; padding: 10px; font-size: 16px; text-align: center;">Create Folder</button>
    <button @click="$emit('updateShowOptions', false)" class="p-2 text-lg bg-red-500 text-white mt-4" style="width: 50%; padding: 10px; font-size: 16px; text-align: center;">Cancel</button>
  </div>
</template>

<script>
import { ref } from 'vue';

export default {

  props: ['flatFolders','showOptions'],
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
<style scoped>
div {
  max-width: 800px;
  margin: 20px auto;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  text-align: center;
}
div label {
  text-align: left;
}
</style>
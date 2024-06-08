<template>
  <div>
    <h2>File Manager</h2>

    <!-- Create Folder Section -->
    <div>
      <label for="folderName">Folder Name:</label>
      <input
        id="folderName"
        v-model="folderName"
        placeholder="New Folder Name"
        style="margin-right: 20px"
      />

      <label for="parentFolderId">Parent Folder:</label>
      <select id="parentFolderId" v-model="parentFolderId">
        <option value="">No Parent</option>
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

    <!-- Upload File Section -->

    <div>
      <label for="fileUpload" style="margin-right: 10px">Upload File:</label>
      <input id="fileUpload" type="file" @change="handleFileUpload" />

      <label for="selectedFolderId" style="margin-right: 10px"
        >Select Folder:</label
      >
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

      <button style="align-self: center" @click="uploadNewFile">
        Upload File
      </button>
    </div>

    <!-- Folders and Files List Section -->
    <div v-if="folders.length || files.length">
      <h3>Folders List</h3>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Parent ID</th>
            <th>UserId</th>
            <th>Type</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="folder in folders" :key="folder.id">
            <td>{{ folder.id }}</td>
            <td>{{ folder.name }}</td>
            <td>{{ folder.parent_id }}</td>
            <td>{{ folder.user_id }}</td>            
            <td>--</td>
            <td>
              <button @click="editFolder(folder)" class="ml-2 text-yellow-500">
                Edit
              </button>
              <button class="delete-button" @click="deleteFolder(folder.id)">
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
      <h3>Files List</h3>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Parent ID</th>
            <th>UserId</th>
            <th>Type</th>

            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="file in files" :key="file.id">
            <td>{{ file.id }}</td>
            <td>{{ file.name }}</td>
            <td>{{ file.folder_id }}</td>
            <td>{{ file.user_id }}</td>
            <td>{{ file.type }}</td>

            <td>
              <button class="button-margin" @click="editFile(file)">
                Edit
              </button>
              <button class="delete-button" @click="deleteFile(file.id)">
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Folder Edit Dialog -->
    <div
      v-if="showFolderEditDialog"
      class="fixed inset-0 bg-gray-800 bg-opacity-75 flex justify-center items-center"
    >
      <div class="bg-white p-4 rounded shadow-md">
        <h3 class="text-lg font-bold mb-2">Edit Folder</h3>
        <div class="mb-2">
          <label for="folderName" class="block font-bold mb-1"
            >Folder Name:</label
          >
          <input
            id="folderName"
            v-model="folderToEdit.name"
            class="border p-2 w-full"
          />
        </div>
        <div class="mb-2">
          <label for="parentFolderId" class="block font-bold mb-1"
            >Parent Folder ID:</label
          >
          <input
            id="parentFolderId"
            v-model="folderToEdit.parent_id"
            class="border p-2 w-full"
          />
        </div>
        <button @click="updateFolder" class="bg-blue-500 text-white p-2 mr-2">
          Save
        </button>
        <button
          @click="showFolderEditDialog = false"
          class="bg-gray-500 text-white p-2"
        >
          Cancel
        </button>
      </div>
    </div>

    <!-- File Edit Dialog -->
    <div
      v-if="showFileEditDialog"
      class="fixed inset-0 bg-gray-800 bg-opacity-75 flex justify-center items-center"
    >
      <div class="bg-white p-4 rounded shadow-md">
        <h3 class="text-lg font-bold mb-2">Edit File</h3>
        <label class="block mb-2">
          Name:
          <input
            v-model="fileToEdit.name"
            class="border p-2 mb-2 w-full"
          />
        </label>
        <label class="block mb-2">
          Type:
          <input
            v-model="fileToEdit.type"
            class="border p-2 mb-2 w-full"
          />
        </label>
        <button @click="updateFile" class="bg-blue-500 text-white p-2 mr-2">
          Save
        </button>
        <button
          @click="showFileEditDialog = false"
          class="bg-gray-500 text-white p-2"
        >
          Cancel
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from "vue";
import {
  getFolders,
  createFolder,
  updateFolder as updateFolderService,
  deleteFolder as deleteFolderService,
} from "@/services/folderService";
import {
  uploadFile,
  updateFile as updateFileService,
  deleteFile as deleteFileService,
  getFiles,
} from "@/services/fileService";

export default {
  setup() {
    const folders = ref([]);
    const files = ref([]);
    const flatFolders = ref([]);
    const folderName = ref("");
    const parentFolderId = ref("");
    const selectedFile = ref(null);
    const selectedFolderId = ref("");
    const showFolderEditDialog = ref(false);
    const showFileEditDialog = ref(false);
    const folderToEdit = ref(null);
    const fileToEdit = ref(null);

    const fetchFoldersAndFiles = async () => {
      const response = await getFolders();
      const foldersResult = response.folders ? response.folders : [];
      flatFolders.value = flattenFolders(foldersResult);
      folders.value = foldersResult;

      await fetchFiles();
    };
    const fetchFiles = async () => {
      const response = await getFiles();
      if (Array.isArray(response.files)) {
    // Filter out any invalid files
    files.value = response.files.filter((file) => file && typeof file === "object");
  }
    };

    onMounted(fetchFoldersAndFiles);

    // Function to create a new folder
    const createNewFolder = async () => {
      // Prepare the folder data
      const folderData = {
        name: folderName.value,
        parent_id: parentFolderId.value,
      };

      // Create the new folder
      const newFolder = await createFolder(folderData);

      // If the new folder has a parent, add it to the parent's children
      if (parentFolderId.value) {
        const parentFolder = findFolderById(
          folders.value,
          parentFolderId.value
        );
        if (parentFolder) {
          parentFolder.children.push(newFolder);
        }
      } else {
        // If the new folder doesn't have a parent, add it to the root level
        folders.value = [...folders.value, newFolder.folder];
      }

      // Update the flatFolders array and reset the form values
      flatFolders.value = flattenFolders(folders.value);
      folderName.value = "";
      parentFolderId.value = "";
    };

    // Function to handle file upload
    const handleFileUpload = (event) => {
      // Store the uploaded file
      selectedFile.value = event.target.files[0];
    };

    // Function to upload a new file
    const uploadNewFile = async () => {
      // If no file is selected, return
      if (!selectedFile.value) return;

      // Prepare the form data
      const formData = new FormData();
      formData.append("file", selectedFile.value);
      formData.append("name", selectedFile.value.name);
      formData.append("folder_id", selectedFolderId.value);
      formData.append("type", selectedFile.value.type);
console.log(formData);
      // Upload the file
      const newFile = await uploadFile(formData);
      // Add the new file to the files array and reset the form values
      files.value = [...files.value, newFile.file];
      selectedFile.value = null;
      selectedFolderId.value = "";
    };

    // Function to toggle a folder's open state
    const toggleFolder = (id) => {
      // Find the folder by id
      const folder = flatFolders.value.find((f) => f.id === id);

      // If the folder is found, toggle its open state
      if (folder) folder.open = !folder.open;
    };

    // Function to edit a folder
    const editFolder = (folder) => {
      // Store the folder to be edited and show the edit dialog
      folderToEdit.value = { ...folder };
      showFolderEditDialog.value = true;
    };

    // Function to update a folder
    const updateFolder = async () => {
      // Update the folder and fetch the updated list of folders and files
      await updateFolderService(folderToEdit.value.id, folderToEdit.value);
      await fetchFoldersAndFiles();

      // Hide the edit dialog
      showFolderEditDialog.value = false;
    };

    // Function to edit a file
    const editFile = (file) => {
      // Store the file to be edited and show the edit dialog
      fileToEdit.value = { ...file };
      showFileEditDialog.value = true;
    };

    // Function to update a file
    const updateFile = async () => {
      // Update the file and fetch the updated list of folders and files
      await updateFileService(fileToEdit.value.id, fileToEdit.value);
      await fetchFoldersAndFiles();

      // Hide the edit dialog
      showFileEditDialog.value = false;
    };

    // Function to delete a folder
    const deleteFolder = async (id) => {
      // Delete the folder
      await deleteFolderService(id);

      // Remove the deleted folder from the folders array and update the flatFolders array
      folders.value = folders.value.filter((folder) => folder.id !== id);
      flatFolders.value = flattenFolders(folders.value);
    };

    // Function to delete a file
    const deleteFile = async (id) => {
      // Delete the file
      await deleteFileService(id);

      // Remove the deleted file from the files array
      files.value = files.value.filter((file) => file.id !== id);
    };

    // Function to flatten the folders array
    const flattenFolders = (folders) => {
      let result = [];
      folders.forEach((folder) => {
        // Initialize the folder's open state and children array
        folder.open = false;
        folder.children = folder.children || [];

        // Add the folder to the result array
        result.push(folder);

        // If the folder has children, recursively flatten them and add to the result array
        if (folder.children.length) {
          result = result.concat(flattenFolders(folder.children));
        }
      });
      return result;
    };

    // Function to find a folder by id
    const findFolderById = (folders, id) => {
      for (const folder of folders) {
        // If the folder's id matches the given id, return the folder
        if (folder.id === id) return folder;

        // If the folder has children, recursively search them
        const child = findFolderById(folder.children || [], id);
        if (child) return child;
      }
      return null;
    };

    // Return the state and methods
    return {
      folders,
      files,
      flatFolders,
      folderName,
      parentFolderId,
      selectedFile,
      selectedFolderId,
      showFolderEditDialog,
      showFileEditDialog,
      folderToEdit,
      fileToEdit,
      createNewFolder,
      handleFileUpload,
      uploadNewFile,
      toggleFolder,
      editFolder,
      updateFolder,
      editFile,
      updateFile,
      deleteFolder,
      deleteFile,
    };
  },
};
</script>

<style scoped>
h2 {
  text-align: center;
  margin-bottom: 20px;
}

div {
  max-width: 800px;
  margin: 20px auto;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  text-align: left;
}

input[type="file"] {
  display: block;
  margin: 10px 0;
}

button {
  padding: 12px 20px;
  background-color: #42b983;
  border: none;
  border-radius: 5px;
  color: white;
  cursor: pointer;
  margin-top: 20px;
  margin-left: 20px; /* add left margin */
  font-size: 16px;
  transition: background-color 0.3s ease;
}

button:hover {
  background-color: #339966;
}

.button-margin {
  margin-right: 10px;
}

.delete-button {
  background-color: red;
  color: white;
}

select {
  margin-left: 10px;
}

ul {
  list-style-type: none;
  padding: 0;
}

li {
  margin-bottom: 10px;
}

li > span {
  cursor: pointer;
  color: #42b983;
}
table {
  border-collapse: collapse;
  width: 100%;
}

th,
td {
  border: 1px solid black;
  padding: 8px;
  text-align: left;
}
</style>

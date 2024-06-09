<template v-if="filteredItems && filteredItems.length">
  <div
    class="container mx-auto p-6 bg-gray-100 min-h-screen"
    @dragover.prevent
    @drop="handleDrop"
    style="
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    "
  >
    <h1 class="text-3xl font-bold mb-6 text-center">Welcome to Content Hub</h1>
    <br />

    <!-- Button to trigger options -->
    <div class="mb-4" style="width: 100%; max-width: 300px">
      <button
        @click="showOptions = true"
        class="bg-blue-500 text-white px-6 py-3 text-lg rounded hover:bg-blue-600 w-full"
        style="width: 100%; padding: 10px; font-size: 26px; cursor: pointer"
      >
        + New
      </button>
    </div>
    <br />
    <br />
    <!-- Options Dialog/Modal -->
    <div
      v-if="showOptions"
      class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-25"
    >
      <div class="bg-white p-4 rounded shadow-md">
        <!-- <h3 class="text-lg font-bold mb-4">Choose an Option:</h3> -->
        <button
          @click="
            showCreateDialog = true;
            showOptions = false;
          "
          class="block w-full text-left px-4 py-2 hover:bg-gray-100"
          style="width: 100%; padding: 10px; font-size: 16px; cursor: pointer"
        >
          Create Folder
        </button>
        <button
          @click="
            showUploadDialog = true;
            showOptions = false;
          "
          class="block w-full text-left px-4 py-2 hover:bg-gray-100"
          style="width: 100%; padding: 10px; font-size: 16px; cursor: pointer"
        >
          Upload File
        </button>
        <button
          @click="showOptions = false"
          class="absolute top-0 right-0 m-4 text-gray-600 hover:text-gray-800"
        >
          &times;
        </button>
      </div>
    </div>

    <!-- Create Folder Dialog/Modal -->
    <FolderForm
      v-if="showCreateDialog"
      @close="showCreateDialog = false"
      @folderCreated="handleFolderCreated"
      @createFolder="createNewFolder"
      :flat-folders="flatFolders"
      class="w-full h-full"
    />

    <!-- Upload File Dialog/Modal -->
    <FileUpload
      v-if="showUploadDialog"
      @close="showUploadDialog = false"
      @uploadFile="uploadNewFile"
      :flat-folders="flatFolders"
      class="w-full h-full"
    />
    <br />

    <!-- Folders and Files List Section -->
    <div
      class="w-full bg-white shadow-md rounded-lg overflow-x-auto"
      style="
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
      "
    >
      <!-- add a search icon -->
      <div style="text-align: center; width: 100%">
        <span
          class="mdi mdi-magnify absolute left-3 top-1/2 transform -translate-y-1/2"
          style="width: 40%; padding: 10px; font-size: 26px; cursor: pointer"
        ></span>
        <input
          v-model="searchTerm"
          class="rounded-full px-3"
          type="search"
          placeholder="Search in Drive"
          style="width: 40%; padding: 10px; font-size: 26px; cursor: pointer"
        />
      </div>

      <!-- Folders and files lists -->
      <table class="w-full table-auto">
        <thead
          style="width: 100%; padding: 10px; font-size: 30px; cursor: pointer"
        >
          <tr
            class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal shadow-md hover:bg-gray-100"
          >
            <th class="py-3 px-6 text-left cursor-pointer">Id</th>
            <th
              @click="sortBy('name')"
              class="py-3 px-6 text-left cursor-pointer"
            >
              Name
            </th>
            <th
              @click="sortBy('user_id')"
              class="py-3 px-6 text-left cursor-pointer"
            >
              User ID
            </th>
            <th
              @click="sortBy('parent_id')"
              class="py-3 px-6 text-left cursor-pointer"
            >
              Parent ID
            </th>
            <th
              @click="sortBy('type')"
              class="py-3 px-6 text-left cursor-pointer"
            >
              Type
            </th>
            <th class="py-3 px-6 text-center">Actions</th>
          </tr>
        </thead>
        <tbody
          class="text-gray-600 text-sm font-light"
          style="width: 100%; padding: 5px; font-size: 25px"
        >
          <tr
            v-for="item in filteredItems"
            :key="item.id"
            @click="item.type === 'folder' ? toggleFolder(item) : null"
            class="py-3 px-6 border-b border-gray-200 hover:bg-gray-100"
            @dragover.prevent
            @drop="handleDropOnItem(item, $event)"
          >
            <td class="py-3 px-6 text-left border border-gray-500">
              {{ item.id }}
            </td>
            <td class="py-3 px-6 text-left">{{ item.name }}</td>
            <td class="py-3 px-6 text-left">{{ item.user_id }}</td>
            <td class="py-3 px-6 text-left">
              {{ item.folder_id || item.parent_id || "--" }}
            </td>
            <td class="py-3 px-6 text-left">{{ item.type }}</td>
            <td class="py-3 px-6 text-center">
              <button
                v-if="item.type === 'folder'"
                @click.stop="editFolder(item)"
                class="text-blue-500 hover:text-blue-700"
                style="
                  width: 100%;
                  padding: 10px;
                  font-size: 16px;
                  cursor: pointer;
                "
              >
                Edit
              </button>
              <!-- Folder Edit Dialog -->

              <button
                v-if="item.type === 'file'"
                @click.stop="editFile(item)"
                class="text-blue-500 hover:text-blue-700"
                style="
                  width: 100%;
                  padding: 10px;
                  font-size: 16px;
                  cursor: pointer;
                "
              >
                Edit
              </button>
              <button
                @click.stop="
                  item.type === 'folder' || item.type === 'sub-folder'
                    ? deleteFolder(item.id)
                    : deleteFile(item.id)
                "
                class="ml-2 text-red-500 hover:text-red-700"
                style="
                  width: 100%;
                  padding: 10px;
                  font-size: 16px;
                  cursor: pointer;
                "
              >
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
        <h2 class="text-lg font-bold mb-2">Edit Folder</h2>
        <div class="mb-2">
          <label for="folderName" class="block font-bold mb-1"
            >Folder Name:</label
          >
          <input
            id="folderName"
            v-model="folderToEdit.name"
            class="border p-2 w-full"
            style="width: 100%; padding: 10px; font-size: 16px; cursor: pointer"
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
            style="width: 100%; padding: 10px; font-size: 16px; cursor: pointer"
          />
        </div>
        <button
          @click="updateFolder"
          class="bg-blue-500 text-white p-2 mr-2"
          style="width: 100%; padding: 10px; font-size: 16px; cursor: pointer"
        >
          Save
        </button>
        <br />
        <button
          @click="showFolderEditDialog = false"
          class="bg-gray-500 text-white p-2"
          style="width: 100%; padding: 10px; font-size: 16px; cursor: pointer"
        >
          Cancel
        </button>
      </div>
    </div>
    <progress max="100" :value="uploadProgress"></progress>
  </div>
</template>

<script>
import { ref, onMounted, computed, reactive, provide } from "vue";
import { useStore } from "vuex";
import { useToast } from "vue-toastification";
// import VueUploadComponent from "vue-upload-component";
import FolderForm from "@/components/FolderForm.vue";
import FileUpload from "@/components/FileUpload.vue";
// import RecentItems from '@/components/RecentItems.vue';
import {
  getFolders,
  getFolder as getFolderAPI,
  createFolder,
  updateFolder as updateFolderAPI,
  deleteFolder as deleteFolderAPI,
} from "@/services/folderService";
import {
  getFiles,
  uploadFile,
  updateFile as updateFileAPI,
  deleteFile as deleteFileAPI,
} from "@/services/fileService";

export default {
  components: {
    FolderForm,
    FileUpload,
    // VueUploadComponent,
    // RecentItems,
  },
  data() {
    return {
      showOptions: false,
      editingFolder: null, //
      // showCreateDialog: false,
      // showUploadDialog: false,
      newButtonOptions: [
        { name: "New Folder", link: "/my-drive", icon: "mdi-folder-plus" },
        { name: "File Upload", link: "/recent", icon: "mdi-file-upload" },
        { name: "Folder Upload", link: "/trash", icon: "mdi-folder-upload" },
      ],
      uploadProgress: 0,
      showProgress: false,
      progress: 0,
      // state: {
      //   recentItems: []
      //   // ...
      // }
    };
  },
  methods: {
    onFileUploaded(file) {
      console.log("File uploaded successfully:", file);
    },
    onUploading(progress) {
      this.showProgress = true;
      this.progress = progress;
    },
  },
  setup() {
    const store = useStore();

    const toast = useToast();

    const handleDrop = async (event) => {
      console.log("dropped");
      event.preventDefault();
      const files = event.dataTransfer.files;
      for (let i = 0; i < files.length; i++) {
        const fileData = new FormData();
        fileData.append("file", files[i]);
        fileData.append("name", files[i].name);
        fileData.append("type", files[i].type);
        await uploadNewFile(fileData);
      }
    };

    const handleDropOnItem = async (item, event) => {
      event.preventDefault();
      event.stopPropagation(); 
      const files = event.dataTransfer.files;
      for (let i = 0; i < files.length; i++) {
        const fileData = new FormData();
        fileData.append("file", files[i]);
        if (item.type === "folder") {
          fileData.append("folder_id", item.id);
          fileData.append("name", files[i].name);
          fileData.append("type", files[i].type);
        }
        await uploadNewFile(fileData);
      }
    };

    const showCreateDialog = ref(false);
    const showUploadDialog = ref(false);

    const searchTerm = ref("");
    const sortField = ref(null);
    const sortOrder = ref(null);

    const items = ref([]);
    const flatFolders = ref([]);
    // const folderName = ref("");
    // const parentFolderId = ref("");
    const flatItems = ref([]); // flatted items
    const folders = ref([]); // only folder list
    const files = ref([]); // only file list
    const sortKey = ref("name");
    // const editingFolder = ref(null);
    const showFolderEditDialog = ref(false);
    const folderToEdit = ref(null);

    const errorMessages = {
      400: "The request was invalid.",
      401: "You are not authorized to perform this action.",
      403: "You do not have permission to access this resource.",
      404: "The requested resource could not be found.",
      408: "The request took too long to complete.",
      500: "There was an internal server error.",
      502: "There was a problem with the gateway.",
      503: "The service is currently unavailable.",
      // Add more error codes/messages as needed
    };

    const rootItems = computed(() => {
      return flatItems.value.filter((item) => item.parent_id == null);
    });

    const filteredItems = computed(() => {
      let result = flatItems.value;

      if (searchTerm.value) {
        const lowerCaseSearchTerm = searchTerm.value.toLowerCase();
        result = result.filter((item) => {
          return (
            String(item.id).toLowerCase().includes(lowerCaseSearchTerm) ||
            item.name.toLowerCase().includes(lowerCaseSearchTerm) ||
            String(item.user_id).toLowerCase().includes(lowerCaseSearchTerm) ||
            String(item.parent_id)
              .toLowerCase()
              .includes(lowerCaseSearchTerm) ||
            item.type.toLowerCase().includes(lowerCaseSearchTerm)
          );
        });
      }

      // add sorting logic
      if (sortField.value) {
        const order = sortOrder.value === "asc" ? 1 : -1;
        result = result.sort((a, b) => {
          if (a[sortField.value] < b[sortField.value]) {
            return -1 * order;
          }
          if (a[sortField.value] > b[sortField.value]) {
            return 1 * order;
          }
          return 0;
        });
      }

      return result;
    });
    const state = reactive({
      recentItems: [],
      deletedItems: [],
    });
    provide("state", state);

    const handleFolderCreated = () => {
      showCreateDialog.value = false;
    };

    const fetchFoldersAndFiles = async () => {
      // fetch all the folders from backend
      const foldersResponse = await getFolders();
      folders.value = foldersResponse.folders || [];
      folders.value = folders.value.filter(
        (folder) => folder.parent_id === null
      );
      folders.value.forEach((folder) => (folder.type = "folder"));

      // fetch all files from backend
      const filesResponse = await getFiles();
      files.value = filesResponse.files || [];
      files.value = files.value.filter((file) => file.folder_id === null);
      files.value.forEach((file) => (file.type = "file"));

      // combine file and folder items
      items.value = [...folders.value, ...files.value];
      flatItems.value = flattenItems(items.value);

      // calculate flatFolders
      flatFolders.value = flattenItems(folders.value);
    };

    const createNewFolder = async (folderData) => {
      try {
        const newFolderResponse = await createFolder(folderData);
        const newFolder = newFolderResponse.folder;
        newFolder.type = "folder";
        folders.value.push(newFolder);
        items.value.push(newFolder);
        flatItems.value = flattenItems(items.value);
        flatFolders.value.push(newFolder);
        showCreateDialog.value = false;
        // add to recent items
        store.dispatch("addRecentItem", newFolder);

        toast.success("Folder created");
      } catch (error) {
        const userFriendlyMessage =
          errorMessages[error.status] || "An unknown error occurred.";
        toast.error(`Folder creation failed: ${userFriendlyMessage}`);
      }
    };

    const editFolder = (folder) => {
      // Store the folder to be edited and show the edit dialog
      folderToEdit.value = { ...folder };
      showFolderEditDialog.value = true;
    };

    // Function to update a folder
    const updateFolder = async () => {
      try {
        await updateFolderAPI(folderToEdit.value.id, folderToEdit.value);
        await fetchFoldersAndFiles();
        showFolderEditDialog.value = false;
        store.dispatch("addRecentItem", folderToEdit.value);
        toast.success("Folder updated");
      } catch (error) {
        const userFriendlyMessage =
          errorMessages[error.status] || "An unknown error occurred.";
        toast.error(`Failed to update folder: ${userFriendlyMessage}`);
      }
    };

    const deleteFolder = async (folderId) => {
      try {
        // use getFolderAPI to retrieve this folder from backend
        let response;
        try {
          response = await getFolderAPI(folderId);
          // handle the response data
        } catch (error) {
          // handle the error
          if (error.status === 404) {
            toast.error(`Folder with id ${folderId} not found.`);
            return;
          }
        }
        await deleteFolderAPI(folderId);
        await fetchFoldersAndFiles();
        store.dispatch("addDeletedItem", response);

        toast.success("Folder deleted");
      } catch (error) {
        const userFriendlyMessage =
          errorMessages[error.status] || "An unknown error occurred.";
        toast.error(`Failed to delete folder: ${userFriendlyMessage}`);
      }
    };

    // files upload and delete and edit methods
    const uploadNewFile = async (fileData) => {
      try {
        const newFileResponse = await uploadFile(fileData, (progress) => {
          this.uploadProgress = progress;
        });

        const newFile = newFileResponse.file;
        newFile.type = "file";
        files.value.push(newFile);
        items.value.push(newFile);
        flatItems.value = flattenItems(items.value);
        showUploadDialog.value = false;
        store.dispatch("addRecentItem", newFile);
        toast.success("File uploaded");
      } catch (error) {
        const userFriendlyMessage =
          errorMessages[error.status] || "An unknown error occurred.";
        toast.error(`Failed to upload file: ${userFriendlyMessage}`);
      }
    };

    const deleteFile = async (fileId) => {
      try {
        const deletedFile = files.value.find((file) => file.id === fileId);
        const response = await deleteFileAPI(fileId);
        console.log("response", response);
        if (response.status === 200 || response.status === 204) {
          // state.deletedItems.push(deletedFile);
          store.dispatch("addDeletedItem", deletedFile);
          await fetchFoldersAndFiles();
          toast.success("File deleted");
        } else {
          toast.error("Failed to delete file");
        }
      } catch (error) {
        toast.error("Failed to delete file");
      }
    };
    const editFile = async (file) => {
      //
      console.log("edit file", file.id);
      const newName = prompt("Enter new file name", file.name);
      if (newName && newName !== file.name) {
        const updatedFile = { ...file, name: newName };
        const updatedFileResponse = await updateFileAPI(file.id, updatedFile);
        const updatedFileData = updatedFileResponse.file;

        // update the file in the files list
        const index = files.value.findIndex((f) => f.id === file.id);
        if (index !== -1) {
          files.value[index] = updatedFileData;
          const itemIndex = items.value.findIndex((i) => i.id === file.id);
          if (itemIndex !== -1) {
            items.value[itemIndex] = updatedFileData;
            flatItems.value = flattenItems(items.value);
          }
        }
      }
    };

    const sortBy = (key) => {
      if (sortKey.value === key) {
        console.log("sort 1 key", key);
        sortOrder.value = -sortOrder.value; // sorting
      } else {
        console.log("sort 2 key", key);
        sortKey.value = key;
        sortOrder.value = 1; // default sorting
      }
    };

    const sortedAndFilteredItems = computed(() => {
      return filteredItems.value.slice().sort((a, b) => {
        if (a[sortKey.value] > b[sortKey.value]) {
          return sortOrder.value;
        } else if (a[sortKey.value] < b[sortKey.value]) {
          return -sortOrder.value;
        } else {
          return 0;
        }
      });
    });

    const toggleFolder = (folder) => {
      folder.open = !folder.open; // toggle open state
      console.log("items", items.value);
      flatItems.value = flattenItems(items.value); // update flat items
      console.log("flatItems", flatItems.value);
    };

    const flattenItems = (items) => {
      let result = [];
      items.forEach((item) => {
        result.push(item);
        if (item.type === "folder" && item.open) {
          //  get children and files
          let children = item.children || [];
          let files = item.files || [];

          // update children type to 'sub-folder'
          children = children.map((child) => ({
            ...child,
            type: "sub-folder",
          }));

          // update files type to 'sub-file'
          files = files.map((file) => ({ ...file, type: "sub-file" }));

          result = result.concat(children).concat(files);
        }
      });
      return result;
    };
    // provide('state', state);
    onMounted(fetchFoldersAndFiles);

    return {
      items,
      flatItems,
      folders,
      files,
      createNewFolder,
      uploadNewFile,
      deleteFolder,
      deleteFile,
      editFile,
      sortBy,
      sortedAndFilteredItems,
      toggleFolder,
      handleFolderCreated,
      flatFolders,
      showCreateDialog,
      showUploadDialog,
      searchTerm,
      filteredItems,
      rootItems,
      sortField,
      sortOrder,
      showFolderEditDialog,
      folderToEdit,
      editFolder,
      updateFolder,
      state,
      handleDrop,
      handleDropOnItem,
    };
  },
};
</script>

<style scoped>
@media screen and (min-width: 768px) {
  .container {
    /* set big padding for large screen */
    padding: 40px;
  }
}
tbody tr:hover {
  background-color: #f3f4f6 !important;
}
th,
td {
  padding: 3em;
}
.container {
  border: 2px dashed #ccc;
  border-radius: 5px;
  padding: 20px;
  text-align: center;
}

.container.dragging {
  border-color: #000;
}
.progress-container {
  width: 100%;
  height: 20px;
  background-color: #f0f0f0;
  margin-top: 10px;
}

.progress-bar {
  height: 100%;
  background-color: #007bff;
}
.button {
  width: 100%;
  padding: 10px;
  background-color: #42b983;
  border: none;
  color: white;
  font-size: 16px;
  cursor: pointer;
}
/* div {
  max-width: 1000px;
  margin: 20px auto;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  text-align: left;
} */

/* button {
  padding: 12px 20px;
  background-color: #9b9c9b;
  border: none;
  border-radius: 5px;
  color: white;
  cursor: pointer;
  margin-top: 20px;
  margin-left: 20px; 
  font-size: 16px;
  transition: background-color 0.3s ease;
} */
</style>

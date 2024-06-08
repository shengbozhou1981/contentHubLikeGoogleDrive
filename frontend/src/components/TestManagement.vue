<template>
  <div class="container mx-auto p-6 bg-gray-100 min-h-screen">
    <h2 class="text-3xl font-bold mb-6 text-center">File Manager</h2>

    <!-- Button to trigger options -->
    <div class="mb-4">
      <button
        @click="showOptions = true"
        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
      >
        + New
      </button>
    </div>

    <!-- Options Dialog/Modal -->
    <div
      v-if="showOptions"
      class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-25"
    >
      <div class="bg-white p-4 rounded shadow-md">
        <button
          @click="showOptions = false"
          class="absolute top-0 right-0 m-4 text-gray-600 hover:text-gray-800"
        >
          &times;
        </button>
        <h3 class="text-lg font-bold mb-4">Choose an Option:</h3>
        <button
          @click="
            showCreateDialog = true;
            showOptions = false;
          "
          class="block w-full text-left px-4 py-2 hover:bg-gray-100"
        >
          Create Folder
        </button>
        <button
          @click="
            showUploadDialog = true;
            showOptions = false;
          "
          class="block w-full text-left px-4 py-2 hover:bg-gray-100"
        >
          Upload File
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
    />

    <!-- Upload File Dialog/Modal -->
    <FileUpload
      v-if="showUploadDialog"
      @close="showUploadDialog = false"
      @uploadFile="uploadNewFile"
      :flat-folders="flatFolders"
    />
    <br />
    <!-- 添加一个搜索图标 -->
    <span
      class="mdi mdi-magnify absolute left-3 top-1/2 transform -translate-y-1/2"
    ></span>
    <!-- 添加一个输入框来输入搜索词 -->
    <input
      v-model="searchTerm"
      class="rounded-full px-3"
      type="search"
      placeholder="Search in Drive"
    />

    <!-- Folders and Files List Section -->
    <div
      class="bg-white shadow-md rounded-lg overflow-x-auto"
      style="display: flex; justify-content: center; align-items: center"
    >
      <table class="min-w-full table-auto border-collapse">
        <thead>
          <tr
            class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal shadow-md"
          >
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
        <tbody class="text-gray-600 text-sm font-light">
          <tr
            v-for="item in filteredItems"
            :key="item.id"
            @click="item.type === 'folder' ? toggleFolder(item) : null"
            class="border-b border-gray-200 hover:bg-gray-100"
          >
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
              >
                Edit
              </button>
              <button
                v-if="item.type === 'file'"
                @click.stop="editFile(item)"
                class="text-blue-500 hover:text-blue-700"
              >
                Edit
              </button>
              <button
                @click.stop="
                  item.type === 'folder'
                    ? deleteFolder(item.id)
                    : deleteFile(item.id)
                "
                class="ml-2 text-red-500 hover:text-red-700"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, computed } from "vue";
import FolderForm from "@/components/FolderForm.vue";
import FileUpload from "@/components/FileUpload.vue";
import {
  getFolders,
  createFolder,
  deleteFolder as deleteFolderAPI,
} from "@/services/folderService";
import {
  getFiles,
  uploadFile,
  updateFile,
  deleteFile as deleteFileAPI,
} from "@/services/fileService";

export default {
  components: {
    FolderForm,
    FileUpload,
  },
  data() {
    return {
      showOptions: false,
      // showCreateDialog: false,
      // showUploadDialog: false,
    };
  },
  methods: {},
  setup() {
    const showCreateDialog = ref(false);
    const showUploadDialog = ref(false);
    const searchTerm = ref("");
    const sortField = ref(null); // 添加这行
    const sortOrder = ref("asc"); // 添加这行
    const items = ref([]); // 包含文件夹和文件的列表
    const flatFolders = ref([]);
    // const folderName = ref("");
    // const parentFolderId = ref("");
    const flatItems = ref([]); // 扁平化后的列表，用于展示
    const folders = ref([]); // 仅文件夹列表
    const files = ref([]); // 仅文件列表
    const sortKey = ref("name");

    const filteredItems = computed(() => {
      let result = flatItems.value;

      if (searchTerm.value) {
        result = result.filter((item) => {
          // 根据你的需求修改这里的条件
          return (
            item.name.includes(searchTerm.value) ||
            String(item.user_id).includes(searchTerm.value) ||
            String(item.parent_id).includes(searchTerm.value) ||
            item.type.includes(searchTerm.value)
          );
        });
      }

      // 添加排序逻辑
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

    // const sortedItems = computed(() => {
    //   return flatItems.value.slice().sort((a, b) => {
    //     const result = a[sortKey.value] > b[sortKey.value] ? 1 : -1;
    //     return result * sortOrder.value;
    //   });
    // });

    const handleFolderCreated = () => {
      showCreateDialog.value = false;
    };

    const fetchFoldersAndFiles = async () => {
      const foldersResponse = await getFolders();

      folders.value = foldersResponse.folders || [];

      folders.value.forEach((folder) => (folder.type = "folder"));
      const filesResponse = await getFiles();
      files.value = filesResponse.files || [];
      files.value.forEach((file) => (file.type = "file"));

      // 将文件夹和文件合并到一个列表中
      items.value = [...folders.value, ...files.value];
      flatItems.value = flattenItems(items.value);

      // 计算 flatFolders
      flatFolders.value = flattenItems(folders.value);
    };

    const createNewFolder = async (folderData) => {
      const newFolderResponse = await createFolder(folderData);
      const newFolder = newFolderResponse.folder;
      newFolder.type = "folder";
      folders.value.push(newFolder);
      items.value.push(newFolder);
      flatItems.value = flattenItems(items.value);
      flatFolders.value.push(newFolder);
      showCreateDialog.value = false;
    };

    const uploadNewFile = async (fileData) => {
      console.log("upload file", fileData);
      const newFileResponse = await uploadFile(fileData);
      const newFile = newFileResponse.file;
      newFile.type = "file";
      files.value.push(newFile);
      items.value.push(newFile);
      flatItems.value = flattenItems(items.value);
      showUploadDialog.value = false;
    };

    const deleteFolder = async (folderId) => {
      console.log("delete folder", folderId);
      await deleteFolderAPI(folderId);
      folders.value = folders.value.filter((folder) => folder.id !== folderId);
      items.value = items.value.filter((item) => item.id !== folderId);
      flatItems.value = flattenItems(items.value);
    };

    const deleteFile = async (fileId) => {
      await deleteFileAPI(fileId);
      files.value = files.value.filter((file) => file.id !== fileId);
      items.value = items.value.filter((item) => item.id !== fileId);
      flatItems.value = flattenItems(items.value);
    };

    const editFile = async (file) => {
      // 模拟文件编辑的逻辑，例如弹出一个编辑框来修改文件名
      const newName = prompt("Enter new file name", file.name);
      if (newName && newName !== file.name) {
        const updatedFile = { ...file, name: newName };
        const updatedFileResponse = await updateFile(updatedFile);
        const updatedFileData = updatedFileResponse.file;

        // 更新前端文件列表
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
        sortOrder.value = -sortOrder.value; // 切换排序顺序
      } else {
        sortKey.value = key;
        sortOrder.value = 1; // 默认升序
      }
    };

    const sortedItems = computed(() => {
      return flatItems.value.slice().sort((a, b) => {
        const result = a[sortKey.value] > b[sortKey.value] ? 1 : -1;
        return result * sortOrder.value;
      });
    });

    const toggleFolder = (folder) => {
      folder.open = !folder.open; // 切换文件夹的展开状态
      flatItems.value = flattenItems(items.value); // 更新扁平化列表
    };

    const flattenItems = (items) => {
      let result = [];
      items.forEach((item) => {
        result.push(item);
        if (item.type === "folder" && item.open) {
          // 如果是文件夹且处于展开状态，则将其子项添加到列表中
          result = result.concat(item.children || []);
        }
      });
      return result;
    };

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
      editFile, // 确保在返回对象中包括 editFile
      sortBy,
      sortedItems,
      toggleFolder,
      handleFolderCreated,
      flatFolders,
      showCreateDialog,
      showUploadDialog,
      searchTerm,
      filteredItems,
      sortField, // 添加这行
      sortOrder, // 添加这行
    };
  },
};
</script>

<style scoped>
.container {
  /* 增加一些内边距 */
  padding: 2em;
}

h2 {
  /* 增加一些边距 */
  margin-bottom: 1em;
}

.mb-6 {
  /* 增加一些边距 */
  margin-bottom: 1em;
}

.bg-white {
  /* 增加一些内边距 */
  padding: 1em;
}

table {
  /* 增加一些边距 */
  margin: 1em 0;
}

th,
td {
  /* 增加一些内边距 */
  padding: 3em;
}
</style>

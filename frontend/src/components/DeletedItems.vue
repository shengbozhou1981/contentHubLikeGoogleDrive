<template>
      <div
        class="bg-white shadow-md rounded-lg overflow-x-auto"
        style="display: flex; justify-content: center; flex-direction: column;align-items: center"
      >
      <h2>Deleted Folders and Files recently</h2>
        <table class="min-w-full table-auto">
          <thead>
            <tr
              class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal shadow-md hover:bg-gray-100"
            >
              <th class="py-3 px-6 text-left cursor-pointer">Id</th>
              <th
                class="py-3 px-6 text-left cursor-pointer"
              >
                Name
              </th>
              <th
                class="py-3 px-6 text-left cursor-pointer"
              >
                User ID
              </th>
              <th
                class="py-3 px-6 text-left cursor-pointer"
              >
                Parent ID
              </th>
              <th
                class="py-3 px-6 text-left cursor-pointer"
              >
                Type
              </th>
            </tr>
          </thead>
          <tbody class="text-gray-600 text-sm font-light">
            <tr
              v-for="item in deletedItems"
              :key="item.id"
              class="py-3 px-6 border-b border-gray-200 hover:bg-gray-100"
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
            </tr>
          </tbody>
        </table>
      </div> 
  </template>
  
  <script>
  import { mapGetters } from 'vuex';
  export default {
      name: 'DeletedItems',
    computed: {
      ...mapGetters(['deletedItems']),
    },
  
  };
  </script>
  <style scoped>
  tbody tr:hover {
    background-color: #f3f4f6 !important; 
  }
  th,
  td {
    padding: 3em;
  }
  div {
    max-width: 1000px;
    margin: 20px auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    text-align: left;
  }
  
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
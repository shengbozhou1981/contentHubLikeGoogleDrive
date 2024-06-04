// src/services/folderService.js
import axios from 'axios';

export async function createFolder(folderData) {
    const response = await axios.post('/api/folders', folderData);
    return response.data;
}

export async function getFolders() {
    const response = await axios.get('/api/folders');
    return response.data;
}

export async function updateFolder(id, folderData) {
    const response = await axios.put(`/api/folders/${id}`, folderData);
    return response.data;
}

export async function deleteFolder(id) {
    const response = await axios.delete(`/api/folders/${id}`);
    return response.data;
}

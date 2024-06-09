// src/services/folderService.js
import axios from 'axios';

export async function createFolder(folderData) {
    try {
        const response = await axios.post('/api/folders', folderData);
        return response.data;
    } catch (error) {
        return { error };
    }
}

export async function getFolders() {
    try {
        const response = await axios.get('/api/folders');
        return response.data;
    } catch (error) {
        return { error };
    }
}

export async function updateFolder(id, folderData) {
    try {
        const response = await axios.put(`/api/folders/${id}`, folderData);
        return response.data;
    } catch (error) {
        return { error };
    }
}

export async function deleteFolder(id) {
    try {
        const response = await axios.delete(`/api/folders/${id}`);
        return response.data;
    } catch (error) {
        return { error };
    }
}

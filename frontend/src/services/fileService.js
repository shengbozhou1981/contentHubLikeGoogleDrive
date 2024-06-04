// src/services/fileService.js
import axios from 'axios';

export async function uploadFile(formData) {
    const response = await axios.post('/api/files', formData);
    return response.data;
  }

export async function getFiles() {
    try {
    const response = await axios.get('/api/files');
    console.log("get files response: "+ response.data);
    return response.data;
  } catch (error) {
    console.error('get files error:', error);
    throw error;
  }
}

export async function updateFile(id, fileData) {
    const response = await axios.post(`/api/files/${id}`, fileData);
    return response.data;
}

export async function deleteFile(id) {
    const response = await axios.delete(`/api/files/${id}`);
    console.log("delete file response: "+ response.data);
    return response.data;
}
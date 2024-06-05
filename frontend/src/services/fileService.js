// src/services/fileService.js
import axios from 'axios';

export async function uploadFile(formData) {
    const response = await axios.post('/api/files', formData);
    return response.data;
  }

export async function getFiles() {
    try {
    const response = await axios.get('/api/files');
    return response.data;
  } catch (error) {
    console.error('get files error:', error);
    throw error;
  }
}

export async function updateFile(id, fileData) {
    const response = await axios.put(`/api/files/${id}`, fileData);
    return response.data;
}

export async function deleteFile(id) {
  console.log("get into deleteFile method");
    const response = await axios.delete(`/api/files/${id}`);
    console.log("delete file response: "+ response.data);
    return response.data;
}

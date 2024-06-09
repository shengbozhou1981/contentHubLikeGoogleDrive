// src/services/fileService.js
import axios from 'axios';

export async function uploadFile(formData) {
  try {
    const response = await axios.post('/api/files', formData, {
      onUploadProgress: function(progressEvent) {
        self.uploadProgress = Math.round((progressEvent.loaded * 100) / progressEvent.total);
      }
    });
    return response.data;
  } catch (error) {
    return { error };
  }
}

export async function getFiles() {
    try {
    const response = await axios.get('/api/files');
    return response.data;
  } catch (error) {
    return { error };
  }
}

export async function updateFile(id, fileData) {
  try {
      const response = await axios.put(`/api/files/${id}`, fileData);
      return response.data;
  } catch (error) {
      console.error(`Failed to update file: ${error}`);
      return { error };
  }
}

export async function deleteFile(id) {
  try {
      console.log("get into deleteFile method");
      const response = await axios.delete(`/api/files/${id}`);
      console.log("delete file response: "+ response.data);
      return response.data;
  } catch (error) {
      console.error(`Failed to delete file: ${error}`);
      return { error };
  }
}

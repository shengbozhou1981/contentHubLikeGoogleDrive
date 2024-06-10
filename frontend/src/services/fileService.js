// src/services/fileService.js
import axios from 'axios';

export async function uploadFile(formData, onProgress, onSuccess, onError) {
  try {
    let lastUpdateTime = Date.now();
    const response = await axios.post('/api/files', formData, {
      onUploadProgress: (progressEvent) => {
        const now = Date.now();
        if (now - lastUpdateTime > 500) { // Only update progress every 500ms
          const percent = Math.round((progressEvent.loaded * 100) / progressEvent.total);
          onProgress({ percent });
          lastUpdateTime = now;
        }
      }
    }).catch(error => {
      if (axios.isCancel(error)) {
        onError('Upload canceled');
      } else {
        onError(error);
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
      const response = await axios.delete(`/api/files/${id}`);
      return response;
  } catch (error) {
      console.error(`Failed to delete file: ${error}`);
      return { error };
  }
}

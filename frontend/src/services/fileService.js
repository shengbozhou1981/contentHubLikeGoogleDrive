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
    // const response = await axios.post('/api/files', formData, {
    //   onUploadProgress: function(progressEvent) {
    //     const uploadProgress = Math.round((progressEvent.loaded * 100) / progressEvent.total);
    //     // 调用传入的 onProgressCallback 函数，并传递上传进度
    //     onProgressCallback(uploadProgress);
    //   }
    // });
    // return response.data;  
  } catch (error) {
    console.error(error);
  }
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

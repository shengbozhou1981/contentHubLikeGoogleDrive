// src/services/authService.js
import axios from 'axios';

axios.defaults.withCredentials = true;
axios.defaults.baseURL = 'http://localhost:8000';

export async function login(email, password) {
    const response = await axios.post('/api/login', { email, password });
    return response.data;
}

export async function register(name, email, password) {
    const response = await axios.post('/api/register', { name, email, password });
    return response.data;
}

export async function logout() {
    const response = await axios.post('/logout');
    return response.data;
}

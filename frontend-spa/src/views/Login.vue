<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../services/api'

const router = useRouter()

const username = ref('')
const password = ref('')

const login = async () => {
  try {
    const response = await api.post(
      '/login',
      new URLSearchParams({
        username: username.value,
        password: password.value
      }),
      {
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        }
      }
    )

    if (response.data.token) {
      localStorage.setItem(
        'token',
        response.data.token
      )

      router.push('/dashboard')
    } else {
      alert('Login gagal')
    }

  } catch (error) {
    console.error(error)
    alert('Username atau password salah')
  }
}
</script>

<template>
  <div class="login-page">

    <div class="login-card">

      <h1>📚 E-Library</h1>

      <p>
        Selamat datang di sistem
        manajemen perpustakaan digital.
      </p>

      <input
        v-model="username"
        class="form-control mb-3"
        placeholder="Username"
      >

      <input
        v-model="password"
        type="password"
        class="form-control mb-4"
        placeholder="Password"
      >

      <button
        @click="login"
        class="btn-login"
      >
        Login Administrator
      </button>

    </div>

  </div>
</template>

<style scoped>
.login-page {
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;

  background:
    linear-gradient(
      135deg,
      #ffe5ec,
      #f8edff,
      #dbeafe
    );
}

.login-card {
  width: 450px;
  background: rgba(255,255,255,.9);

  padding: 50px;
  border-radius: 35px;

  box-shadow:
    0 20px 50px rgba(0,0,0,.08);

  text-align: center;
}

.login-card h1 {
  font-size: 42px;
  font-weight: 700;
  color: #374151;
  margin-bottom: 15px;
}

.login-card p {
  color: #6b7280;
  margin-bottom: 35px;
}

.form-control {
  border-radius: 15px;
  padding: 14px;
}

.btn-login {
  width: 100%;
  border: none;

  padding: 15px;
  border-radius: 15px;

  color: white;
  font-weight: 600;

  background:
    linear-gradient(
      90deg,
      #cdb4db,
      #a2d2ff
    );

  transition: .3s;
}

.btn-login:hover {
  transform: translateY(-2px);
  opacity: .95;
}
</style>
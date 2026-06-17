<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../services/api'

const buku = ref([])
const kategori = ref([])

const getBuku = async () => {
  try {
    const response = await fetch('http://localhost:8080/buku')
    buku.value = await response.json()
  } catch (error) {
    console.error(error)
  }
}

const getKategori = async () => {
  try {
    const response = await fetch('http://localhost:8080/kategori')
    kategori.value = await response.json()
  } catch (error) {
    console.error(error)
  }
}

const pinjamBuku = async (item) => {
  const nama = prompt(
    'Masukkan nama peminjam'
  )

  if (!nama) return

  try {
    await api.post(
      '/peminjaman',
      {
        nama_peminjam: nama,
        buku_id: item.id,
        tanggal_pinjam:
          new Date()
            .toISOString()
            .split('T')[0],
        status: 'Menunggu'
      }
    )

    alert(
      'Permintaan peminjaman berhasil!'
    )

  } catch (error) {
    console.error(error)
    alert(
      'Gagal mengajukan peminjaman'
    )
  }
}

onMounted(() => {
  getBuku()
  getKategori()
})
</script>

<template>
  <div class="container-fluid py-5 px-5">

<div class="text-center mb-5">
  <h1 class="hero-title">
  ✨ Selamat Datang di E-Library
  </h1>

  <p class="hero-subtitle">
    Temukan berbagai koleksi buku digital
    untuk menambah wawasan dan pengetahuan.
  </p>

  <router-link
    to="/login"
    class="btn btn-primary btn-lg mt-3"
  >
    Login Administrator
  </router-link>
</div>

<div class="row mb-5">

  <div class="col-md-6 mb-3">
    <div class="card shadow-sm border-0 p-4 text-center">
      <h5>Total Buku</h5>

      <h1 class="fw-bold text-primary">
        {{ buku.length }}
      </h1>
    </div>
  </div>

  <div class="col-md-6 mb-3">
    <div class="card shadow-sm border-0 p-4 text-center">
      <h5>Total Kategori</h5>

      <h1 class="fw-bold text-success">
        {{ kategori.length }}
      </h1>
    </div>
  </div>

</div>

<div class="mb-4">
  <h2 class="fw-bold">
    Koleksi Buku
  </h2>

  <p class="text-muted">
    Daftar buku yang tersedia di perpustakaan.
  </p>
</div>

<div class="row">

  <div
    class="col-lg-3 col-md-4 col-sm-6 mb-4"
    v-for="item in buku"
    :key="item.id"
  >
    <div
      class="card buku-card h-100 shadow-sm border-0"
    >

      <div class="card-body text-center d-flex flex-column justify-content-between">

        <div>

          <div class="fs-1 mb-3">
            📚
          </div>

          <h5 class="fw-bold">
            {{ item.judul }}
          </h5>

          <p class="text-muted">
            {{ item.penulis }}
          </p>

        </div>

        <div>

          <span
            class="badge bg-primary mb-3"
          >
            Stok: {{ item.stok }}
          </span>

          <br>

          <button
            class="btn btn-success btn-sm px-4"
            @click="pinjamBuku(item)"
            :disabled="item.stok <= 0"
          >
            📖 Pinjam
          </button>

        </div>

      </div>

    </div>
  </div>

</div>


  </div>
</template>

<style scoped>
.container-fluid {
  min-height: 100vh;

  background: linear-gradient(
    135deg,
    #ffd6e8 0%,
    #f3d9fa 30%,
    #dbeafe 70%,
    #c7f9ff 100%
  );
}

.card {
  border-radius: 20px !important;
  background: rgba(255, 255, 255, 0.9) !important;
  backdrop-filter: blur(10px);
}

.buku-card {
  transition: all 0.3s ease;
  cursor: pointer;
  border-radius: 20px !important;
  overflow: hidden;
}

.buku-card:hover {
  transform: translateY(-10px) scale(1.03);
  box-shadow: 0 20px 40px rgba(0,0,0,0.15) !important;
}

.display-3 {
  color: #374151;
}

.lead {
  color: #6b7280;
}

.btn-primary {
  border-radius: 12px;
  padding: 12px 24px;
}

.badge {
  font-size: 14px;
  padding: 8px 12px;
  border-radius: 10px;
}

.hero-title {
  font-size: 4rem;
  font-weight: 700;
  color: #374151;
  margin-bottom: 20px;
}

.hero-subtitle {
  font-size: 1.2rem;
  color: #6b7280;
  font-style: italic;
}

.hero-title {
  font-family: "Times New Roman", serif;
  font-style: italic;
  letter-spacing: 1px;
}

.buku-card {
  border-radius: 25px;
  transition: .3s;
}

.buku-card:hover {
  transform: translateY(-8px);
}

.btn-success {
  background: #a2d2ff;
  border: none;
  border-radius: 15px;
  color: #374151;
  font-weight: 600;
}

.btn-success:hover {
  background: #8ecae6;
}

.btn-success:disabled {
  background: #d1d5db;
  color: #6b7280;
}

</style>

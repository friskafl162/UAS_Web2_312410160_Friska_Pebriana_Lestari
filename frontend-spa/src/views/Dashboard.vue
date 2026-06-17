<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../services/api'

const router = useRouter()
const menu = ref('dashboard')
const kategori = ref([])
const buku = ref([])
const peminjaman = ref([])

const judul = ref('')
const penulis = ref('')
const stok = ref('')
const kategori_id = ref('')
const editId = ref(null)

const logout = () => {
  localStorage.removeItem('token')
  router.push('/login')
}

const getBuku = async () => {
  try {
    const response = await api.get('/buku')
    buku.value = response.data

    console.log('BUKU:', buku.value)
  } catch (error) {
    console.error(error)
  }
}

const tambahBuku = async () => {
  try {
    const response = await api.post(
      '/buku',
      new URLSearchParams({
        judul: judul.value,
        penulis: penulis.value,
        stok: stok.value,
        kategori_id: kategori_id.value
      }),
      {
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        }
      }
    )

    if (response.status === 200 || response.status === 201) {
      alert('Buku berhasil ditambahkan')

      judul.value = ''
      penulis.value = ''
      stok.value = ''
      kategori_id.value = ''

      getBuku()
    } else {
      alert('Gagal menambahkan buku')
    }

  } catch (error) {
    console.error(error)
    alert('Gagal menambahkan buku')
  }
}

const editBuku = (item) => {
  editId.value = item.id

  judul.value = item.judul
  penulis.value = item.penulis
  stok.value = item.stok
  kategori_id.value = item.kategori_id
}

const updateBuku = async () => {
  try {
    const response = await api.put(
      `/buku/${editId.value}`,
      new URLSearchParams({
        judul: judul.value,
        penulis: penulis.value,
        stok: stok.value,
        kategori_id: kategori_id.value
      }),
      {
        headers: {
          'Content-Type':
            'application/x-www-form-urlencoded'
        }
      }
    )

    if (
      response.status === 200 ||
      response.status === 201
    ) {
      alert('Buku berhasil diupdate')

      editId.value = null

      judul.value = ''
      penulis.value = ''
      stok.value = ''
      kategori_id.value = ''

      getBuku()
    }

  } catch (error) {
    console.error(error)
    alert('Gagal update buku')
  }
}

const getPeminjaman = async () => {
  try {
    const response = await api.get(
      '/peminjaman'
    )

    peminjaman.value =
      response.data

  } catch (error) {
    console.error(error)
  }
}

const terimaPeminjaman = async (id) => {
  await api.put(
    `/peminjaman/${id}`,
    {
      status: 'Dipinjam'
    }
  )

  getPeminjaman()
}

const selesaiPeminjaman = async (id) => {
  await api.put(
    `/peminjaman/${id}`,
    {
      status: 'Dikembalikan'
    }
  )

  getPeminjaman()
}

const hapusBuku = async (id) => {
  try {
    const konfirmasi = confirm(
      'Yakin ingin menghapus buku?'
    )

    if (!konfirmasi) return

    const response = await api.delete(
      `/buku/${id}`
    )

    if (response.status === 200) {
      alert('Buku berhasil dihapus')
      getBuku()
    }

  } catch (error) {
    console.error(error)
    alert('Gagal menghapus buku')
  }
}

const getKategori = async () => {
  try {
    const response = await api.get('/kategori')
    kategori.value = response.data

    console.log('KATEGORI:', kategori.value)
  } catch (error) {
    console.error(error)
  }
}

onMounted(() => {
  getBuku()
  getKategori()
  getPeminjaman()
})
</script>

<template>
  <div class="min-h-screen bg-gray-50 py-6 px-4 sm:px-6 lg:px-8 font-sans">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row gap-6">

      <div class="w-full md:w-64 bg-white rounded-2xl shadow-sm p-5 flex flex-col justify-between border border-gray-100 shrink-0 min-h-[400px] md:min-h-[calc(100vh-3rem)]">
        <div>
          <h2 class="text-2xl font-bold text-gray-800 mb-8 flex items-center gap-2">
            <span>📚</span> E-Library
          </h2>

          <nav class="space-y-2">
            <div
              class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium cursor-pointer transition-all duration-200"
              :class="menu === 'dashboard' ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
              @click="menu = 'dashboard'"
            >
              <span class="text-lg">📊</span> Dashboard
            </div>

            <div
              class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium cursor-pointer transition-all duration-200"
              :class="menu === 'buku' ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
              @click="menu = 'buku'"
            >
              <span class="text-lg">📚</span> Kelola Buku
            </div>

            <div
              class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium cursor-pointer transition-all duration-200"
              :class="menu === 'peminjaman' ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
              @click="menu = 'peminjaman'"
            >
              <span class="text-lg">📋</span> Peminjaman
            </div>
          </nav>
        </div>

        <button
          @click="logout"
          class="mt-8 w-full flex items-center justify-center gap-2 px-4 py-3 bg-red-50 text-red-600 hover:bg-red-100 rounded-xl font-medium transition-colors duration-200"
        >
          🚪 Logout
        </button>
      </div>

      <div class="flex-1">
        <div class="bg-white rounded-2xl shadow-sm p-6 md:p-8 border border-gray-100 min-h-full">

          <div v-if="menu === 'dashboard'" class="space-y-6">
            <div>
              <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                Dashboard Administrator
              </h1>
              <p class="text-gray-500 mt-1">
                Selamat datang di sistem E-Library.
              </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="flex items-center gap-5 p-6 bg-gradient-to-r from-blue-50 to-indigo-50/50 rounded-2xl border border-blue-100/50">
                <div class="w-14 h-14 bg-blue-500 rounded-xl flex items-center justify-center text-2xl shadow-md shadow-blue-200 text-white">
                  📚
                </div>
                <div>
                  <h5 class="text-sm font-semibold text-blue-800 uppercase tracking-wider">Total Buku</h5>
                  <h1 class="text-3xl font-black text-gray-900 my-0.5">
                    {{ buku.length }}
                  </h1>
                  <p class="text-xs text-blue-600/80 font-medium">Jumlah buku yang tersedia</p>
                </div>
              </div>

              <div class="flex items-center gap-5 p-6 bg-gradient-to-r from-purple-50 to-pink-50/50 rounded-2xl border border-purple-100/50">
                <div class="w-14 h-14 bg-purple-500 rounded-xl flex items-center justify-center text-2xl shadow-md shadow-purple-200 text-white">
                  🏷️
                </div>
                <div>
                  <h5 class="text-sm font-semibold text-purple-800 uppercase tracking-wider">Total Kategori</h5>
                  <h1 class="text-3xl font-black text-gray-900 my-0.5">
                    {{ kategori.length }}
                  </h1>
                  <p class="text-xs text-purple-600/80 font-medium">Jumlah kategori buku</p>
                </div>
              </div>
            </div>

            <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100">
              <h3 class="text-lg font-bold text-gray-800 mb-2">
                ✨ Selamat Datang, Administrator
              </h3>
              <p class="text-gray-600 text-sm leading-relaxed">
                Saat ini terdapat <strong class="text-gray-900 font-semibold">{{ buku.length }}</strong> buku dan <strong class="text-gray-900 font-semibold">{{ kategori.length }}</strong> kategori yang sedang aktif dikelola.
              </p>
            </div>
          </div>

          <div v-if="menu === 'buku'" class="space-y-8">
            <div>
              <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                Kelola Buku
              </h1>
            </div>

            <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 max-w-xl">
              <h4 class="text-lg font-bold text-gray-800 mb-4">
                {{ editId === null ? '➕ Tambah Buku' : '✏️ Edit Buku' }}
              </h4>

              <div class="space-y-3">
                <input
                  v-model="judul"
                  class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all text-sm"
                  placeholder="Judul Buku"
                >
                <input
                  v-model="penulis"
                  class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all text-sm"
                  placeholder="Penulis"
                >
                <input
                  v-model="stok"
                  class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all text-sm"
                  placeholder="Stok"
                >
                <input
                  v-model="kategori_id"
                  class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-gray-800 placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all text-sm"
                  placeholder="Kategori ID"
                >
              </div>

              <button
                v-if="editId === null"
                @click="tambahBuku"
                class="mt-5 w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl shadow-sm hover:shadow transition-all duration-200 text-sm"
              >
                Tambah Buku
              </button>
              <button
                v-else
                @click="updateBuku"
                class="mt-5 w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 px-4 rounded-xl shadow-sm hover:shadow transition-all duration-200 text-sm"
              >
                Update Buku
              </button>
            </div>

            <div class="border border-gray-100 rounded-2xl overflow-hidden shadow-sm">
              <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                <h4 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                  <span>📖</span> Data Buku
                </h4>
              </div>

              <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                  <thead>
                    <tr class="bg-gray-50/50 text-gray-500 text-xs uppercase font-bold tracking-wider border-b border-gray-100">
                      <th class="px-6 py-3.5">ID</th>
                      <th class="px-6 py-3.5">Judul</th>
                      <th class="px-6 py-3.5">Penulis</th>
                      <th class="px-6 py-3.5">Stok</th>
                      <th class="px-6 py-3.5 text-center">Aksi</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    <tr
                      v-for="item in buku"
                      :key="item.id"
                      class="hover:bg-gray-50/80 transition-colors"
                    >
                      <td class="px-6 py-4 font-medium text-gray-500">#{{ item.id }}</td>
                      <td class="px-6 py-4 font-semibold text-gray-900">{{ item.judul }}</td>
                      <td class="px-6 py-4 text-gray-600">{{ item.penulis }}</td>
                      <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                          {{ item.stok }} pcs
                        </span>
                      </td>
                      <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                          <button
                            @click="editBuku(item)"
                            class="px-3 py-1.5 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-lg text-xs font-semibold transition-colors"
                          >
                            Edit
                          </button>
                          <button
                            @click="hapusBuku(item.id)"
                            class="px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-xs font-semibold transition-colors"
                          >
                            Hapus
                          </button>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <div v-if="menu === 'peminjaman'" class="space-y-6">
            <div>
              <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight flex items-center gap-2">
                <span>📋</span> Data Peminjaman
              </h1>
              <p class="text-gray-500 mt-1">
                Kelola data peminjaman buku.
              </p>
            </div>

            <div class="flex items-center gap-5 p-5 bg-gradient-to-r from-blue-50 to-sky-50/50 rounded-2xl border border-blue-100/50 max-w-sm">
              <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center text-xl shadow-md shadow-blue-200 text-white">
                📚
              </div>
              <div>
                <h5 class="text-xs font-semibold text-blue-800 uppercase tracking-wider">Total Peminjaman</h5>
                <h1 class="text-2xl font-black text-gray-900">
                  {{ peminjaman.length }}
                </h1>
                <p class="text-[11px] text-blue-600/80 font-medium">Jumlah seluruh transaksi</p>
              </div>
            </div>

            <div class="border border-gray-100 rounded-2xl overflow-hidden shadow-sm">
              <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                  <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase font-bold tracking-wider border-b border-gray-100">
                      <th class="px-6 py-4">ID</th>
                      <th class="px-6 py-4">Nama Peminjam</th>
                      <th class="px-6 py-4">Buku</th>
                      <th class="px-6 py-4">Tanggal</th>
                      <th class="px-6 py-4">Status</th>
                      <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    <tr
                      v-for="item in peminjaman"
                      :key="item.id"
                      class="hover:bg-gray-50/80 transition-colors"
                    >
                      <td class="px-6 py-4 font-medium text-gray-500">#{{ item.id }}</td>
                      <td class="px-6 py-4 font-semibold text-gray-900">{{ item.nama_peminjam }}</td>
                      <td class="px-6 py-4 text-gray-600">{{ item.judul }}</td>
                      <td class="px-6 py-4 text-gray-500 text-xs">{{ item.tanggal_pinjam }}</td>
                      <td class="px-6 py-4">
                        <span
                          v-if="item.status === 'Menunggu'"
                          class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100"
                        >
                          Menunggu
                        </span>
                        <span
                          v-else-if="item.status === 'Dipinjam'"
                          class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100"
                        >
                          Dipinjam
                        </span>
                        <span
                          v-else
                          class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100"
                        >
                          Dikembalikan
                        </span>
                      </td>
                      <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center">
                          <button
                            v-if="item.status === 'Menunggu'"
                            @click="terimaPeminjaman(item.id)"
                            class="px-4 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold transition-colors shadow-sm"
                          >
                            Terima
                          </button>

                          <button
                            v-if="item.status === 'Dipinjam'"
                            @click="selesaiPeminjaman(item.id)"
                            class="px-4 py-1.5 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-xs font-semibold transition-colors shadow-sm"
                          >
                            Selesai
                          </button>

                          <span
                            v-if="item.status === 'Dikembalikan'"
                            class="text-emerald-600 font-bold text-xs bg-emerald-50/50 px-3 py-1 rounded-lg border border-emerald-100"
                          >
                            ✓ Selesai
                          </span>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

          </div>
        </div>
      </div>

    </div>
  </div>
</template>


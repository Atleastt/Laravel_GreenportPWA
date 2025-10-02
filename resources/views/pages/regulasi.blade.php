<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Regulasi & Standar') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="regulasiApp()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Header Info -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <div class="flex items-center space-x-4">
                    <div class="text-4xl">📋</div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Pusat Regulasi & Standar Audit</h3>
                        <p class="text-gray-600">Akses lengkap ke semua regulasi, standar, dan template audit yang diperlukan</p>
                    </div>
                </div>
            </div>

            <!-- Search & Filter -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" 
                               x-model="searchQuery"
                               @input="searchRegulasi()"
                               placeholder="Cari regulasi, standar, atau kata kunci..."
                               class="w-full border-gray-300 rounded-md">
                    </div>
                    <div class="flex space-x-2">
                        <select x-model="categoryFilter" @change="filterRegulasi()" class="border-gray-300 rounded-md">
                            <option value="">Semua Kategori</option>
                            <option value="lingkungan">Lingkungan</option>
                            <option value="iso">ISO Standards</option>
                            <option value="pelabuhan">Regulasi Pelabuhan</option>
                            <option value="keselamatan">Keselamatan Kerja</option>
                            <option value="kualitas">Manajemen Kualitas</option>
                        </select>
                        <select x-model="typeFilter" @change="filterRegulasi()" class="border-gray-300 rounded-md">
                            <option value="">Semua Jenis</option>
                            <option value="undang-undang">Undang-Undang</option>
                            <option value="peraturan-pemerintah">PP</option>
                            <option value="peraturan-menteri">Permen</option>
                            <option value="iso-standard">ISO Standard</option>
                            <option value="template">Template</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Category Tabs -->
            <div class="bg-white rounded-lg shadow-lg mb-6">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex overflow-x-auto">
                        <button @click="activeCategory = 'all'" 
                                :class="activeCategory === 'all' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                            📚 Semua
                        </button>
                        <button @click="activeCategory = 'lingkungan'" 
                                :class="activeCategory === 'lingkungan' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                            🌱 Lingkungan
                        </button>
                        <button @click="activeCategory = 'iso'" 
                                :class="activeCategory === 'iso' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                            📊 ISO Standards
                        </button>
                        <button @click="activeCategory = 'pelabuhan'" 
                                :class="activeCategory === 'pelabuhan' ? 'border-blue-800 text-blue-800' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                            ⚓ Pelabuhan
                        </button>
                        <button @click="activeCategory = 'templates'" 
                                :class="activeCategory === 'templates' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                            📝 Templates
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Regulasi Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-lg font-semibold mb-6">
                            <span x-text="getCategoryTitle()"></span>
                            <span class="text-sm font-normal text-gray-500">(<span x-text="filteredRegulasi.length"></span> dokumen)</span>
                        </h3>
                        
                        <div class="space-y-4">
                            <template x-for="regulasi in paginatedRegulasi" :key="regulasi.id">
                                <div class="border border-gray-200 rounded-lg p-6 hover:bg-gray-50 transition-colors">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3 mb-2">
                                                <span class="text-2xl" x-text="getTypeIcon(regulasi.type)"></span>
                                                <h4 class="font-semibold text-gray-800" x-text="regulasi.title"></h4>
                                                <span class="px-2 py-1 text-xs rounded-full"
                                                      :class="getCategoryBadgeColor(regulasi.category)"
                                                      x-text="regulasi.category"></span>
                                            </div>
                                            
                                            <p class="text-gray-600 mb-3" x-text="regulasi.description"></p>
                                            
                                            <div class="flex items-center text-sm text-gray-500 space-x-4 mb-3">
                                                <span>📅 <span x-text="regulasi.tahun_terbit || 'N/A'"></span></span>
                                                <span>📄 <span x-text="regulasi.nomor_dokumen || 'N/A'"></span></span>
                                                <span x-show="regulasi.status_berlaku" class="text-green-600">✅ Berlaku</span>
                                                <span x-show="!regulasi.status_berlaku" class="text-red-600">❌ Tidak Berlaku</span>
                                            </div>
                                            
                                            <div x-show="regulasi.tags" class="flex flex-wrap gap-1 mb-3">
                                                <template x-for="tag in (regulasi.tags || '').split(',')" :key="tag">
                                                    <span class="bg-gray-100 text-gray-700 px-2 py-1 text-xs rounded" x-text="tag.trim()"></span>
                                                </template>
                                            </div>
                                        </div>
                                        
                                        <div class="flex flex-col space-y-2 ml-4">
                                            <button @click="viewRegulasi(regulasi)" 
                                                    class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">
                                                Lihat Detail
                                            </button>
                                            <button x-show="regulasi.download_url" 
                                                    @click="downloadDocument(regulasi.download_url)"
                                                    class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">
                                                Download
                                            </button>
                                            <button @click="bookmarkRegulasi(regulasi.id)" 
                                                    :class="isBookmarked(regulasi.id) ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-gray-600 hover:bg-gray-700'"
                                                    class="text-white px-3 py-1 rounded text-sm">
                                                <span x-text="isBookmarked(regulasi.id) ? 'Tersimpan' : 'Bookmark'"></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            
                            <div x-show="filteredRegulasi.length === 0" 
                                 class="text-center py-8 text-gray-500">
                                Tidak ada regulasi ditemukan
                            </div>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-6 flex justify-center">
                            <nav class="flex space-x-2">
                                <button @click="changePage(currentPage - 1)" 
                                        :disabled="currentPage === 1"
                                        class="px-3 py-2 border rounded-md disabled:opacity-50">
                                    ← Sebelumnya
                                </button>
                                <template x-for="page in totalPages" :key="page">
                                    <button @click="changePage(page)" 
                                            :class="page === currentPage ? 'bg-blue-600 text-white' : 'bg-white text-gray-700'"
                                            class="px-3 py-2 border rounded-md"
                                            x-text="page"></button>
                                </template>
                                <button @click="changePage(currentPage + 1)" 
                                        :disabled="currentPage === totalPages"
                                        class="px-3 py-2 border rounded-md disabled:opacity-50">
                                    Selanjutnya →
                                </button>
                            </nav>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    
                    <!-- Quick Access -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h4 class="font-semibold text-gray-800 mb-4">⚡ Akses Cepat</h4>
                        <div class="space-y-3">
                            <a href="#" @click="quickFilter('terbaru')" 
                               class="block text-blue-600 hover:text-blue-800 text-sm">
                                📅 Regulasi Terbaru (2024)
                            </a>
                            <a href="#" @click="quickFilter('wajib')" 
                               class="block text-red-600 hover:text-red-800 text-sm">
                                ⚠️ Regulasi Wajib Pelabuhan
                            </a>
                            <a href="#" @click="quickFilter('iso')" 
                               class="block text-green-600 hover:text-green-800 text-sm">
                                📊 ISO 14001 & 45001
                            </a>
                            <a href="#" @click="quickFilter('template')" 
                               class="block text-purple-600 hover:text-purple-800 text-sm">
                                📝 Template Dokumen
                            </a>
                        </div>
                    </div>

                    <!-- Bookmarked -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h4 class="font-semibold text-gray-800 mb-4">🔖 Tersimpan</h4>
                        <div class="space-y-2">
                            <template x-for="bookmark in bookmarkedRegulasi" :key="bookmark.id">
                                <div class="text-sm">
                                    <a href="#" @click="viewRegulasi(bookmark)" 
                                       class="text-blue-600 hover:text-blue-800 block truncate" 
                                       x-text="bookmark.title"></a>
                                </div>
                            </template>
                            <div x-show="bookmarkedRegulasi.length === 0" 
                                 class="text-gray-500 text-sm">
                                Belum ada regulasi tersimpan
                            </div>
                        </div>
                    </div>

                    <!-- Recent Updates -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h4 class="font-semibold text-gray-800 mb-4">🔔 Update Terbaru</h4>
                        <div class="space-y-3">
                            <template x-for="update in recentUpdates" :key="update.id">
                                <div class="text-sm border-b border-gray-200 pb-2 last:border-b-0">
                                    <div class="font-medium text-gray-800" x-text="update.title"></div>
                                    <div class="text-gray-600" x-text="update.description"></div>
                                    <div class="text-gray-500 text-xs" x-text="formatDate(update.date)"></div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Help & Support -->
                    <div class="bg-blue-50 rounded-lg p-6">
                        <h4 class="font-semibold text-blue-800 mb-3">💡 Butuh Bantuan?</h4>
                        <p class="text-blue-700 text-sm mb-3">
                            Tidak menemukan regulasi yang dicari? Tim kami siap membantu!
                        </p>
                        <button @click="requestHelp()" 
                                class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 w-full">
                            Hubungi Support
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Regulasi -->
    <div x-show="showDetailModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="inline-block w-full max-w-4xl bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all">
                <div class="bg-white px-6 pt-5 pb-4">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" x-text="selectedRegulasi?.title"></h3>
                        <button @click="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div x-show="selectedRegulasi" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nomor Dokumen</label>
                                <p class="text-sm text-gray-900" x-text="selectedRegulasi?.nomor_dokumen || 'N/A'"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tahun Terbit</label>
                                <p class="text-sm text-gray-900" x-text="selectedRegulasi?.tahun_terbit || 'N/A'"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kategori</label>
                                <p class="text-sm text-gray-900" x-text="selectedRegulasi?.category || 'N/A'"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <span :class="selectedRegulasi?.status_berlaku ? 'text-green-600' : 'text-red-600'" 
                                      class="text-sm font-medium">
                                    <span x-text="selectedRegulasi?.status_berlaku ? '✅ Berlaku' : '❌ Tidak Berlaku'"></span>
                                </span>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                            <p class="text-sm text-gray-900" x-text="selectedRegulasi?.description || 'Tidak ada deskripsi'"></p>
                        </div>
                        
                        <div x-show="selectedRegulasi?.content">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ringkasan Isi</label>
                            <div class="bg-gray-50 p-4 rounded-md">
                                <p class="text-sm text-gray-700" x-text="selectedRegulasi?.content"></p>
                            </div>
                        </div>
                        
                        <div x-show="selectedRegulasi?.related_regulations">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Regulasi Terkait</label>
                            <div class="space-y-2">
                                <template x-for="related in (selectedRegulasi?.related_regulations || '').split(',')" :key="related">
                                    <div class="text-sm text-blue-600 hover:text-blue-800 cursor-pointer" x-text="related.trim()"></div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-3 flex justify-end space-x-3">
                    <button x-show="selectedRegulasi?.download_url" 
                            @click="downloadDocument(selectedRegulasi.download_url)"
                            class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                        Download Dokumen
                    </button>
                    <button @click="closeDetailModal()"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function regulasiApp() {
            return {
                regulasiData: @json($regulasi ?? []),
                filteredRegulasi: [],
                paginatedRegulasi: [],
                bookmarkedRegulasi: @json($bookmarkedRegulasi ?? []),
                recentUpdates: @json($recentUpdates ?? []),
                searchQuery: '',
                categoryFilter: '',
                typeFilter: '',
                activeCategory: 'all',
                currentPage: 1,
                itemsPerPage: 10,
                totalPages: 1,
                showDetailModal: false,
                selectedRegulasi: null,
                
                init() {
                    this.filterRegulasi();
                },
                
                searchRegulasi() {
                    this.filterRegulasi();
                },
                
                filterRegulasi() {
                    let filtered = this.regulasiData;
                    
                    // Filter by search query
                    if (this.searchQuery) {
                        filtered = filtered.filter(regulasi => 
                            regulasi.title.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                            regulasi.description.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                            (regulasi.nomor_dokumen && regulasi.nomor_dokumen.toLowerCase().includes(this.searchQuery.toLowerCase()))
                        );
                    }
                    
                    // Filter by category
                    if (this.categoryFilter) {
                        filtered = filtered.filter(regulasi => regulasi.category === this.categoryFilter);
                    }
                    
                    // Filter by type
                    if (this.typeFilter) {
                        filtered = filtered.filter(regulasi => regulasi.type === this.typeFilter);
                    }
                    
                    // Filter by active category tab
                    if (this.activeCategory !== 'all') {
                        if (this.activeCategory === 'templates') {
                            filtered = filtered.filter(regulasi => regulasi.type === 'template');
                        } else {
                            filtered = filtered.filter(regulasi => regulasi.category === this.activeCategory);
                        }
                    }
                    
                    this.filteredRegulasi = filtered;
                    this.currentPage = 1;
                    this.updatePagination();
                },
                
                updatePagination() {
                    this.totalPages = Math.ceil(this.filteredRegulasi.length / this.itemsPerPage);
                    const startIndex = (this.currentPage - 1) * this.itemsPerPage;
                    const endIndex = startIndex + this.itemsPerPage;
                    this.paginatedRegulasi = this.filteredRegulasi.slice(startIndex, endIndex);
                },
                
                changePage(page) {
                    if (page >= 1 && page <= this.totalPages) {
                        this.currentPage = page;
                        this.updatePagination();
                    }
                },
                
                quickFilter(type) {
                    switch(type) {
                        case 'terbaru':
                            this.searchQuery = '2024';
                            break;
                        case 'wajib':
                            this.categoryFilter = 'pelabuhan';
                            break;
                        case 'iso':
                            this.searchQuery = 'ISO';
                            break;
                        case 'template':
                            this.activeCategory = 'templates';
                            break;
                    }
                    this.filterRegulasi();
                },
                
                viewRegulasi(regulasi) {
                    this.selectedRegulasi = regulasi;
                    this.showDetailModal = true;
                },
                
                closeDetailModal() {
                    this.showDetailModal = false;
                    this.selectedRegulasi = null;
                },
                
                downloadDocument(url) {
                    window.open(url, '_blank');
                },
                
                bookmarkRegulasi(regulasiId) {
                    const isBookmarked = this.isBookmarked(regulasiId);
                    
                    fetch(`/regulasi/${regulasiId}/bookmark`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    }).then(() => {
                        if (isBookmarked) {
                            this.bookmarkedRegulasi = this.bookmarkedRegulasi.filter(r => r.id !== regulasiId);
                        } else {
                            const regulasi = this.regulasiData.find(r => r.id === regulasiId);
                            if (regulasi) this.bookmarkedRegulasi.push(regulasi);
                        }
                    });
                },
                
                isBookmarked(regulasiId) {
                    return this.bookmarkedRegulasi.some(r => r.id === regulasiId);
                },
                
                requestHelp() {
                    // Redirect to support or open contact modal
                    window.location.href = '/forum';
                },
                
                getCategoryTitle() {
                    const titles = {
                        'all': 'Semua Regulasi & Standar',
                        'lingkungan': 'Regulasi Lingkungan',
                        'iso': 'ISO Standards',
                        'pelabuhan': 'Regulasi Pelabuhan',
                        'templates': 'Template Dokumen'
                    };
                    return titles[this.activeCategory] || 'Regulasi & Standar';
                },
                
                getTypeIcon(type) {
                    const icons = {
                        'undang-undang': '📜',
                        'peraturan-pemerintah': '📋',
                        'peraturan-menteri': '📄',
                        'iso-standard': '📊',
                        'template': '📝'
                    };
                    return icons[type] || '📚';
                },
                
                getCategoryBadgeColor(category) {
                    const colors = {
                        'lingkungan': 'bg-green-100 text-green-800',
                        'iso': 'bg-blue-100 text-blue-800',
                        'pelabuhan': 'bg-blue-900 text-white',
                        'keselamatan': 'bg-red-100 text-red-800',
                        'kualitas': 'bg-purple-100 text-purple-800'
                    };
                    return colors[category] || 'bg-gray-100 text-gray-800';
                },
                
                formatDate(dateString) {
                    if (!dateString) return 'N/A';
                    return new Date(dateString).toLocaleDateString('id-ID', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                }
            }
        }
    </script>
</x-app-layout>

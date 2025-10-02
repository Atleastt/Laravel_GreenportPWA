<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Forum & Konsultasi') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="forumApp()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Header Actions -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Forum Diskusi Audit</h3>
                        <p class="text-gray-600">Ajukan pertanyaan, berbagi pengalaman, dan dapatkan solusi terbaik</p>
                    </div>
                    <div class="flex space-x-3">
                        <button @click="showNewThreadModal = true" 
                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 flex items-center">
                            <i class="fas fa-plus mr-2"></i>Buat Thread Baru
                        </button>
                        <button @click="showBestPracticesModal = true" 
                                class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 flex items-center">
                            <i class="fas fa-lightbulb mr-2"></i>Best Practices
                        </button>
                    </div>
                </div>
            </div>

            <!-- Search & Filter -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" 
                               x-model="searchQuery"
                               @input="searchThreads()"
                               placeholder="Cari diskusi, kategori, atau kata kunci..."
                               class="w-full border-gray-300 rounded-md">
                    </div>
                    <div class="flex space-x-2">
                        <select x-model="categoryFilter" @change="filterThreads()" class="border-gray-300 rounded-md">
                            <option value="">Semua Kategori</option>
                            <option value="audit-kepatuhan">Audit Kepatuhan</option>
                            <option value="dokumentasi">Dokumentasi</option>
                            <option value="regulasi">Regulasi</option>
                            <option value="teknologi">Teknologi</option>
                            <option value="best-practice">Best Practice</option>
                        </select>
                        <select x-model="statusFilter" @change="filterThreads()" class="border-gray-300 rounded-md">
                            <option value="">Semua Status</option>
                            <option value="open">Terbuka</option>
                            <option value="solved">Terpecahkan</option>
                            <option value="closed">Ditutup</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Forum Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <div class="flex items-center">
                        <div class="text-3xl mr-4">💬</div>
                        <div>
                            <h4 class="font-semibold text-gray-800">Total Diskusi</h4>
                            <p class="text-2xl font-bold text-blue-600" x-text="stats.totalThreads || 0"></p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <div class="flex items-center">
                        <div class="text-3xl mr-4">✅</div>
                        <div>
                            <h4 class="font-semibold text-gray-800">Terpecahkan</h4>
                            <p class="text-2xl font-bold text-green-600" x-text="stats.solvedThreads || 0"></p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <div class="flex items-center">
                        <div class="text-3xl mr-4">👥</div>
                        <div>
                            <h4 class="font-semibold text-gray-800">Partisipan Aktif</h4>
                            <p class="text-2xl font-bold text-purple-600" x-text="stats.activeUsers || 0"></p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <div class="flex items-center">
                        <div class="text-3xl mr-4">⭐</div>
                        <div>
                            <h4 class="font-semibold text-gray-800">Best Practices</h4>
                            <p class="text-2xl font-bold text-yellow-600" x-text="stats.bestPractices || 0"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Forum Threads -->
            <div class="bg-white rounded-lg shadow-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-6">Diskusi Terbaru</h3>
                    
                    <div class="space-y-4">
                        <template x-for="thread in filteredThreads" :key="thread.id">
                            <div class="border border-gray-200 rounded-lg p-6 hover:bg-gray-50 transition-colors">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <h4 class="text-lg font-semibold text-gray-800 hover:text-blue-600 cursor-pointer"
                                                @click="openThread(thread.id)" 
                                                x-text="thread.title"></h4>
                                            <span class="px-2 py-1 text-xs rounded-full"
                                                  :class="getCategoryColor(thread.category)"
                                                  x-text="getCategoryLabel(thread.category)"></span>
                                            <span x-show="thread.is_solved" 
                                                  class="bg-green-100 text-green-800 px-2 py-1 text-xs rounded-full">
                                                ✅ Terpecahkan
                                            </span>
                                        </div>
                                        
                                        <p class="text-gray-600 mb-3" x-text="thread.description"></p>
                                        
                                        <div class="flex items-center text-sm text-gray-500 space-x-4">
                                            <span>👤 <span x-text="thread.author || 'Unknown'"></span></span>
                                            <span>📅 <span x-text="formatDate(thread.created_at)"></span></span>
                                            <span>💬 <span x-text="thread.replies_count || 0"></span> balasan</span>
                                            <span>👁️ <span x-text="thread.views_count || 0"></span> views</span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-col items-end space-y-2">
                                        <div class="flex space-x-2">
                                            <button @click="openThread(thread.id)" 
                                                    class="text-blue-600 hover:text-blue-800 text-sm">
                                                Lihat Detail
                                            </button>
                                            <button x-show="!thread.is_solved && canMarkSolved(thread)" 
                                                    @click="markAsSolved(thread.id)"
                                                    class="text-green-600 hover:text-green-800 text-sm">
                                                Tandai Selesai
                                            </button>
                                        </div>
                                        
                                        <div x-show="thread.last_reply" class="text-right">
                                            <div class="text-xs text-gray-500">Balasan terakhir:</div>
                                            <div class="text-xs text-gray-600" x-text="thread.last_reply?.author || 'Unknown'"></div>
                                            <div class="text-xs text-gray-500" x-text="formatDate(thread.last_reply?.created_at)"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                        
                        <div x-show="filteredThreads.length === 0" 
                             class="text-center py-8 text-gray-500">
                            Tidak ada diskusi ditemukan
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
        </div>
    </div>

    <!-- Modal Buat Thread Baru -->
    <div x-show="showNewThreadModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="inline-block w-full max-w-2xl bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all">
                <form @submit.prevent="createThread()">
                    <div class="bg-white px-6 pt-5 pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Buat Thread Baru</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Judul Diskusi</label>
                                <input type="text" x-model="newThread.title" 
                                       class="mt-1 block w-full border-gray-300 rounded-md" 
                                       placeholder="Jelaskan topik diskusi dengan singkat..." required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kategori</label>
                                <select x-model="newThread.category" class="mt-1 block w-full border-gray-300 rounded-md" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="audit-kepatuhan">Audit Kepatuhan</option>
                                    <option value="dokumentasi">Dokumentasi</option>
                                    <option value="regulasi">Regulasi</option>
                                    <option value="teknologi">Teknologi</option>
                                    <option value="best-practice">Best Practice</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                <textarea x-model="newThread.description" rows="5" 
                                         class="mt-1 block w-full border-gray-300 rounded-md"
                                         placeholder="Jelaskan pertanyaan atau topik diskusi secara detail..." required></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tag (opsional)</label>
                                <input type="text" x-model="newThread.tags" 
                                       class="mt-1 block w-full border-gray-300 rounded-md"
                                       placeholder="Pisahkan dengan koma: audit, lingkungan, ISO">
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-3 flex flex-row-reverse space-x-3 space-x-reverse">
                        <button type="submit" 
                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            Buat Thread
                        </button>
                        <button type="button" @click="closeNewThreadModal()"
                                class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Best Practices -->
    <div x-show="showBestPracticesModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="inline-block w-full max-w-4xl bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all">
                <div class="bg-white px-6 pt-5 pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Best Practices Audit</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <template x-for="practice in bestPractices" :key="practice.id">
                            <div class="border rounded-lg p-4 hover:bg-gray-50">
                                <div class="flex items-center mb-2">
                                    <span class="text-2xl mr-2" x-text="practice.icon || '⭐'"></span>
                                    <h4 class="font-semibold text-gray-800" x-text="practice.title"></h4>
                                </div>
                                <p class="text-gray-600 text-sm mb-3" x-text="practice.description"></p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500" x-text="'Dari: ' + (practice.author || 'Unknown')"></span>
                                    <button @click="viewPracticeDetail(practice.id)" 
                                            class="text-blue-600 text-sm hover:text-blue-800">
                                        Lihat Detail
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-3 flex justify-end">
                    <button @click="closeBestPracticesModal()"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function forumApp() {
            return {
                threads: {!! json_encode($threads ?? []) !!},
                stats: {!! json_encode($stats ?? []) !!},
                bestPractices: {!! json_encode($bestPractices ?? []) !!},
                searchQuery: '',
                categoryFilter: '',
                statusFilter: '',
                filteredThreads: [],
                currentPage: 1,
                totalPages: 1,
                showNewThreadModal: false,
                showBestPracticesModal: false,
                newThread: {
                    title: '',
                    category: '',
                    description: '',
                    tags: ''
                },
                
                init() {
                    this.filteredThreads = this.threads;
                    this.updatePagination();
                },
                
                searchThreads() {
                    this.filterThreads();
                },
                
                filterThreads() {
                    let filtered = this.threads;
                    
                    if (this.searchQuery) {
                        filtered = filtered.filter(thread => 
                            thread.title.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                            thread.description.toLowerCase().includes(this.searchQuery.toLowerCase())
                        );
                    }
                    
                    if (this.categoryFilter) {
                        filtered = filtered.filter(thread => thread.category === this.categoryFilter);
                    }
                    
                    if (this.statusFilter) {
                        filtered = filtered.filter(thread => {
                            if (this.statusFilter === 'open') return !thread.is_solved && !thread.is_closed;
                            if (this.statusFilter === 'solved') return thread.is_solved;
                            if (this.statusFilter === 'closed') return thread.is_closed;
                            return true;
                        });
                    }
                    
                    this.filteredThreads = filtered;
                    this.currentPage = 1;
                    this.updatePagination();
                },
                
                updatePagination() {
                    this.totalPages = Math.ceil(this.filteredThreads.length / 10);
                },
                
                changePage(page) {
                    if (page >= 1 && page <= this.totalPages) {
                        this.currentPage = page;
                    }
                },
                
                openThread(threadId) {
                    window.location.href = '/forum/' + threadId;
                },
                
                markAsSolved(threadId) {
                    fetch('/forum/' + threadId + '/solved', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    }).then(() => {
                        const thread = this.threads.find(t => t.id === threadId);
                        if (thread) thread.is_solved = true;
                        this.filterThreads();
                    });
                },
                
                canMarkSolved(thread) {
                    const currentUserId = {{ auth()->id() ?? 'null' }};
                    const isAuditor = {{ auth()->user() && auth()->user()->hasRole('auditor') ? 'true' : 'false' }};
                    return thread.author_id === currentUserId || isAuditor;
                },
                
                createThread() {
                    fetch('/forum', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(this.newThread)
                    }).then(response => response.json())
                    .then(data => {
                        this.threads.unshift(data);
                        this.filterThreads();
                        this.closeNewThreadModal();
                    });
                },
                
                closeNewThreadModal() {
                    this.showNewThreadModal = false;
                    this.newThread = {
                        title: '',
                        category: '',
                        description: '',
                        tags: ''
                    };
                },
                
                closeBestPracticesModal() {
                    this.showBestPracticesModal = false;
                },
                
                viewPracticeDetail(practiceId) {
                    window.location.href = '/forum/best-practices/' + practiceId;
                },
                
                getCategoryColor(category) {
                    const colors = {
                        'audit-kepatuhan': 'bg-blue-100 text-blue-800',
                        'dokumentasi': 'bg-green-100 text-green-800',
                        'regulasi': 'bg-red-100 text-red-800',
                        'teknologi': 'bg-purple-100 text-purple-800',
                        'best-practice': 'bg-yellow-100 text-yellow-800'
                    };
                    return colors[category] || 'bg-gray-100 text-gray-800';
                },
                
                getCategoryLabel(category) {
                    const labels = {
                        'audit-kepatuhan': 'Audit Kepatuhan',
                        'dokumentasi': 'Dokumentasi',
                        'regulasi': 'Regulasi',
                        'teknologi': 'Teknologi',
                        'best-practice': 'Best Practice'
                    };
                    return labels[category] || category;
                },
                
                formatDate(dateString) {
                    if (!dateString) return 'N/A';
                    return new Date(dateString).toLocaleDateString('id-ID', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                }
            }
        }
    </script>
</x-app-layout>

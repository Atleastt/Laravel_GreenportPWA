<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sertifikasi & Penghargaan') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="sertifikasiApp()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Achievement Overview -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-700 rounded-lg shadow-lg p-8 mb-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold mb-2">Prestasi Audit Anda</h3>
                        <p class="text-blue-100">Raih sertifikat dan penghargaan melalui kinerja audit yang konsisten</p>
                    </div>
                    <div class="text-6xl opacity-20">🏆</div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    <div class="bg-white/10 backdrop-blur rounded-lg p-4">
                        <div class="text-3xl font-bold" x-text="stats.totalCertificates || 0"></div>
                        <div class="text-blue-100">Total Sertifikat</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur rounded-lg p-4">
                        <div class="text-3xl font-bold" x-text="stats.currentLevel || 'Bronze'"></div>
                        <div class="text-blue-100">Level Saat Ini</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur rounded-lg p-4">
                        <div class="text-3xl font-bold" x-text="stats.pointsToNext || 0"></div>
                        <div class="text-blue-100">Poin ke Level Berikutnya</div>
                    </div>
                </div>
            </div>

            <!-- Progress Level -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Progress Sertifikasi</h3>
                
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium">Progress ke <span x-text="getNextLevel()"></span></span>
                        <span class="text-sm text-gray-500"><span x-text="currentPoints"></span> / <span x-text="pointsNeeded"></span> poin</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-3 rounded-full transition-all duration-300" 
                             :style="'width: ' + getProgressPercentage() + '%'"></div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center p-4 rounded-lg" 
                         :class="getCurrentLevelClass('Bronze')">
                        <div class="text-3xl mb-2">🥉</div>
                        <h4 class="font-semibold">Bronze</h4>
                        <p class="text-sm">0 - 100 poin</p>
                    </div>
                    <div class="text-center p-4 rounded-lg" 
                         :class="getCurrentLevelClass('Silver')">
                        <div class="text-3xl mb-2">🥈</div>
                        <h4 class="font-semibold">Silver</h4>
                        <p class="text-sm">101 - 300 poin</p>
                    </div>
                    <div class="text-center p-4 rounded-lg" 
                         :class="getCurrentLevelClass('Gold')">
                        <div class="text-3xl mb-2">🥇</div>
                        <h4 class="font-semibold">Gold</h4>
                        <p class="text-sm">301+ poin</p>
                    </div>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="bg-white rounded-lg shadow-lg mb-6">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex">
                        <button @click="activeTab = 'certificates'" 
                                :class="activeTab === 'certificates' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                            🏅 Sertifikat Saya
                        </button>
                        <button @click="activeTab = 'achievements'" 
                                :class="activeTab === 'achievements' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                            🏆 Pencapaian
                        </button>
                        <button @click="activeTab = 'leaderboard'" 
                                :class="activeTab === 'leaderboard' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                            📊 Leaderboard
                        </button>
                        <button @click="activeTab = 'rewards'" 
                                :class="activeTab === 'rewards' ? 'border-yellow-500 text-yellow-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                            🎁 Hadiah
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Certificates Tab -->
            <div x-show="activeTab === 'certificates'" class="space-y-6">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Sertifikat Digital</h3>
                        <button @click="generateCertificate()" 
                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            Generate Sertifikat Baru
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <template x-for="certificate in certificates" :key="certificate.id">
                            <div class="border rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                                <div class="bg-gradient-to-br from-blue-50 to-purple-50 p-6">
                                    <div class="text-center">
                                        <div class="text-4xl mb-3" x-text="getCertificateIcon(certificate.type)"></div>
                                        <h4 class="font-bold text-lg text-gray-800" x-text="certificate.title"></h4>
                                        <p class="text-gray-600 text-sm mb-2" x-text="certificate.description"></p>
                                        <div class="text-xs text-gray-500 mb-4">
                                            Diterbitkan: <span x-text="formatDate(certificate.issued_date)"></span>
                                        </div>
                                        
                                        <div class="flex justify-center space-x-2 mb-4">
                                            <span class="px-2 py-1 text-xs rounded-full"
                                                  :class="getValidityBadgeColor(certificate.is_valid)"
                                                  x-text="certificate.is_valid ? 'Valid' : 'Expired'"></span>
                                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800"
                                                  x-text="certificate.level || 'Bronze'"></span>
                                        </div>
                                        
                                        <div class="space-y-2">
                                            <button @click="viewCertificate(certificate)" 
                                                    class="w-full bg-blue-600 text-white px-3 py-2 rounded text-sm hover:bg-blue-700">
                                                Lihat Sertifikat
                                            </button>
                                            <div class="flex space-x-2">
                                                <button @click="downloadCertificate(certificate.id)" 
                                                        class="flex-1 bg-green-600 text-white px-3 py-1 rounded text-xs hover:bg-green-700">
                                                    Download
                                                </button>
                                                <button @click="shareCertificate(certificate.id)" 
                                                        class="flex-1 bg-purple-600 text-white px-3 py-1 rounded text-xs hover:bg-purple-700">
                                                    Share
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                        
                        <div x-show="certificates.length === 0" 
                             class="col-span-full text-center py-8 text-gray-500">
                            Belum ada sertifikat. Selesaikan audit untuk mendapatkan sertifikat pertama!
                        </div>
                    </div>
                </div>
            </div>

            <!-- Achievements Tab -->
            <div x-show="activeTab === 'achievements'" class="space-y-6">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold mb-6">Pencapaian & Badge</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <template x-for="achievement in achievements" :key="achievement.id">
                            <div class="text-center p-4 border rounded-lg"
                                 :class="achievement.earned ? 'bg-yellow-50 border-yellow-200' : 'bg-gray-50 border-gray-200'">
                                <div class="text-3xl mb-2" 
                                     :class="achievement.earned ? 'grayscale-0' : 'grayscale'"
                                     x-text="achievement.icon || '🏅'"></div>
                                <h4 class="font-semibold text-sm" x-text="achievement.title"></h4>
                                <p class="text-xs text-gray-600 mb-2" x-text="achievement.description"></p>
                                <div x-show="achievement.earned" class="text-xs text-green-600">
                                    ✅ Tercapai pada <span x-text="formatDate(achievement.earned_date)"></span>
                                </div>
                                <div x-show="!achievement.earned" class="text-xs text-gray-500">
                                    Progress: <span x-text="achievement.progress || 0"></span>/<span x-text="achievement.target || 1"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Leaderboard Tab -->
            <div x-show="activeTab === 'leaderboard'" class="space-y-6">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold mb-6">Peringkat Auditor</h3>
                    
                    <!-- Top 3 -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                        <template x-for="(leader, index) in leaderboard.slice(0, 3)" :key="leader.id">
                            <div class="text-center p-6 rounded-lg"
                                 :class="getLeaderboardColor(index)">
                                <div class="text-4xl mb-2" x-text="getLeaderboardIcon(index)"></div>
                                <h4 class="font-bold text-lg" x-text="leader.name"></h4>
                                <p class="text-sm mb-2" x-text="leader.organization || 'N/A'"></p>
                                <div class="text-2xl font-bold" x-text="leader.total_points || 0"></div>
                                <div class="text-sm">poin</div>
                                <div class="text-xs mt-2" x-text="(leader.certificates_count || 0) + ' sertifikat'"></div>
                            </div>
                        </template>
                    </div>
                    
                    <!-- Full Leaderboard -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rank</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Auditor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Organisasi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Poin</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Level</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sertifikat</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <template x-for="(leader, index) in leaderboard" :key="leader.id">
                                    <tr :class="leader.is_current_user ? 'bg-blue-50' : ''">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <span x-text="index + 1"></span>
                                            <span x-show="leader.is_current_user" class="ml-2 text-blue-600">(Anda)</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="text-sm font-medium text-gray-900" x-text="leader.name"></div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="leader.organization || 'N/A'"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-600" x-text="leader.total_points || 0"></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs rounded-full"
                                                  :class="getLevelBadgeColor(leader.level)"
                                                  x-text="leader.level || 'Bronze'"></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="leader.certificates_count || 0"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Rewards Tab -->
            <div x-show="activeTab === 'rewards'" class="space-y-6">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h3 class="text-lg font-semibold mb-6">Tukar Poin dengan Hadiah</h3>
                    
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-semibold text-blue-800">Poin Tersedia</h4>
                                <p class="text-blue-600">Gunakan poin Anda untuk mendapatkan hadiah menarik</p>
                            </div>
                            <div class="text-3xl font-bold text-blue-800" x-text="currentPoints"></div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <template x-for="reward in rewards" :key="reward.id">
                            <div class="border rounded-lg p-4 hover:shadow-lg transition-shadow">
                                <div class="text-center">
                                    <div class="text-4xl mb-3" x-text="reward.icon || '🎁'"></div>
                                    <h4 class="font-semibold text-lg mb-2" x-text="reward.title"></h4>
                                    <p class="text-gray-600 text-sm mb-4" x-text="reward.description"></p>
                                    
                                    <div class="flex items-center justify-center mb-4">
                                        <span class="text-2xl font-bold text-blue-600" x-text="reward.points_required || 0"></span>
                                        <span class="text-sm text-gray-500 ml-1">poin</span>
                                    </div>
                                    
                                    <button @click="redeemReward(reward)" 
                                            :disabled="currentPoints < (reward.points_required || 0)"
                                            :class="currentPoints >= (reward.points_required || 0) ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-400 cursor-not-allowed'"
                                            class="w-full text-white px-4 py-2 rounded-md">
                                        <span x-text="currentPoints >= (reward.points_required || 0) ? 'Tukar Sekarang' : 'Poin Tidak Cukup'"></span>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Certificate View -->
    <div x-show="showCertificateModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="inline-block w-full max-w-4xl bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all">
                <div class="bg-white px-6 pt-5 pb-4" x-show="selectedCertificate">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Sertifikat Digital</h3>
                        <button @click="closeCertificateModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <!-- Certificate Preview -->
                    <div class="bg-gradient-to-br from-blue-50 to-purple-50 border-2 border-blue-200 rounded-lg p-8 text-center">
                        <div class="mb-6">
                            <div class="text-6xl mb-4" x-text="getCertificateIcon(selectedCertificate?.type)"></div>
                            <h2 class="text-2xl font-bold text-gray-800 mb-2">SERTIFIKAT AUDIT</h2>
                            <h3 class="text-xl font-semibold text-blue-800" x-text="selectedCertificate?.title"></h3>
                        </div>
                        
                        <div class="mb-6">
                            <p class="text-gray-600 mb-4">Diberikan kepada:</p>
                            <h4 class="text-2xl font-bold text-gray-800" x-text="selectedCertificate?.recipient_name || 'N/A'"></h4>
                            <p class="text-gray-600 mt-2" x-text="selectedCertificate?.recipient_organization || 'N/A'"></p>
                        </div>
                        
                        <div class="mb-6">
                            <p class="text-gray-600" x-text="selectedCertificate?.description"></p>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-8 text-sm text-gray-600 mb-6">
                            <div>
                                <p>Tanggal Terbit:</p>
                                <p class="font-semibold" x-text="formatDate(selectedCertificate?.issued_date)"></p>
                            </div>
                            <div>
                                <p>ID Sertifikat:</p>
                                <p class="font-semibold" x-text="selectedCertificate?.certificate_id || 'N/A'"></p>
                            </div>
                        </div>
                        
                        <div class="flex justify-center">
                            <img x-show="selectedCertificate?.qr_code" 
                                 :src="selectedCertificate?.qr_code" 
                                 alt="QR Code" 
                                 class="w-20 h-20">
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-3 flex justify-end space-x-3">
                    <button @click="downloadCertificate(selectedCertificate?.id)"
                            class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                        Download PDF
                    </button>
                    <button @click="shareCertificate(selectedCertificate?.id)"
                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Share
                    </button>
                    <button @click="closeCertificateModal()"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function sertifikasiApp() {
            return {
                activeTab: 'certificates',
                certificates: {!! json_encode($certificates ?? []) !!},
                achievements: {!! json_encode($achievements ?? []) !!},
                leaderboard: {!! json_encode($leaderboard ?? []) !!},
                rewards: {!! json_encode($rewards ?? []) !!},
                stats: {!! json_encode($stats ?? []) !!},
                currentPoints: {!! json_encode($currentPoints ?? 0) !!},
                pointsNeeded: {!! json_encode($pointsNeeded ?? 100) !!},
                showCertificateModal: false,
                selectedCertificate: null,
                
                getNextLevel() {
                    const currentLevel = this.stats.currentLevel || 'Bronze';
                    return currentLevel === 'Bronze' ? 'Silver' : currentLevel === 'Silver' ? 'Gold' : 'Platinum';
                },
                
                getProgressPercentage() {
                    return Math.min(100, (this.currentPoints / this.pointsNeeded) * 100);
                },
                
                getCurrentLevelClass(level) {
                    const currentLevel = this.stats.currentLevel || 'Bronze';
                    return currentLevel === level ? 'bg-blue-100 border-2 border-blue-500' : 'bg-gray-50';
                },
                
                viewCertificate(certificate) {
                    this.selectedCertificate = certificate;
                    this.showCertificateModal = true;
                },
                
                closeCertificateModal() {
                    this.showCertificateModal = false;
                    this.selectedCertificate = null;
                },
                
                downloadCertificate(certificateId) {
                    window.open('/certificates/' + certificateId + '/download', '_blank');
                },
                
                shareCertificate(certificateId) {
                    const url = window.location.origin + '/certificates/' + certificateId + '/verify';
                    navigator.clipboard.writeText(url).then(() => {
                        alert('Link sertifikat disalin ke clipboard!');
                    });
                },
                
                generateCertificate() {
                    fetch('/certificates/generate', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    }).then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.certificates.unshift(data.certificate);
                            alert('Sertifikat baru berhasil dibuat!');
                        }
                    });
                },
                
                redeemReward(reward) {
                    const pointsRequired = reward.points_required || 0;
                    if (this.currentPoints < pointsRequired) return;
                    
                    if (confirm('Tukar ' + pointsRequired + ' poin dengan ' + reward.title + '?')) {
                        fetch('/rewards/' + reward.id + '/redeem', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        }).then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                this.currentPoints -= pointsRequired;
                                alert('Hadiah berhasil ditukar!');
                            }
                        });
                    }
                },
                
                getCertificateIcon(type) {
                    const icons = {
                        'completion': '🎓',
                        'excellence': '🏆',
                        'compliance': '✅',
                        'environmental': '🌱',
                        'safety': '🛡️'
                    };
                    return icons[type] || '🏅';
                },
                
                getValidityBadgeColor(isValid) {
                    return isValid ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                },
                
                getLeaderboardColor(index) {
                    const colors = [
                        'bg-yellow-100 border-yellow-500',
                        'bg-gray-100 border-gray-400',
                        'bg-orange-100 border-orange-500'
                    ];
                    return colors[index] || 'bg-gray-50';
                },
                
                getLeaderboardIcon(index) {
                    return ['🥇', '🥈', '🥉'][index] || '🏅';
                },
                
                getLevelBadgeColor(level) {
                    const colors = {
                        'Bronze': 'bg-orange-100 text-orange-800',
                        'Silver': 'bg-gray-100 text-gray-800',
                        'Gold': 'bg-yellow-100 text-yellow-800',
                        'Platinum': 'bg-purple-100 text-purple-800'
                    };
                    return colors[level] || 'bg-gray-100 text-gray-800';
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

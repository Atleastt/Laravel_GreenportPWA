<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-semibold text-gray-700 mb-6">Pengaturan Profil</h1>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="md:flex">
            <!-- Sidebar/Nav -->
            {{-- <div class="w-full md:w-1/4 bg-gray-50 p-6 border-r border-gray-200">
                <nav class="space-y-1">
                    <a href="#" class="block px-3 py-2 rounded-md text-sm font-medium text-emerald-700 bg-emerald-100">Informasi Pribadi</a>
                    <a href="#" class="block px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-900">Ganti Password</a>
                    <a href="#" class="block px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-900">Notifikasi</a>
                </nav>
            </div> --}}

            <!-- Content Area -->
            <div class="w-full p-6">
                <!-- Profile Photo Section -->
                <div class="mb-8 pb-8 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Foto Profil</h2>
            <div class="flex items-center space-x-4">
                        <img class="h-24 w-24 rounded-full object-cover" src="https://ui-avatars.com/api/?name=User+Name&color=FFFFFF&background=10B981" alt="Current profile photo">
                <div>
                            <button type="button" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Ganti Foto
                            </button>
                            <p class="mt-1 text-xs text-gray-500">JPG, GIF atau PNG. Ukuran maks 2MB.</p>
                </div>
            </div>
        </div>

                <!-- Personal Information Section -->
                <div class="mb-8 pb-8 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Informasi Pribadi</h2>
                    <form action="#" method="POST" class="space-y-4">
        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input type="text" name="name" id="name" value="Nama Pengguna Saat Ini" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                            <input type="email" name="email" id="email" value="user@example.com" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm bg-gray-50" readonly>
                            <p class="mt-1 text-xs text-gray-500">Email tidak dapat diubah.</p>
                </div>
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                            <input type="text" name="role" id="role" value="Auditor" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm bg-gray-50" readonly>
                </div>
                        <div class="pt-2">
                            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 text-sm font-semibold">
                                Simpan
                            </button>
            </div>
                    </form>
                </div>

                <!-- Change Password Section -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Ganti Password</h2>
                    <form action="#" method="POST" class="space-y-4">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700">Password Saat Ini</label>
                            <input type="password" name="current_password" id="current_password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                        </div>
                        <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                            <input type="password" name="new_password" id="new_password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                </div>
                        <div>
                    <label for="confirm_new_password" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                            <input type="password" name="confirm_new_password" id="confirm_new_password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                        </div>
                        <div class="pt-2">
                            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 text-sm font-semibold">
                                Ganti Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
</div>
@endsection

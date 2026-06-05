@extends('layouts.app')

@section('title', 'Developer Dashboard - SPARK')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-blue-900 to-purple-900 rounded-lg shadow-lg p-6 mb-8 text-white">
            <h1 class="text-3xl font-bold mb-2">🏴‍☠️ Ahoy, Developer!</h1>
            <p class="text-blue-200">Selamat datang di SPARK Command Center. Kelola seluruh sistem PKKMB di sini.</p>
        </div>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 border-blue-500">
                <div class="p-6">
                    <div class="text-3xl font-bold text-blue-600">{{ $total_students ?? 0 }}</div>
                    <div class="text-gray-600 mt-1">👨‍🎓 Total Mahasiswa</div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 border-green-500">
                <div class="p-6">
                    <div class="text-3xl font-bold text-green-600">{{ $total_mentors ?? 0 }}</div>
                    <div class="text-gray-600 mt-1">👨‍🏫 Total Mentor</div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 border-yellow-500">
                <div class="p-6">
                    <div class="text-3xl font-bold text-yellow-600">{{ $total_groups ?? 0 }}</div>
                    <div class="text-gray-600 mt-1">🏠 Total Kelompok</div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 border-purple-500">
                <div class="p-6">
                    <div class="text-3xl font-bold text-purple-600">{{ $total_quests ?? 0 }}</div>
                    <div class="text-gray-600 mt-1">📜 Total Quest</div>
                </div>
            </div>
        </div>
        
        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h2 class="text-xl font-bold mb-4 border-b pb-2">⚡ Quick Actions</h2>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('developer.students.create') }}" class="bg-blue-50 text-blue-700 p-3 rounded-lg text-center hover:bg-blue-100 transition">
                            ➕ Tambah Mahasiswa
                        </a>
                        <a href="{{ route('developer.mentors.create') }}" class="bg-green-50 text-green-700 p-3 rounded-lg text-center hover:bg-green-100 transition">
                            ➕ Tambah Mentor
                        </a>
                        <a href="{{ route('developer.groups.create') }}" class="bg-yellow-50 text-yellow-700 p-3 rounded-lg text-center hover:bg-yellow-100 transition">
                            ➕ Tambah Kelompok
                        </a>
                        <a href="{{ route('developer.quests.create') }}" class="bg-purple-50 text-purple-700 p-3 rounded-lg text-center hover:bg-purple-100 transition">
                            ➕ Tambah Quest
                        </a>
                        <a href="{{ route('developer.cft.create') }}" class="bg-red-50 text-red-700 p-3 rounded-lg text-center hover:bg-red-100 transition">
                            🏴 Tambah CFT
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Recent Data -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h2 class="text-xl font-bold mb-4 border-b pb-2">📊 Statistik Cepat</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Mahasiswa Aktif</span>
                            <span class="font-bold text-green-600">{{ $active_students ?? 0 }} / {{ $total_students ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">CFT Challenge Aktif</span>
                            <span class="font-bold text-purple-600">{{ $active_cft ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Daily Quest Aktif</span>
                            <span class="font-bold text-orange-600">{{ $daily_quests ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Students Table -->
        <div class="mt-8 bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">👨‍🎓 Mahasiswa Terbaru</h2>
                    <a href="{{ route('developer.students.index') }}" class="text-blue-600 hover:underline text-sm">Lihat Semua →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIM</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelompok</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recent_students ?? [] as $student)
                            <tr>
                                <td class="px-6 py-4 text-sm">{{ $student->nim }}</td>
                                <td class="px-6 py-4 text-sm">{{ $student->name }}</td>
                                <td class="px-6 py-4 text-sm">{{ $student->group->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $student->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $student->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada data mahasiswa</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
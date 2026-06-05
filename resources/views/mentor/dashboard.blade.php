@extends('layouts.app')

@section('title', 'Mentor Dashboard - SPARK')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-green-800 to-teal-800 rounded-lg shadow-lg p-6 mb-8 text-white">
            <h1 class="text-3xl font-bold mb-2">🏴‍☠️ Ahoy, Mentor {{ Auth::user()->name }}!</h1>
            <p class="text-green-200">Selamat datang di dashboard mentor SPARK. Kelola mahasiswa bimbingan Anda di sini.</p>
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
                    <div class="text-3xl font-bold text-green-600">{{ $total_groups ?? 0 }}</div>
                    <div class="text-gray-600 mt-1">🏠 Kelompok Bimbingan</div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 border-yellow-500">
                <div class="p-6">
                    <div class="text-3xl font-bold text-yellow-600">{{ $total_quests_completed_today ?? 0 }}</div>
                    <div class="text-gray-600 mt-1">✅ Quest Selesai Hari Ini</div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 border-purple-500">
                <div class="p-6">
                    <div class="text-3xl font-bold text-purple-600">{{ $avg_pet_level ?? 1 }}</div>
                    <div class="text-gray-600 mt-1">🐾 Rata-rata Pet Level</div>
                </div>
            </div>
        </div>
        
        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Kelompok Bimbingan -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h2 class="text-xl font-bold mb-4 border-b pb-2">🏠 Kelompok Bimbingan</h2>
                    <div class="space-y-3">
                        @forelse($groups as $group)
                        <div class="border rounded-lg p-3 hover:shadow-md transition">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-bold text-lg">{{ $group->name }}</h3>
                                    <p class="text-sm text-gray-600">Kode: {{ $group->code }}</p>
                                </div>
                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                    {{ $group->students->count() }} Mahasiswa
                                </span>
                            </div>
                            <div class="mt-2">
                                <div class="flex justify-between text-sm mb-1">
                                    <span>Progress Pet Kelompok</span>
                                    <span>{{ $group->pet_health }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 rounded-full h-2" style="width: {{ $group->pet_health }}%"></div>
                                </div>
                            </div>
                            <div class="mt-2 text-sm">
                                <span class="text-gray-600">🐾 Pet Level:</span>
                                <span class="font-bold">{{ $group->pet->level ?? 1 }}</span>
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-500 text-center py-4">Belum ada kelompok bimbingan</p>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <!-- Pengumuman Terbaru -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h2 class="text-xl font-bold">📢 Pengumuman Terbaru</h2>
                        <a href="{{ route('mentor.announcements.create') }}" class="text-blue-600 text-sm hover:underline">+ Buat Pengumuman</a>
                    </div>
                    <div class="space-y-3">
                        @forelse($recent_announcements as $announcement)
                        <div class="border-b pb-3">
                            <div class="flex justify-between items-start">
                                <h3 class="font-semibold">{{ $announcement->title }}</h3>
                                <span class="text-xs text-gray-500">{{ $announcement->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($announcement->content, 80) }}</p>
                            <a href="{{ route('mentor.announcements.show', $announcement->id) }}" class="text-blue-600 text-xs hover:underline">Baca →</a>
                        </div>
                        @empty
                        <p class="text-gray-500 text-center py-4">Belum ada pengumuman</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="mt-8 bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h2 class="text-xl font-bold mb-4 border-b pb-2">⚡ Quick Actions</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <a href="{{ route('mentor.students.create') }}" class="bg-blue-50 text-blue-700 p-3 rounded-lg text-center hover:bg-blue-100 transition">
                        ➕ Tambah Mahasiswa
                    </a>
                    <a href="{{ route('mentor.students.import.form') }}" class="bg-green-50 text-green-700 p-3 rounded-lg text-center hover:bg-green-100 transition">
                        📤 Import Excel
                    </a>
                    <a href="{{ route('mentor.pet.progress') }}" class="bg-yellow-50 text-yellow-700 p-3 rounded-lg text-center hover:bg-yellow-100 transition">
                        🐾 Progress Pet
                    </a>
                    <a href="{{ route('mentor.quests.progress') }}" class="bg-purple-50 text-purple-700 p-3 rounded-lg text-center hover:bg-purple-100 transition">
                        📜 Progress Quest
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
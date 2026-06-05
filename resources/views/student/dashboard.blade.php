@extends('layouts.app')

@section('title', 'My Dashboard - SPARK')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Welcome Card -->
        <div class="bg-gradient-to-r from-purple-800 to-indigo-800 rounded-lg shadow-lg p-6 mb-8 text-white">
            <h1 class="text-3xl font-bold mb-2">🏴‍☠️ Ahoy, {{ Auth::user()->name }}!</h1>
            <p class="text-purple-200">Selamat datang di dashboard pribadi SPARK. Pantau progress kamu di sini.</p>
        </div>
        
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 border-orange-500">
                <div class="p-6">
                    <div class="text-3xl font-bold text-orange-600">{{ Auth::user()->food_points }}</div>
                    <div class="text-gray-600 mt-1">🍖 Food Points</div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 border-green-500">
                <div class="p-6">
                    <div class="text-3xl font-bold text-green-600">{{ $petLevel ?? 1 }}</div>
                    <div class="text-gray-600 mt-1">🐾 Pet Level</div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 border-blue-500">
                <div class="p-6">
                    <div class="text-3xl font-bold text-blue-600">{{ $questsCompletedToday ?? 0 }}</div>
                    <div class="text-gray-600 mt-1">✅ Quest Selesai Hari Ini</div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 border-purple-500">
                <div class="p-6">
                    <div class="text-3xl font-bold text-purple-600">{{ $cftCompleted ?? 0 }}</div>
                    <div class="text-gray-600 mt-1">🏆 CFT Selesai</div>
                </div>
            </div>
        </div>
        
        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Pet Info -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h2 class="text-xl font-bold mb-4 border-b pb-2">🐾 Pet Kelompok: {{ $groupName ?? 'Unknown' }}</h2>
                    <div class="text-center mb-4">
                        @php
                            $petLevel = $petLevel ?? 1;
                            $petEmoji = $petLevel < 3 ? '🐣' : ($petLevel < 6 ? '🐥' : '🦅');
                        @endphp
                        <div class="text-8xl mb-3">{{ $petEmoji }}</div>
                        <div class="font-bold text-lg">{{ $petName ?? 'Baby Pet' }}</div>
                        <div class="text-sm text-gray-500">Level {{ $petLevel }} | Exp {{ $petExp ?? 0 }}/100</div>
                    </div>
                    <div class="mb-4">
                        <div class="flex justify-between text-sm mb-1">
                            <span>Experience</span>
                            <span>{{ $petExp ?? 0 }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-blue-500 rounded-full h-3" style="width: {{ $petExp ?? 0 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span>Progress Pet Kelompok</span>
                            <span>{{ $groupPetHealth ?? 0 }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-green-500 rounded-full h-3" style="width: {{ $groupPetHealth ?? 0 }}%"></div>
                        </div>
                    </div>
                    <div class="mt-4 text-center">
                        <button onclick="window.location.href='{{ route('student.main') }}'" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                            🎮 Kembali ke Dunia 3D
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h2 class="text-xl font-bold mb-4 border-b pb-2">📜 Quest Hari Ini</h2>
                    <div class="space-y-3">
                        @forelse($dailyQuests as $quest)
                        <div class="flex justify-between items-center border-b pb-2">
                            <div>
                                <div class="font-medium">{{ $quest->title }}</div>
                                <div class="text-xs text-gray-500">🍖 +{{ $quest->food_reward }}</div>
                            </div>
                            <div>
                                @if($quest->is_completed)
                                    <span class="text-green-600 text-xl">✅</span>
                                @else
                                    <span class="text-gray-400">⭕</span>
                                @endif
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-500 text-center py-4">Belum ada quest hari ini</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Links -->
        <div class="mt-8 bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <h2 class="text-xl font-bold mb-4 border-b pb-2">⚡ Quick Links</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <a href="{{ route('student.main') }}" class="bg-indigo-50 text-indigo-700 p-3 rounded-lg text-center hover:bg-indigo-100 transition">
                        🎮 Dunia 3D
                    </a>
                    <a href="#" onclick="loadPopupInDashboard('pet')" class="bg-orange-50 text-orange-700 p-3 rounded-lg text-center hover:bg-orange-100 transition">
                        🐾 Pet
                    </a>
                    <a href="#" onclick="loadPopupInDashboard('quests')" class="bg-green-50 text-green-700 p-3 rounded-lg text-center hover:bg-green-100 transition">
                        📜 Quest
                    </a>
                    <a href="#" onclick="loadPopupInDashboard('cft')" class="bg-purple-50 text-purple-700 p-3 rounded-lg text-center hover:bg-purple-100 transition">
                        🏴 CFT
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function loadPopupInDashboard(page) {
    window.location.href = '{{ route('student.main') }}';
    setTimeout(() => {
        localStorage.setItem('openPopup', page);
    }, 100);
}

// Check if we need to open a popup after redirect
if (localStorage.getItem('openPopup')) {
    const page = localStorage.getItem('openPopup');
    localStorage.removeItem('openPopup');
    setTimeout(() => {
        if (typeof loadPopup === 'function') {
            loadPopup(page);
        }
    }, 500);
}
</script>
@endsection
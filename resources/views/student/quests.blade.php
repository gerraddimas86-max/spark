<div>
    <div class="mb-4 text-center">
        <p class="text-sm text-gray-400">Selesaikan quest harian untuk mendapatkan food points!</p>
        <div class="inline-block bg-gray-800 px-4 py-1 rounded-full mt-2">
            🍖 Food Points saat ini: <strong class="text-yellow-400">{{ Auth::user()->food_points }}</strong>
        </div>
    </div>
    
    <div class="space-y-3">
        @forelse($quests as $quest)
        <div class="bg-gray-800 rounded-xl p-4 transition-all {{ $quest->is_completed ? 'opacity-75 border-l-4 border-green-500' : 'border-l-4 border-yellow-500' }}">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <h3 class="font-bold text-lg">{{ $quest->title }}</h3>
                        @if($quest->is_completed)
                            <span class="text-green-500 text-sm">✓ Selesai</span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-400">{{ $quest->description }}</p>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="text-xs bg-yellow-600 px-2 py-1 rounded">🍖 +{{ $quest->food_reward }}</span>
                        <span class="text-xs text-gray-500">
                            @if($quest->type == 'login') 🔐 Login
                            @elseif($quest->type == 'cft') 🏴 CFT
                            @elseif($quest->type == 'feed_pet') 🐾 Feed Pet
                            @elseif($quest->type == 'read_announcement') 📢 Baca Info
                            @else 📜 {{ $quest->type }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-8">
            <div class="text-6xl mb-3">📜</div>
            <p class="text-gray-400">Belum ada quest hari ini</p>
        </div>
        @endforelse
    </div>
    
    <div class="mt-5 text-center text-xs text-gray-500 border-t border-gray-700 pt-4">
        💡 Quest akan direset setiap hari pukul 00:00. Selesaikan sebelum diganti!
    </div>
</div>
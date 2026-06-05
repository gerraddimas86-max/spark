<div class="text-center">
    <!-- Pet Display -->
    <div class="mb-6">
        @php
            $petLevel = $pet->level ?? 1;
            $petEmoji = $petLevel < 3 ? '🐣' : ($petLevel < 6 ? '🐥' : '🦅');
            $petColor = $petLevel < 3 ? '#8B4513' : ($petLevel < 6 ? '#CD853F' : '#FFD700');
        @endphp
        <div class="text-8xl mb-3 animate-bounce" style="animation-duration: 2s;">
            {{ $petEmoji }}
        </div>
        <h3 class="text-xl font-bold text-yellow-400">{{ $pet->name ?? 'Baby Pet' }}</h3>
        <p class="text-sm text-gray-400">Level {{ $petLevel }} | Exp {{ $pet->experience ?? 0 }}/100</p>
    </div>
    
    <!-- Experience Bar -->
    <div class="mb-5">
        <div class="flex justify-between text-sm mb-1">
            <span>Experience</span>
            <span>{{ $pet->experience ?? 0 }}%</span>
        </div>
        <div class="w-full bg-gray-700 rounded-full h-4 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-full rounded-full transition-all duration-500" 
                 style="width: {{ $pet->experience ?? 0 }}%"></div>
        </div>
    </div>
    
    <!-- Group Pet Health -->
    <div class="mb-6">
        <div class="flex justify-between text-sm mb-1">
            <span>🏠 Progress Pet Kelompok</span>
            <span>{{ $group->pet_health ?? 0 }}%</span>
        </div>
        <div class="w-full bg-gray-700 rounded-full h-4 overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-teal-500 h-full rounded-full transition-all duration-500" 
                 style="width: {{ $group->pet_health ?? 0 }}%"></div>
        </div>
        <p class="text-xs text-gray-400 mt-2">
            Setiap kali anggota kelompok memberi makan, progress kelompok meningkat!
        </p>
    </div>
    
    <!-- Feed Form -->
    <div class="bg-gray-800 rounded-xl p-4 mb-5">
        <h4 class="font-bold mb-3 text-yellow-400">🍖 Beri Makan Pet</h4>
        <form action="{{ route('student.pet.feed') }}" method="POST" id="feedForm">
            @csrf
            <div class="mb-3">
                <label class="block text-sm mb-2">Jumlah Makanan:</label>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="adjustFood(-5)" class="bg-gray-700 w-10 h-10 rounded-lg text-xl">-5</button>
                    <input type="number" name="food_amount" id="food_amount" min="1" max="{{ Auth::user()->food_points }}" 
                           value="5" class="flex-1 bg-gray-700 border-gray-600 text-white text-center rounded-lg py-2">
                    <button type="button" onclick="adjustFood(5)" class="bg-gray-700 w-10 h-10 rounded-lg text-xl">+5</button>
                </div>
            </div>
            <div class="text-sm text-gray-400 mb-4">
                🍖 Food Points tersedia: <span id="foodPoints">{{ Auth::user()->food_points }}</span>
            </div>
            <button type="submit" class="w-full bg-gradient-to-r from-yellow-500 to-orange-500 text-white py-3 rounded-lg font-bold hover:from-yellow-600 hover:to-orange-600 transition">
                🍖 Beri Makan Pet
            </button>
        </form>
    </div>
    
    <!-- Feed History -->
    @php
        $feedHistory = $pet->feedLogs()->with('user')->latest()->take(5)->get() ?? collect();
    @endphp
    @if($feedHistory->count() > 0)
    <div>
        <h4 class="font-bold mb-3 text-left">📋 Riwayat Pemberian Makan Terbaru:</h4>
        <div class="space-y-2 max-h-40 overflow-y-auto">
            @foreach($feedHistory as $feed)
            <div class="flex justify-between items-center text-sm bg-gray-800 p-2 rounded">
                <span>{{ $feed->user->name }}</span>
                <span class="text-yellow-400">+{{ $feed->food_amount }} 🍖</span>
                <span class="text-xs text-gray-500">{{ $feed->created_at->diffForHumans() }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
    function adjustFood(amount) {
        let input = document.getElementById('food_amount');
        let current = parseInt(input.value) || 0;
        let max = parseInt(input.getAttribute('max')) || 999;
        let newValue = current + amount;
        if (newValue >= 1 && newValue <= max) {
            input.value = newValue;
        }
    }
    
    // Validate before submit
    document.getElementById('feedForm')?.addEventListener('submit', function(e) {
        let amount = parseInt(document.getElementById('food_amount').value);
        let max = parseInt(document.getElementById('food_amount').getAttribute('max'));
        if (amount > max) {
            e.preventDefault();
            alert('Food points tidak mencukupi!');
        }
    });
</script>
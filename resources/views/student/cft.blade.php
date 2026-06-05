<div>
    <div class="mb-4 text-center">
        <p class="text-sm text-gray-400">Selesaikan challenge Capture The Flag untuk mendapatkan food points!</p>
        <div class="inline-block bg-gray-800 px-4 py-1 rounded-full mt-2">
            🏆 CFT Selesai: <strong class="text-purple-400">{{ $completedCount ?? 0 }}</strong> / {{ $challenges->count() }}
        </div>
    </div>
    
    <div class="space-y-3">
        @forelse($challenges as $challenge)
        @php
            $isCompleted = $challenge->attempts->where('user_id', Auth::id())->where('is_correct', true)->isNotEmpty();
        @endphp
        <div class="bg-gray-800 rounded-xl p-4 transition-all {{ $isCompleted ? 'opacity-75 border-l-4 border-green-500' : 'border-l-4 border-purple-500 hover:bg-gray-750' }}">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <h3 class="font-bold text-lg">{{ $challenge->title }}</h3>
                        @if($isCompleted)
                            <span class="text-green-500 text-sm">✓ Selesai</span>
                        @else
                            <span class="text-purple-400 text-sm">Belum</span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-400 line-clamp-2">{{ Str::limit($challenge->description, 80) }}</p>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="text-xs bg-yellow-600 px-2 py-1 rounded">🍖 +{{ $challenge->food_reward }}</span>
                        <span class="text-xs bg-purple-600 px-2 py-1 rounded">⭐ +{{ $challenge->points }}</span>
                    </div>
                </div>
                @if(!$isCompleted)
                <button onclick="showChallenge({{ $challenge->id }}, '{{ addslashes($challenge->title) }}', '{{ addslashes($challenge->description) }}')" 
                        class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm transition">
                    Kerjakan →
                </button>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-8">
            <div class="text-6xl mb-3">🏴</div>
            <p class="text-gray-400">Belum ada challenge CFT. Tunggu update dari developer!</p>
        </div>
        @endforelse
    </div>
</div>

<script>
function showChallenge(id, title, description) {
    const contentDiv = document.getElementById('popup-content');
    const titleDiv = document.getElementById('popup-title');
    
    titleDiv.innerText = '🏴 ' + title;
    
    contentDiv.innerHTML = `
        <form action="/student/cft/${id}/submit" method="POST" id="cftForm">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="mb-4">
                <p class="text-gray-300 mb-4">${description}</p>
                <label class="block text-sm mb-2">Jawaban / Flag:</label>
                <input type="text" name="answer" placeholder="Masukkan flag di sini..." 
                       class="w-full bg-gray-700 border-gray-600 text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closePopup()" class="flex-1 bg-gray-700 text-white py-2 rounded-lg">Batal</button>
                <button type="submit" class="flex-1 bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700">Submit Flag</button>
            </div>
        </form>
    `;
    
    document.getElementById('cftForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        try {
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const result = await response.json();
            
            if (result.success) {
                alert('✅ ' + result.message);
                closePopup();
                loadPopup('cft');
            } else {
                alert('❌ ' + result.message);
            }
        } catch (error) {
            alert('Terjadi kesalahan. Silakan coba lagi.');
        }
    });
}
</script>
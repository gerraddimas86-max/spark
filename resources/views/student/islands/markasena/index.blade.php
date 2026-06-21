<div>
    <div class="mb-4">
        <p class="text-sm text-gray-400 text-center">Pengumuman resmi dari mentor untuk kelompok <strong class="text-yellow-400">{{ $groupName ?? Auth::user()->group->name ?? 'Anda' }}</strong></p>
    </div>
    
    <div class="space-y-3">
        @forelse($announcements as $announcement)
        <div class="bg-gray-800 rounded-xl p-4 hover:bg-gray-750 transition cursor-pointer" onclick="showAnnouncement({{ $announcement->id }}, '{{ addslashes($announcement->title) }}', '{{ addslashes($announcement->content) }}', '{{ $announcement->created_at->format('d F Y H:i') }}')">
            <div class="flex justify-between items-start mb-2">
                <h3 class="font-bold text-lg">{{ $announcement->title }}</h3>
                <span class="text-xs text-gray-500">{{ $announcement->created_at->diffForHumans() }}</span>
            </div>
            <p class="text-sm text-gray-400 line-clamp-2">{{ Str::limit($announcement->content, 100) }}</p>
            <div class="flex justify-end mt-2">
                <span class="text-xs text-blue-400">Baca selengkapnya →</span>
            </div>
        </div>
        @empty
        <div class="text-center py-12">
            <div class="text-6xl mb-3">📭</div>
            <p class="text-gray-400">Belum ada pengumuman untuk kelompok Anda</p>
            <p class="text-xs text-gray-500 mt-2">Pantau terus halaman ini untuk info terbaru dari mentor!</p>
        </div>
        @endforelse
    </div>
</div>

<script>
function showAnnouncement(id, title, content, date) {
    const contentDiv = document.getElementById('popup-content');
    const titleDiv = document.getElementById('popup-title');
    
    titleDiv.innerText = '📢 ' + title;
    
    contentDiv.innerHTML = `
        <div class="mb-4">
            <div class="flex justify-between items-center text-sm text-gray-400 mb-4 pb-3 border-b border-gray-700">
                <span>📅 ${date}</span>
                <span>📢 Pengumuman</span>
            </div>
            <div class="text-gray-300 leading-relaxed whitespace-pre-line">
                ${content}
            </div>
        </div>
        <button onclick="closePopup()" class="w-full bg-gray-700 text-white py-2 rounded-lg hover:bg-gray-600 transition mt-2">
            Tutup
        </button>
    `;
}
</script>
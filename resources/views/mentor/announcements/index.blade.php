@extends('layouts.app')

@section('title', 'Pengumuman - SPARK')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">📢 Pengumuman</h1>
                    <a href="{{ route('mentor.announcements.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition">
                        + Buat Pengumuman
                    </a>
                </div>
                
                <!-- Filter -->
                <div class="mb-4">
                    <select id="groupFilter" class="px-4 py-2 border rounded-lg w-full md:w-64">
                        <option value="">Semua Kelompok</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Announcements List -->
                <div class="space-y-4">
                    @forelse($announcements as $announcement)
                    <div class="border rounded-lg p-4 hover:shadow-md transition" data-group-id="{{ $announcement->group_id }}">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-bold text-lg">{{ $announcement->title }}</h3>
                                <p class="text-sm text-gray-500">
                                    Kelompok: <span class="font-medium">{{ $announcement->group->name }}</span>
                                    • Dibuat: {{ $announcement->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <div class="space-x-2">
                                <a href="{{ route('mentor.announcements.show', $announcement->id) }}" class="text-blue-600 hover:text-blue-800">Lihat</a>
                                <a href="{{ route('mentor.announcements.edit', $announcement->id) }}" class="text-yellow-600 hover:text-yellow-800">Edit</a>
                                <form action="{{ route('mentor.announcements.destroy', $announcement->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus pengumuman ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                                </form>
                            </div>
                        </div>
                        <p class="text-gray-700 mt-2">{{ Str::limit($announcement->content, 150) }}</p>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500">
                        Belum ada pengumuman. <a href="{{ route('mentor.announcements.create') }}" class="text-blue-600">Buat pengumuman pertama</a>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('groupFilter')?.addEventListener('change', function() {
        let groupId = this.value;
        let items = document.querySelectorAll('.border.rounded-lg.p-4');
        items.forEach(item => {
            if (!groupId || item.dataset.groupId == groupId) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });
</script>
@endsection
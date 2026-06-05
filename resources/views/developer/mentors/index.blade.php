@extends('layouts.app')

@section('title', 'Manajemen Mentor - SPARK')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">👨‍🏫 Manajemen Mentor</h1>
                    <a href="{{ route('developer.mentors.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition">
                        + Tambah Mentor
                    </a>
                </div>
                
                <!-- Search -->
                <div class="mb-4">
                    <input type="text" id="search" placeholder="Cari mentor..." class="w-full md:w-1/3 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelompok Bimbingan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($mentors as $mentor)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm">{{ $mentor->id }}</td>
                                <td class="px-6 py-4 text-sm font-medium">{{ $mentor->name }}</td>
                                <td class="px-6 py-4 text-sm">{{ $mentor->email }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @foreach($mentor->mentorGroups as $group)
                                        <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mr-1 mb-1">{{ $group->name }}</span>
                                    @endforeach
                                </td>
                                <td class="px-6 py-4 text-sm space-x-2">
                                    <a href="{{ route('developer.mentors.edit', $mentor->id) }}" class="text-yellow-600 hover:text-yellow-900">✏️ Edit</a>
                                    <form action="{{ route('developer.mentors.destroy', $mentor->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus mentor ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 ml-2">🗑️ Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada data mentor</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('search')?.addEventListener('keyup', function() {
        let searchText = this.value.toLowerCase();
        let rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(searchText) ? '' : 'none';
        });
    });
</script>
@endsection
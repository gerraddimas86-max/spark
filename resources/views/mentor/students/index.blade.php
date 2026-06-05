@extends('layouts.app')

@section('title', 'Manajemen Mahasiswa - SPARK')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">👨‍🎓 Manajemen Mahasiswa Bimbingan</h1>
                    <div class="space-x-2">
                        <a href="{{ route('mentor.students.import.form') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            📤 Import Excel
                        </a>
                        <a href="{{ route('mentor.students.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            + Tambah Manual
                        </a>
                    </div>
                </div>
                
                <!-- Filter & Search -->
                <div class="flex flex-wrap gap-4 mb-4">
                    <input type="text" id="search" placeholder="Cari NIM atau Nama..." class="px-4 py-2 border rounded-lg w-full md:w-64">
                    <select id="groupFilter" class="px-4 py-2 border rounded-lg">
                        <option value="">Semua Kelompok</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">NIM</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelompok</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Food Points</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quest Selesai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">CFT Selesai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($students as $student)
                            <tr data-group-id="{{ $student->group_id }}">
                                <td class="px-6 py-4 text-sm">{{ $student->nim }}</td>
                                <td class="px-6 py-4 text-sm font-medium">{{ $student->name }}</td>
                                <td class="px-6 py-4 text-sm">{{ $student->group->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm">🍖 {{ $student->food_points }}</td>
                                <td class="px-6 py-4 text-sm">
                                    {{ $student->userQuests()->where('is_completed', true)->whereDate('completed_date', today())->count() }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    {{ $student->cftAttempts()->where('is_correct', true)->count() }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $student->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $student->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm space-x-2">
                                    <a href="{{ route('mentor.students.edit', $student->id) }}" class="text-yellow-600 hover:text-yellow-900">✏️ Edit</a>
                                    <form action="{{ route('mentor.students.destroy', $student->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus mahasiswa ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 ml-2">🗑️ Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                    Belum ada mahasiswa bimbingan. 
                                    <a href="{{ route('mentor.students.create') }}" class="text-blue-600">Tambah mahasiswa</a> atau 
                                    <a href="{{ route('mentor.students.import.form') }}" class="text-green-600">import Excel</a>
                                </td>
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
    function filterTable() {
        let searchText = document.getElementById('search').value.toLowerCase();
        let groupFilter = document.getElementById('groupFilter').value;
        let rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            let groupMatch = !groupFilter || row.dataset.groupId == groupFilter;
            let searchMatch = text.includes(searchText);
            row.style.display = (groupMatch && searchMatch) ? '' : 'none';
        });
    }
    
    document.getElementById('search')?.addEventListener('keyup', filterTable);
    document.getElementById('groupFilter')?.addEventListener('change', filterTable);
</script>
@endsection
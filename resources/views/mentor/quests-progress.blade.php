@extends('layouts.app')

@section('title', 'Progress Quest - SPARK')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">📜 Progress Quest Mahasiswa</h1>
                    <div class="text-sm text-gray-500">
                        Tanggal: {{ now()->format('d F Y') }}
                    </div>
                </div>
                
                <!-- Filter -->
                <div class="flex flex-wrap gap-4 mb-6">
                    <select id="groupFilter" class="px-4 py-2 border rounded-lg">
                        <option value="">Semua Kelompok</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                    <select id="questFilter" class="px-4 py-2 border rounded-lg">
                        <option value="">Semua Quest</option>
                        @foreach($quests as $quest)
                            <option value="{{ $quest->id }}">{{ $quest->title }}</option>
                        @endforeach
                    </select>
                    <button onclick="resetFilters()" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">Reset Filter</button>
                </div>
                
                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-blue-50 p-4 rounded-lg text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $total_students ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Total Mahasiswa</div>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $completed_today ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Quest Selesai Hari Ini</div>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg text-center">
                        <div class="text-2xl font-bold text-yellow-600">{{ $completion_rate ?? 0 }}%</div>
                        <div class="text-sm text-gray-600">Rata-rata Completion</div>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg text-center">
                        <div class="text-2xl font-bold text-purple-600">{{ $total_food_given ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Total Food Diberikan</div>
                    </div>
                </div>
                
                <!-- Progress Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mahasiswa</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kelompok</th>
                                @foreach($quests as $quest)
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                    {{ Str::limit($quest->title, 15) }}
                                </th>
                                @endforeach
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($students as $student)
                            <tr data-group-id="{{ $student->group_id }}">
                                <td class="px-4 py-3 text-sm">
                                    <div class="font-medium">{{ $student->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $student->nim }}</div>
                                </td>
                                <td class="px-4 py-3 text-sm">{{ $student->group->name ?? '-' }}</td>
                                @php
                                    $completedCount = 0;
                                @endphp
                                @foreach($quests as $quest)
                                    @php
                                        $isCompleted = $student->userQuests
                                            ->where('quest_id', $quest->id)
                                            ->whereDate('quest_date', today())
                                            ->where('is_completed', true)
                                            ->isNotEmpty();
                                        if($isCompleted) $completedCount++;
                                    @endphp
                                    <td class="px-4 py-3 text-center">
                                        @if($isCompleted)
                                            <span class="text-green-600 text-xl">✅</span>
                                        @else
                                            <span class="text-gray-400 text-xl">⭕</span>
                                        @endif
                                    </td>
                                @endforeach
                                <td class="px-4 py-3 text-center font-bold">
                                    {{ $completedCount }}/{{ $quests->count() }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ 3 + $quests->count() }}" class="px-4 py-8 text-center text-gray-500">
                                    Belum ada data mahasiswa
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
    function resetFilters() {
        document.getElementById('groupFilter').value = '';
        document.getElementById('questFilter').value = '';
        filterTable();
    }
    
    function filterTable() {
        let groupFilter = document.getElementById('groupFilter').value;
        let rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            let groupMatch = !groupFilter || row.dataset.groupId == groupFilter;
            row.style.display = groupMatch ? '' : 'none';
        });
    }
    
    document.getElementById('groupFilter')?.addEventListener('change', filterTable);
</script>
@endsection
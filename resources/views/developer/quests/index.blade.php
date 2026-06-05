@extends('layouts.app')

@section('title', 'Manajemen Quest - SPARK')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">📜 Manajemen Quest</h1>
                    <a href="{{ route('developer.quests.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition">
                        + Tambah Quest
                    </a>
                </div>
                
                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reward</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Daily</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($quests as $quest)
                            <tr>
                                <td class="px-6 py-4 text-sm">{{ $quest->id }}</td>
                                <td class="px-6 py-4 text-sm font-medium">{{ $quest->title }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        @if($quest->type == 'login') bg-green-100 text-green-800
                                        @elseif($quest->type == 'cft') bg-blue-100 text-blue-800
                                        @elseif($quest->type == 'feed_pet') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $quest->type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">🍖 {{ $quest->food_reward }}</td>
                                <td class="px-6 py-4 text-sm">
                                    {{ $quest->is_daily ? '✅ Harian' : '📅 Sekali' }}
                                </td>
                                <td class="px-6 py-4 text-sm space-x-2">
                                    <a href="{{ route('developer.quests.edit', $quest->id) }}" class="text-yellow-600 hover:text-yellow-900">✏️ Edit</a>
                                    <form action="{{ route('developer.quests.destroy', $quest->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus quest ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 ml-2">🗑️ Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada quest. <a href="{{ route('developer.quests.create') }}" class="text-blue-600">Buat quest pertama</a></td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
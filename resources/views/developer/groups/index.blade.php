@extends('layouts.app')

@section('title', 'Manajemen Kelompok - SPARK')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">🏠 Manajemen Kelompok</h1>
                    <a href="{{ route('developer.groups.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition">
                        + Tambah Kelompok
                    </a>
                </div>
                
                <!-- Grid Groups -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($groups as $group)
                    <div class="border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition">
                        <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-4 text-white">
                            <h3 class="font-bold text-lg">{{ $group->name }}</h3>
                            <p class="text-sm opacity-90">Kode: {{ $group->code }}</p>
                        </div>
                        <div class="p-4">
                            <div class="mb-3">
                                <div class="flex justify-between text-sm mb-1">
                                    <span>Progress Pet</span>
                                    <span>{{ $group->pet_health }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 rounded-full h-2" style="width: {{ $group->pet_health }}%"></div>
                                </div>
                            </div>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">👨‍🎓 Mahasiswa:</span>
                                    <span class="font-bold">{{ $group->students->count() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">👨‍🏫 Mentor:</span>
                                    <span class="font-bold">{{ $group->mentors->count() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">🐾 Pet Level:</span>
                                    <span class="font-bold">{{ $group->pet->level ?? 1 }}</span>
                                </div>
                            </div>
                            <div class="flex justify-end space-x-2 mt-4 pt-3 border-t">
                                <a href="{{ route('developer.groups.edit', $group->id) }}" class="text-yellow-600 hover:text-yellow-800 text-sm">✏️ Edit</a>
                                <form action="{{ route('developer.groups.destroy', $group->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus kelompok ini? Semua mahasiswa dan pet akan terhapus.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">🗑️ Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center py-8 text-gray-500">
                        Belum ada kelompok. <a href="{{ route('developer.groups.create') }}" class="text-blue-600">Buat kelompok pertama</a>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
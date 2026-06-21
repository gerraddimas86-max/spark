@extends('layouts.app')

@section('title', 'Edit Kelompok - SPARK')

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">✏️ Edit Kelompok & Pet</h1>
                    <a href="{{ route('developer.groups.index') }}" class="text-gray-600 hover:text-gray-900">← Kembali</a>
                </div>
                
                <form action="{{ route('developer.groups.update', $group->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Informasi Kelompok -->
                    <div class="border-b pb-4 mb-4">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">🏠 Informasi Kelompok</h2>
                        
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Kelompok *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $group->name) }}" 
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                                   required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Kode Kelompok *</label>
                            <input type="text" name="code" id="code" value="{{ old('code', $group->code) }}" 
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('code') border-red-500 @enderror"
                                   required>
                            @error('code')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Informasi Pet -->
                    <div class="border-b pb-4 mb-4">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">🐾 Informasi Pet</h2>
                        
                        @if($group->pet)
                            <div class="mb-4">
                                <label for="pet_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Pet *</label>
                                <input type="text" name="pet_name" id="pet_name" value="{{ old('pet_name', $group->pet->name) }}" 
                                       class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('pet_name') border-red-500 @enderror"
                                       required>
                                <p class="text-xs text-gray-500 mt-1">Nama pet yang akan ditampilkan kepada mahasiswa.</p>
                                @error('pet_name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Pet</label>
                                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                    <span class="text-2xl">{{ $group->pet->emoji }}</span>
                                    <span class="text-gray-700 capitalize">{{ $group->pet->type }}</span>
                                    <span class="text-xs text-gray-500">(Tidak dapat diubah)</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Tipe pet menentukan tampilan gambar dan emoji pet.</p>
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Stage Pet Saat Ini</label>
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-sm 
                                    {{ $group->pet->stage == 'egg' ? 'bg-gray-200 text-gray-700' : 
                                       ($group->pet->stage == 'baby' ? 'bg-blue-100 text-blue-700' : 
                                       ($group->pet->stage == 'adult' ? 'bg-purple-100 text-purple-700' : 
                                       'bg-yellow-100 text-yellow-700')) }}">
                                    <span>{{ $group->pet->stage_name }}</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    Stage akan berubah otomatis berdasarkan level pet:
                                    Level 0 = Telur, Level 1-4 = Bayi, Level 5-9 = Dewasa, Level 10+ = Legendaris
                                </p>
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Level & Experience</label>
                                <div class="flex gap-4">
                                    <div class="flex-1 p-2 bg-gray-50 rounded text-center">
                                        <div class="text-2xl font-bold text-blue-600">{{ $group->pet->level }}</div>
                                        <div class="text-xs text-gray-500">Level</div>
                                    </div>
                                    <div class="flex-1 p-2 bg-gray-50 rounded text-center">
                                        <div class="text-2xl font-bold text-green-600">{{ $group->pet->experience }}</div>
                                        <div class="text-xs text-gray-500">Experience / 100</div>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    💡 Pet akan naik level setiap mendapat 100 experience dari pemberian makan.
                                </p>
                            </div>
                        @else
                            <div class="bg-red-50 p-4 rounded-lg text-red-700">
                                <p>⚠️ Pet belum tersedia untuk kelompok ini.</p>
                            </div>
                        @endif
                    </div>
                    
                    <div class="bg-yellow-50 p-4 rounded-lg mb-4">
                        <p class="text-sm text-yellow-800">
                            ⚠️ <strong>Perhatian:</strong> Perubahan kode kelompok tidak akan mempengaruhi data mahasiswa yang sudah ada.
                        </p>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <a href="{{ route('developer.groups.index') }}" class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">Update Kelompok & Pet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
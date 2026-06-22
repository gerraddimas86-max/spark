@extends('layouts.app')

@section('title', 'Tambah CFT Challenge - SPARK')

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">🏴 Tambah CFT Challenge Baru</h1>
                    <a href="{{ route('developer.cft.index') }}" class="text-gray-600 hover:text-gray-900">← Kembali</a>
                </div>
                
                <form action="{{ route('developer.cft.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Challenge *</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" 
                               placeholder="Contoh: Base64 Basics"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('title') border-red-500 @enderror"
                               required>
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Challenge *</label>
                        <textarea name="description" id="description" rows="4" 
                                  placeholder="Jelaskan tantangan yang harus diselesaikan mahasiswa..."
                                  class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                                  required>{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="flag" class="block text-sm font-medium text-gray-700 mb-1">Flag / Jawaban *</label>
                        <input type="text" name="flag" id="flag" value="{{ old('flag') }}" 
                               placeholder="Contoh: SPARK{flag_here} atau jawaban exact"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('flag') border-red-500 @enderror"
                               required>
                        <p class="text-xs text-gray-500 mt-1">Flag bersifat case-sensitive. Mahasiswa harus memasukkan string ini persis.</p>
                        @error('flag')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="food_reward" class="block text-sm font-medium text-gray-700 mb-1">Reward Makanan *</label>
                            <input type="number" name="food_reward" id="food_reward" value="{{ old('food_reward', 10) }}" 
                                   min="1" max="100"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('food_reward') border-red-500 @enderror"
                                   required>
                            @error('food_reward')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="points" class="block text-sm font-medium text-gray-700 mb-1">Points (Leaderboard) *</label>
                            <input type="number" name="points" id="points" value="{{ old('points', 100) }}" 
                                   min="0" max="1000"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('points') border-red-500 @enderror"
                                   required>
                            @error('points')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Aktifkan Challenge</span>
                        </label>
                        <p class="text-xs text-gray-500 mt-1">Jika tidak aktif, challenge tidak akan terlihat oleh mahasiswa.</p>
                    </div>
                    
                    <div class="bg-yellow-50 p-4 rounded-lg mb-4">
                        <p class="text-sm text-yellow-800">
                            💡 <strong>Tips membuat CFT:</strong><br>
                            • Base64: Berikan string terenkripsi base64<br>
                            • Caesar Cipher: Berikan teks terenkripsi dengan shift tertentu<br>
                            • Inspect Element: Sembunyikan flag di komentar HTML<br>
                            • Hidden Link: Sembunyikan tautan dengan CSS<br>
                            • Math: Berikan soal matematika sederhana
                        </p>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <a href="{{ route('developer.cft.index') }}" class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan Challenge</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Tambah Kelompok - SPARK')

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">🏠 Tambah Kelompok Baru</h1>
                    <a href="{{ route('developer.groups.index') }}" class="text-gray-600 hover:text-gray-900">← Kembali</a>
                </div>
                
                <form action="{{ route('developer.groups.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Kelompok *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" 
                               placeholder="Contoh: Bajak Laut Merah"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                               required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Kode Kelompok *</label>
                        <input type="text" name="code" id="code" value="{{ old('code') }}" 
                               placeholder="Contoh: BLM01"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('code') border-red-500 @enderror"
                               required>
                        <p class="text-xs text-gray-500 mt-1">Kode unik untuk identifikasi kelompok (tanpa spasi).</p>
                        @error('code')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="bg-blue-50 p-4 rounded-lg mb-4">
                        <p class="text-sm text-blue-800">
                            💡 <strong>Info:</strong> Setiap kelompok akan otomatis mendapat 1 Pet saat dibuat.
                        </p>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <a href="{{ route('developer.groups.index') }}" class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Buat Kelompok</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
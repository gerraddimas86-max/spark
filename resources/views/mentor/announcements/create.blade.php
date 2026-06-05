@extends('layouts.app')

@section('title', 'Buat Pengumuman - SPARK')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">📢 Buat Pengumuman Baru</h1>
                    <a href="{{ route('mentor.announcements.index') }}" class="text-gray-600 hover:text-gray-900">← Kembali</a>
                </div>
                
                <form action="{{ route('mentor.announcements.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Pengumuman *</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" 
                               placeholder="Contoh: Informasi PKKMB Hari Pertama"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('title') border-red-500 @enderror"
                               required>
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="group_id" class="block text-sm font-medium text-gray-700 mb-1">Kelompok Tujuan *</label>
                        <select name="group_id" id="group_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('group_id') border-red-500 @enderror" required>
                            <option value="">Pilih Kelompok</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>
                                    {{ $group->name }} ({{ $group->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('group_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Isi Pengumuman *</label>
                        <textarea name="content" id="content" rows="6" 
                                  placeholder="Tulis isi pengumuman di sini..."
                                  class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('content') border-red-500 @enderror"
                                  required>{{ old('content') }}</textarea>
                        @error('content')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="bg-yellow-50 p-4 rounded-lg mb-4">
                        <p class="text-sm text-yellow-800">
                            💡 <strong>Tips:</strong> Pengumuman akan langsung terlihat oleh mahasiswa di kelompok yang dipilih.
                        </p>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <a href="{{ route('mentor.announcements.index') }}" class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Publikasikan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
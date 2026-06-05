@extends('layouts.app')

@section('title', 'Edit Pengumuman - SPARK')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">✏️ Edit Pengumuman</h1>
                    <a href="{{ route('mentor.announcements.index') }}" class="text-gray-600 hover:text-gray-900">← Kembali</a>
                </div>
                
                <form action="{{ route('mentor.announcements.update', $announcement->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Pengumuman *</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $announcement->title) }}" 
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
                                <option value="{{ $group->id }}" {{ old('group_id', $announcement->group_id) == $group->id ? 'selected' : '' }}>
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
                                  class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('content') border-red-500 @enderror"
                                  required>{{ old('content', $announcement->content) }}</textarea>
                        @error('content')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <a href="{{ route('mentor.announcements.index') }}" class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">Update Pengumuman</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
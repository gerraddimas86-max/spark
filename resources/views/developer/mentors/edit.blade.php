@extends('layouts.app')

@section('title', 'Edit Mentor - SPARK')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">✏️ Edit Mentor</h1>
                    <a href="{{ route('developer.mentors.index') }}" class="text-gray-600 hover:text-gray-900">← Kembali</a>
                </div>
                
                <form action="{{ route('developer.mentors.update', $mentor->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $mentor->name) }}" 
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                               required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $mentor->email) }}" 
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                               required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password (kosongkan jika tidak diubah)</label>
                        <input type="password" name="password" id="password" 
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <p class="text-xs text-gray-500 mt-1">Minimal 6 karakter.</p>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kelompok Bimbingan</label>
                        <div class="border rounded-lg p-3 max-h-48 overflow-y-auto">
                            @forelse($groups as $group)
                                <label class="flex items-center space-x-2 mb-2">
                                    <input type="checkbox" name="group_ids[]" value="{{ $group->id }}" 
                                           {{ in_array($group->id, $mentor->mentorGroups->pluck('id')->toArray()) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span>{{ $group->name }} ({{ $group->code }})</span>
                                </label>
                            @empty
                                <p class="text-gray-500 text-sm">Belum ada kelompok.</p>
                            @endforelse
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <a href="{{ route('developer.mentors.index') }}" class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">Update Mentor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
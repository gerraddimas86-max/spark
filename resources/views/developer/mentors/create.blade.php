@extends('layouts.app')

@section('title', 'Tambah Mentor - SPARK')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">➕ Tambah Mentor Baru</h1>
                    <a href="{{ route('developer.mentors.index') }}" class="text-gray-600 hover:text-gray-900">← Kembali</a>
                </div>
                
                <form action="{{ route('developer.mentors.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" 
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                               required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" 
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                               required>
                        <p class="text-xs text-gray-500 mt-1">Gunakan email institusi atau pribadi.</p>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                        <input type="password" name="password" id="password" 
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('password') border-red-500 @enderror"
                               required>
                        <p class="text-xs text-gray-500 mt-1">Minimal 6 karakter.</p>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kelompok Bimbingan (dapat pilih lebih dari satu)</label>
                        <div class="border rounded-lg p-3 max-h-48 overflow-y-auto">
                            @forelse($groups as $group)
                                <label class="flex items-center space-x-2 mb-2">
                                    <input type="checkbox" name="group_ids[]" value="{{ $group->id }}" 
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span>{{ $group->name }} ({{ $group->code }})</span>
                                </label>
                            @empty
                                <p class="text-gray-500 text-sm">Belum ada kelompok. <a href="{{ route('developer.groups.create') }}" class="text-blue-600">Buat kelompok dulu</a></p>
                            @endforelse
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <a href="{{ route('developer.mentors.index') }}" class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan Mentor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
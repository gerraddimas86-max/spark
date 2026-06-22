@extends('layouts.app')

@section('title', 'Edit Mahasiswa - SPARK')

@section('content')
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h1 class="text-2xl font-bold text-gray-900">✏️ Edit Mahasiswa</h1>
                        <a href="{{ route('developer.students.index') }}" class="text-gray-600 hover:text-gray-900">←
                            Kembali</a>
                    </div>

                    <form action="{{ route('developer.students.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap
                                *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                                required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="nim" class="block text-sm font-medium text-gray-700 mb-1">NIM *</label>
                            <input type="text" name="nim" id="nim" value="{{ old('nim', $user->nim) }}"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('nim') border-red-500 @enderror"
                                required>
                            @error('nim')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="group_id" class="block text-sm font-medium text-gray-700 mb-1">Kelompok *</label>
                            <select name="group_id" id="group_id"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('group_id') border-red-500 @enderror"
                                required>
                                <option value="">Pilih Kelompok</option>
                                @foreach ($groups as $group)
                                    <option value="{{ $group->id }}"
                                        {{ old('group_id', $user->group_id) == $group->id ? 'selected' : '' }}>
                                        {{ $group->name }} ({{ $group->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('group_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password (kosongkan
                                jika tidak diubah)</label>
                            <input type="password" name="password" id="password"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div class="mb-4">
                            <label class="flex items-center space-x-2">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1"
                                    {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm text-gray-700">Aktifkan akun ini</span>
                            </label>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6">
                            <a href="{{ route('developer.students.index') }}"
                                class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">Batal</a>
                            <button type="submit"
                                class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">Update
                                Mahasiswa</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

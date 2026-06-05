@extends('layouts.app')

@section('title', 'Import Mahasiswa - SPARK')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">📤 Import Mahasiswa (Excel)</h1>
                    <a href="{{ route('mentor.students.index') }}" class="text-gray-600 hover:text-gray-900">← Kembali</a>
                </div>
                
                <div class="bg-blue-50 p-4 rounded-lg mb-6">
                    <h3 class="font-bold text-blue-800 mb-2">📋 Panduan Format Excel:</h3>
                    <ul class="text-sm text-blue-700 list-disc list-inside space-y-1">
                        <li>File harus berekstensi .xlsx atau .csv</li>
                        <li>Kolom yang wajib ada: <strong>name</strong>, <strong>nim</strong> (urutan bebas)</li>
                        <li>Contoh baris: John Doe | 09011282328001</li>
                        <li>Password default untuk mahasiswa: <strong>spark123</strong></li>
                        <li>Mahasiswa akan dimasukkan ke kelompok yang Anda pilih di bawah</li>
                    </ul>
                </div>
                
                <div class="bg-yellow-50 p-4 rounded-lg mb-6">
                    <p class="text-sm text-yellow-800">
                        💡 <strong>Tips:</strong> Pastikan tidak ada NIM yang duplikat dengan data yang sudah ada.
                        Jika ada duplikat, data akan dilewati.
                    </p>
                </div>
                
                <form action="{{ route('mentor.students.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="group_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Kelompok *</label>
                        <select name="group_id" id="group_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">-- Pilih Kelompok --</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }} ({{ $group->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="file" class="block text-sm font-medium text-gray-700 mb-1">File Excel *</label>
                        <input type="file" name="file" id="file" accept=".xlsx,.csv,.xls"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               required>
                        <p class="text-xs text-gray-500 mt-1">Maksimal 2MB</p>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <a href="{{ route('mentor.students.index') }}" class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            📤 Upload & Import
                        </button>
                    </div>
                </form>
                
                <div class="mt-6 text-center">
                    <a href="#" class="text-blue-600 text-sm hover:underline">📥 Download Template Excel</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
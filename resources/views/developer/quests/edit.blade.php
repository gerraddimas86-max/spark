@extends('layouts.app')

@section('title', 'Edit Quest - SPARK')

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">✏️ Edit Quest</h1>
                    <a href="{{ route('developer.quests.index') }}" class="text-gray-600 hover:text-gray-900">← Kembali</a>
                </div>
                
                <form action="{{ route('developer.quests.update', $quest->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Judul Quest *</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $quest->title) }}" 
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('title') border-red-500 @enderror"
                               required>
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi *</label>
                        <textarea name="description" id="description" rows="3" 
                                  class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                                  required>{{ old('description', $quest->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Tipe Quest *</label>
                        <select name="type" id="type" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('type') border-red-500 @enderror" required>
                            <option value="login" {{ old('type', $quest->type) == 'login' ? 'selected' : '' }}>Login Harian</option>
                            <option value="cft" {{ old('type', $quest->type) == 'cft' ? 'selected' : '' }}>Menyelesaikan CFT</option>
                            <option value="feed_pet" {{ old('type', $quest->type) == 'feed_pet' ? 'selected' : '' }}>Memberi Makan Pet</option>
                            <option value="read_announcement" {{ old('type', $quest->type) == 'read_announcement' ? 'selected' : '' }}>Membaca Pengumuman</option>
                            <option value="custom" {{ old('type', $quest->type) == 'custom' ? 'selected' : '' }}>Custom (khusus)</option>
                        </select>
                        @error('type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="food_reward" class="block text-sm font-medium text-gray-700 mb-1">Reward Makanan *</label>
                        <input type="number" name="food_reward" id="food_reward" value="{{ old('food_reward', $quest->food_reward) }}" 
                               min="1" max="100"
                               class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('food_reward') border-red-500 @enderror"
                               required>
                        @error('food_reward')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="is_daily" value="1" {{ old('is_daily', $quest->is_daily) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Quest Harian (reset setiap hari)</span>
                        </label>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <a href="{{ route('developer.quests.index') }}" class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">Batal</a>
                        <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">Update Quest</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
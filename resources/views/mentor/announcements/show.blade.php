@extends('layouts.app')

@section('title', $announcement->title . ' - SPARK')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <a href="{{ route('mentor.announcements.index') }}" class="text-gray-600 hover:text-gray-900">← Kembali ke Pengumuman</a>
                    <div class="space-x-2">
                        <a href="{{ route('mentor.announcements.edit', $announcement->id) }}" class="text-yellow-600 hover:text-yellow-800">✏️ Edit</a>
                        <form action="{{ route('mentor.announcements.destroy', $announcement->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus pengumuman ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">🗑️ Hapus</button>
                        </form>
                    </div>
                </div>
                
                <div class="border-b pb-4 mb-4">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $announcement->title }}</h1>
                    <div class="flex flex-wrap gap-4 mt-2 text-sm text-gray-500">
                        <span>📢 Kelompok: <strong>{{ $announcement->group->name }}</strong></span>
                        <span>👤 Dibuat oleh: <strong>{{ $announcement->creator->name }}</strong></span>
                        <span>📅 {{ $announcement->created_at->format('d F Y, H:i') }}</span>
                    </div>
                </div>
                
                <div class="prose max-w-none">
                    <div class="whitespace-pre-line text-gray-700 leading-relaxed">
                        {{ $announcement->content }}
                    </div>
                </div>
                
                <div class="mt-6 pt-4 border-t text-sm text-gray-500">
                    <p>Terakhir diperbarui: {{ $announcement->updated_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
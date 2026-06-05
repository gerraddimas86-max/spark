@extends('layouts.app')

@section('title', 'Progress Pet - SPARK')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">🐾 Progress Pet Kelompok</h1>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($groups as $group)
                    <div class="border rounded-lg overflow-hidden shadow-sm">
                        <div class="bg-gradient-to-r from-green-600 to-teal-600 p-4 text-white">
                            <h3 class="font-bold text-lg">{{ $group->name }}</h3>
                            <p class="text-sm opacity-90">Kode: {{ $group->code }}</p>
                        </div>
                        <div class="p-4">
                            <!-- Pet Display -->
                            <div class="text-center mb-4">
                                @php
                                    $petLevel = $group->pet->level ?? 1;
                                    $petEmoji = $petLevel < 3 ? '🐣' : ($petLevel < 6 ? '🐥' : '🦅');
                                @endphp
                                <div class="text-7xl mb-2">{{ $petEmoji }}</div>
                                <div class="font-bold text-lg">{{ $group->pet->name ?? 'Baby Pet' }}</div>
                                <div class="text-sm text-gray-600">Level {{ $petLevel }} | Exp {{ $group->pet->experience ?? 0 }}/100</div>
                            </div>
                            
                            <!-- Experience Bar -->
                            <div class="mb-4">
                                <div class="flex justify-between text-sm mb-1">
                                    <span>Experience</span>
                                    <span>{{ ($group->pet->experience ?? 0) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-blue-500 rounded-full h-3" style="width: {{ ($group->pet->experience ?? 0) }}%"></div>
                                </div>
                            </div>
                            
                            <!-- Group Pet Health -->
                            <div class="mb-4">
                                <div class="flex justify-between text-sm mb-1">
                                    <span>Pet Health Kelompok</span>
                                    <span>{{ $group->pet_health }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-green-500 rounded-full h-3" style="width: {{ $group->pet_health }}%"></div>
                                </div>
                            </div>
                            
                            <!-- Stats -->
                            <div class="grid grid-cols-2 gap-3 mt-4 pt-3 border-t">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-blue-600">{{ $group->students->count() }}</div>
                                    <div class="text-xs text-gray-500">Mahasiswa</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-green-600">{{ $group->pet->feedLogs->count() ?? 0 }}</div>
                                    <div class="text-xs text-gray-500">Total Feed</div>
                                </div>
                            </div>
                            
                            <!-- Recent Feeders -->
                            @php
                                $recentFeeds = $group->pet->feedLogs()->with('user')->latest()->take(5)->get() ?? collect();
                            @endphp
                            @if($recentFeeds->count() > 0)
                            <div class="mt-4 pt-3 border-t">
                                <p class="text-sm font-semibold mb-2">🍖 Pemberi Makan Terbaru:</p>
                                <div class="space-y-1">
                                    @foreach($recentFeeds as $feed)
                                    <div class="flex justify-between text-xs">
                                        <span>{{ $feed->user->name }}</span>
                                        <span class="text-gray-500">{{ $feed->food_amount }} 🍖 • {{ $feed->created_at->diffForHumans() }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center py-8 text-gray-500">
                        Belum ada kelompok bimbingan
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
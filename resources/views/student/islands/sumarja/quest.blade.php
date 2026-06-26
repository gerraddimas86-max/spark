<!-- ============================================================ -->
<!-- FILE 2: resources/views/student/islands/sumarja/quest.blade.php -->
<!-- ============================================================ -->
<div class="quest-modal" id="questModal">
    <div class="quest-modal-content">
        <!-- Header Modal -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <i class="fas fa-list-check text-white/60"></i>
                <h2 class="text-lg font-semibold text-white">Quest Harian</h2>
            </div>
            <button onclick="closeQuests()" class="modal-close-btn text-white/30 hover:text-white text-xl transition-all duration-300">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Quest Stats -->
        <div class="flex items-center gap-4 text-sm text-white/40 mb-6">
            <span>Progress: <strong class="text-white">{{ $questCompletedCount ?? 0 }}</strong> / {{ $questTotalCount ?? 0 }}</span>
            <span class="w-px h-4 bg-white/10"></span>
            <span>Reward: <strong class="text-white">{{ $questTotalReward ?? 0 }}</strong> FP</span>
        </div>

        <!-- Quest List -->
        <div class="space-y-3">
            @forelse($quests ?? [] as $quest)
            <div class="quest-item {{ $quest->is_completed ? 'completed' : '' }}">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-medium text-white">{{ $quest->title }}</span>
                            @if($quest->is_completed)
                                <span class="text-xs text-green-400"><i class="fas fa-check-circle"></i></span>
                            @endif
                        </div>
                        <p class="text-xs text-white/30 font-light truncate">{{ $quest->description ?? '' }}</p>
                        <div class="flex items-center gap-3 mt-1">
                            <span class="text-xs text-white/20">+{{ $quest->reward_food_points ?? $quest->food_reward ?? 10 }} FP</span>
                            <span class="text-xs text-white/20">Progress: {{ $quest->progress ?? 0 }}/{{ $quest->target ?? 1 }}</span>
                        </div>
                    </div>
                    @if(!$quest->is_completed)
                        <button onclick="completeQuest('{{ $quest->id }}')" 
                                class="text-xs text-white/30 hover:text-white px-3 py-1 rounded-full border border-white/10 hover:border-white/30 transition-all duration-300">
                            Selesaikan
                        </button>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center text-white/30 py-8">
                <i class="fas fa-inbox text-2xl block mb-2 opacity-30"></i>
                <p class="text-sm">Belum ada quest tersedia</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    /* Quest Modal Styles */
    .quest-modal {
        display: none; /* HARUS display: none */
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(10px);
        z-index: 100;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .quest-modal.active {
        display: flex; /* Muncul kalau ada class active */
    }

    .quest-modal-content {
        background: rgba(20, 20, 30, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        padding: 40px;
        max-width: 600px;
        width: 100%;
        max-height: 80vh;
        overflow-y: auto;
        animation: modalIn 0.3s ease;
    }

    @keyframes modalIn {
        from {
            opacity: 0;
            transform: scale(0.95) translateY(20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .quest-item {
        padding: 16px;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.06);
        transition: all 0.3s ease;
    }

    .quest-item:hover {
        background: rgba(255, 255, 255, 0.05);
    }

    .quest-item.completed {
        border-color: rgba(74, 222, 128, 0.2);
        background: rgba(74, 222, 128, 0.05);
    }

    .modal-close-btn {
        transition: all 0.3s ease;
    }

    .modal-close-btn:hover {
        transform: rotate(90deg);
    }

    /* Scrollbar styling */
    .quest-modal-content::-webkit-scrollbar {
        width: 4px;
    }

    .quest-modal-content::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 10px;
    }

    .quest-modal-content::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
    }
</style>
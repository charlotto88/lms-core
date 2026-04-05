<div>
    <div class="mt-12 pt-8 border-t border-gray-100 flex justify-center">
        <button wire:click="toggleComplete" 
                style="background-color: {{ $isCompleted ? '#2d2f31' : '#a435f0' }};"
                class="flex items-center gap-2 px-8 py-4 rounded-lg text-white font-bold transition-all hover:opacity-90 active:scale-95 shadow-lg">
            @if($isCompleted)
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                Les Voltooi
            @else
                Merk as Voltooi
            @endif
        </button>
    </div>
</div>

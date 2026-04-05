@extends('layouts.student-course')

@section('content')
    @php
        // 1. Pre-load all completed lesson IDs for this user once to ensure 100% accuracy
        $completedIds = \App\Models\CourseProgress::where('user_id', auth()->id())
            ->pluck('course_material_id')
            ->map(fn($id) => (int) $id)
            ->toArray();
    @endphp

    <div class="max-w-5xl mx-auto py-16 px-8">
        {{-- Course Header --}}
        <div class="mb-12">
            <h1 class="text-5xl font-black text-gray-900 tracking-tight mb-4 leading-none uppercase">
                {{ $course->title }}
            </h1>
            <div class="flex items-center gap-6">
                <p class="text-gray-500 text-lg max-w-2xl">{{ $course->description }}</p>
                <div class="h-12 w-[2px] bg-gray-200"></div>
                <div>
                    <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest text-orange-600">Total Time</span>
                    <span class="text-lg font-bold text-gray-900">{{ $course->chapters->flatMap->modules->flatMap->materials->sum('duration') }} min</span>
                </div>
            </div>
        </div>

        <div class="space-y-8">
            @foreach($course->chapters as $chapter)
                {{-- CHAPTER LEVEL --}}
                <details class="group border border-gray-200 rounded-[2rem] overflow-hidden bg-white shadow-sm" {{ $loop->first ? 'open' : '' }}>
                    <summary class="flex items-center justify-between p-8 bg-gray-50 border-b border-gray-200 cursor-pointer list-none transition hover:bg-gray-100">
                        <div class="flex items-center gap-4">
                            <span class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-900 text-white text-xs font-black">
                                {{ $loop->iteration }}
                            </span>
                            <span class="font-black text-xl tracking-tight uppercase">{{ $chapter->title }}</span>
                        </div>
                        <span class="transition group-open:rotate-180 text-gray-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </span>
                    </summary>
                    
                    <div class="p-8 space-y-10">
                        @foreach($chapter->modules as $module)
                            {{-- MODULE LEVEL --}}
                            <div x-data="{ open: true }">
                                <button @click="open = !open" class="flex items-center justify-between w-full mb-6 group/mod">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-4 h-4 text-orange-600 transition-transform" :class="open ? 'rotate-90' : ''" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path>
                                        </svg>
                                        <span class="text-xs font-black text-gray-400 uppercase tracking-widest group-hover/mod:text-orange-600 transition">
                                            {{ $module->title }}
                                        </span>
                                    </div>
                                    <div class="h-[1px] flex-1 bg-gray-100 mx-4"></div>
                                </button>

                                {{-- LESSONS LIST --}}
                                <div x-show="open" x-collapse class="grid gap-3">
                                    @foreach($module->materials as $lesson)
                                        @php
                                            // Simple check: is this lesson ID in our completed list?
                                            $isDone = in_array((int) $lesson->id, $completedIds ?? []);
                                        @endphp

                                        <div class="flex items-center gap-4">
                                            <div class="shrink-0">
                                                @if($isDone)
                                                    {{-- GREEN CHECK --}}
                                                    <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center border border-green-600 shadow-sm">
                                                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    </div>
                                                @else
                                                    {{-- EMPTY CIRCLE --}}
                                                    <div class="w-6 h-6 border-2 border-gray-200 rounded-full"></div>
                                                @endif
                                            </div>
                                            <span class="font-bold {{ $isDone ? 'text-gray-400' : 'text-gray-800' }}">{{ $lesson->title }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </details>
            @endforeach
        </div>
    </div>
@endsection
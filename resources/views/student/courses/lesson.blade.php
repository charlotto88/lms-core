@extends('layouts.student-course')

@section('content')
    <div class="max-w-4xl mx-auto py-12 px-8">
        
        {{-- 1. Lesson Header --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">{{ $currentLesson->title }}</h1>
            <p class="text-sm text-gray-500 mt-2">{{ $currentLesson->duration }} min om te voltooi</p>
        </div>

        {{-- 2. Lesson Content (The Builder Blocks) --}}
        <div class="lesson-content prose prose-orange max-w-none">
            @foreach($currentLesson->content_blocks as $block)
                @if($block['type'] === 'text')
                    {!! $block['data']['text_content'] !!}
                @elseif($block['type'] === 'video')
                    <div class="aspect-video my-8">
                        <iframe class="w-full h-full rounded-2xl shadow-lg" src="{{ $block['data']['url'] }}" frameborder="0" allowfullscreen></iframe>
                    </div>
                @elseif($block['type'] === 'image')
                    <img src="{{ asset('storage/' . $block['data']['image_path']) }}" alt="{{ $block['data']['alt_text'] ?? '' }}" class="rounded-2xl my-8">
                @endif
            @endforeach

            {{-- 3. THE BUTTON GOES HERE (Right after the content ends) --}}
            <div class="mt-12 pt-10 border-t border-gray-100">
                @livewire('complete-lesson-button', ['lessonId' => $currentLesson->id], key($currentLesson->id))
            </div>
        </div>

        {{-- 4. Simple Navigation --}}
        <div class="flex justify-between mt-12 text-sm font-bold text-gray-400 uppercase tracking-widest">
            {{-- Previous/Next Lesson links could go here later --}}
        </div>

    </div>
@endsection
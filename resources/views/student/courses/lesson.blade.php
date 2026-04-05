@extends('layouts.student-course')

@section('content')
    <div class="max-w-4xl mx-auto py-12 px-8 lg:px-16">
        <h1 class="text-4xl font-black text-gray-900 tracking-tight leading-none mb-12">
            {{ $currentLesson->title }}
        </h1>

        <div class="space-y-12 pb-32">
            @if($currentLesson->content_blocks)
                @foreach($currentLesson->content_blocks as $block)
                    <div class="block-wrapper">
                        @if($block['type'] === 'text')
                            <div class="prose prose-orange prose-lg max-w-none">
                                {!! $block['data']['text_content'] !!}
                            </div>
                        @endif

                        @if($block['type'] === 'video')
                            @php
                                $url = $block['data']['url'];
                                if (str_contains($url, 'watch?v=')) $url = str_replace('watch?v=', 'embed/', $url);
                                elseif (str_contains($url, 'youtu.be/')) $url = str_replace('youtu.be/', 'youtube.com/embed/', $url);
                            @endphp
                            <div class="aspect-video rounded-3xl overflow-hidden shadow-2xl bg-black">
                                <iframe src="{{ $url }}" class="w-full h-full" allowfullscreen></iframe>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>

        <div class="fixed bottom-10 right-10 z-50">
            <button class="bg-orange-600 text-white px-8 py-3 rounded-2xl font-bold shadow-xl hover:scale-105 transition transform">
                Next Lesson &rarr;
            </button>
        </div>
    </div>
@endsection
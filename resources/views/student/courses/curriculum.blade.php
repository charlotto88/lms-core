@extends('layouts.student-course')

@section('content')
    <div class="max-w-5xl mx-auto py-16 px-8">
        <div class="mb-12">
            <h1 class="text-5xl font-black text-gray-900 tracking-tight mb-4 leading-none">
                {{ $course->title }}
            </h1>
            <p class="text-gray-500 text-lg max-w-2xl">{{ $course->description }}</p>
        </div>

        <div class="space-y-6">
            @foreach($course->chapters as $chapter)
                <div class="border border-gray-200 rounded-3xl overflow-hidden bg-white shadow-sm">
                    <div class="p-6 bg-gray-50 border-b border-gray-200 font-black text-xl tracking-tight">
                        {{ $chapter->title }}
                    </div>
                    <div class="p-6 space-y-6">
                        @foreach($chapter->modules as $module)
                            <div>
                                <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">
                                    {{ $module->title }}
                                </h4>
                                <div class="grid gap-2">
                                    @foreach($module->materials as $lesson)
                                        <a href="{{ route('student.lesson.show', [$course->slug, $lesson->id]) }}" class="flex items-center justify-between p-4 rounded-2xl border border-gray-100 hover:border-orange-600 hover:shadow-md transition group">
                                            <div class="flex items-center space-x-4">
                                                <div class="p-2 bg-orange-50 rounded-lg text-orange-600">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path></svg>
                                                </div>
                                                <span class="font-bold text-gray-700 group-hover:text-orange-600">{{ $lesson->title }}</span>
                                            </div>
                                            <span class="text-[10px] font-black text-gray-400">START &rarr;</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
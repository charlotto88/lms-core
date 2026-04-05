<!DOCTYPE html>
<html lang="af" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $course->title }} - Otto Academy</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script defer src="https://unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full antialiased font-sans text-gray-900">

    @php
        // 1. Fetch IDs and force them to be integers immediately
        $completedLessonIds = \App\Models\CourseProgress::where('user_id', auth()->id())
            ->pluck('course_material_id')
            ->map(fn($id) => (int) $id) // Force all to integers
            ->toArray();
    @endphp

    <div class="flex h-screen overflow-hidden bg-white">
        
        {{-- SIDEBAR --}}
        <aside class="w-80 border-r border-gray-200 flex flex-col bg-gray-50 shadow-inner">
            {{-- Sidebar Header --}}
                <div class="p-6 border-b border-gray-200 bg-white">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 bg-orange-600 rounded-lg shadow-lg">
                            <x-heroicon-o-academic-cap class="w-6 h-6 text-white" />
                        </div>
                        <span class="font-black tracking-tighter text-xl italic text-gray-900">OTTO<span class="text-orange-600">LMS</span></span>
                    </div>
                    
                    {{-- RESTORED DASHBOARD LINK --}}
                    <a href="{{ route('filament.student.pages.my-courses') }}" 
                    class="flex items-center gap-2 mb-6 px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-black text-gray-600 transition-all uppercase tracking-widest">
                        <x-heroicon-m-squares-2x2 class="w-4 h-4 text-orange-600" />
                        My Dashboard
                    </a>

                    <h2 class="text-sm font-black text-gray-900 leading-tight uppercase tracking-tight">
                        {{ $course->title }}
                    </h2>
                </div>

            {{-- NAVIGATION --}}
            <nav class="flex-1 overflow-y-auto p-4 space-y-4 custom-scrollbar">
                @foreach($course->chapters as $chapter)
                    @php
                        // Auto-open chapter if it contains the current lesson
                        $chapterIsOpen = (isset($currentLesson) && $chapter->modules->flatMap->materials->contains($currentLesson)) || $loop->first;
                    @endphp

                    <div x-data="{ chapterOpen: {{ $chapterIsOpen ? 'true' : 'false' }} }" class="mb-4">
                        <button @click="chapterOpen = !chapterOpen" 
                                class="flex items-center justify-between w-full px-3 py-2 text-[11px] font-black text-gray-400 uppercase tracking-widest hover:text-gray-900 transition-colors group">
                            <span :class="chapterOpen ? 'text-gray-900' : ''">{{ $chapter->title }}</span>
                            <svg class="w-3 h-3 transition-transform" :class="chapterOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="chapterOpen" x-collapse class="mt-2 space-y-4 ml-2 border-l-2 border-gray-200/50 pl-2">
                            @foreach($chapter->modules as $module)
                                @php
                                    $moduleIsOpen = isset($currentLesson) && $module->materials->contains($currentLesson);
                                @endphp

                                <div x-data="{ moduleOpen: {{ $moduleIsOpen ? 'true' : 'false' }} }">
                                    <button @click="moduleOpen = !moduleOpen" 
                                            class="flex items-center justify-between w-full px-2 py-1.5 text-[11px] font-bold text-gray-500 bg-gray-100 rounded-lg hover:bg-orange-50 hover:text-orange-600 transition-all mb-2">
                                        <span>{{ $module->title }}</span>
                                        <svg class="w-3 h-3 transition-transform" :class="moduleOpen ? 'rotate-90' : ''" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path>
                                        </svg>
                                    </button>

                                    <div x-show="moduleOpen" x-collapse class="space-y-1">
                                        @foreach($module->materials as $lesson)
                                            @php
                                                $done = in_array((int) $lesson->id, $completedIds ?? []);
                                                $active = isset($currentLesson) && (int)$currentLesson->id == (int)$lesson->id;
                                            @endphp

                                            <a href="..." class="{{ $active ? 'bg-orange-600 text-white' : '' }}">
                                                <div class="shrink-0">
                                                    @if($done)
                                                        <div class="w-4 h-4 bg-green-500 rounded-full flex items-center justify-center border border-green-600">
                                                            <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                        </div>
                                                    @else
                                                        <div class="w-4 h-4 border-2 {{ $active ? 'border-white/50' : 'border-gray-300' }} rounded-full"></div>
                                                    @endif
                                                </div>
                                                <span>{{ $lesson->title }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </nav>
        </aside>

        {{-- MAIN CONTENT AREA --}}
        <main class="flex-1 flex flex-col overflow-hidden bg-white">
            <header class="h-16 border-b border-gray-200 flex items-center justify-between px-8 bg-white/80 backdrop-blur-md sticky top-0 z-20">
                <div class="flex items-center gap-4">
                    <a href="{{ route('student.course.curriculum', $course->slug) }}" class="text-sm font-bold text-gray-400 hover:text-orange-600 transition flex items-center gap-2">
                        <x-heroicon-m-arrow-left class="w-4 h-4" />
                        Terug na Kurrikulum
                    </a>
                </div>
                <div class="flex items-center gap-4">
                    {{-- User Profile / Progress Stats could go here --}}
                </div>
            </header>

            <div class="flex-1 overflow-y-auto custom-scrollbar">
                @yield('content')
            </div>
        </main>
    </div>

    @livewireScripts
    <script>
        document.addEventListener('livewire:init', () => {
           Livewire.on('progressUpdated', () => {
               window.location.reload();
           });
        });
    </script>
</body>
</html>
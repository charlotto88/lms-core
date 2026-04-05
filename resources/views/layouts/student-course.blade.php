<!DOCTYPE html>
<html lang="en" class="h-full bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full overflow-hidden font-sans antialiased text-gray-900">
    <div class="flex h-screen overflow-hidden">
        
        <aside class="hidden lg:flex flex-col w-[25%] border-r border-gray-200 bg-gray-50 overflow-y-auto shrink-0">
            <div class="p-6 border-b bg-white sticky top-0 z-20">
                <h2 class="font-bold text-lg leading-tight">{{ $course->title }}</h2>
                <p class="text-[10px] font-black uppercase tracking-widest text-orange-600 mt-1">{{ $course->course_code }}</p>
            </div>
            <nav class="p-4 space-y-4">
                @foreach($course->chapters as $chapter)
                    <div class="mb-4">
                        <h3 class="px-2 text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">{{ $chapter->title }}</h3>
                        @foreach($chapter->modules as $module)
                            <div class="ml-2 mb-4">
                                <div class="px-2 py-1 text-[11px] font-bold text-gray-500 bg-gray-100 rounded">{{ $module->title }}</div>
                                <div class="mt-1 space-y-1">
                                    @foreach($module->materials as $lesson)
                                        <a href="{{ route('student.lesson.show', [$course->slug, $lesson->id]) }}" 
                                           class="block py-2 px-4 text-sm rounded-lg transition {{ isset($currentLesson) && $currentLesson->id == $lesson->id ? 'bg-orange-600 text-white font-bold' : 'text-gray-600 hover:bg-gray-100' }}">
                                            {{ $lesson->title }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </nav>
        </aside>

        <main class="flex-1 flex flex-col min-w-0 bg-white overflow-hidden">
            <header class="h-16 border-b border-gray-200 flex items-center px-8 shrink-0 bg-white">
                <div class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                    <a href="/student/my-courses">Dashboard</a> / <span class="text-gray-900">{{ $course->title }}</span>
                </div>
            </header>
            <div class="flex-1 overflow-y-auto">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
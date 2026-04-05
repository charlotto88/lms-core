<x-filament-panels::page>
    <div class="space-y-8">
        {{-- Custom Dashboard Header --}}
        <header class="flex flex-col gap-2">
            <h1 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white">
                My Enrolled Courses
            </h1>
            <p class="text-gray-500 dark:text-gray-400">
                Welcome back, {{ auth()->user()->name }}. Continue your learning journey.
            </p>
        </header>

        {{-- Course Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($this->courses as $course)
                <div class="flex flex-col bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 group">
                    
                    {{-- Course Hero/Branding Area --}}
                    <div class="h-32 bg-gradient-to-br from-orange-500 to-orange-600 p-6 flex items-end relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4 opacity-10">
                            <svg class="w-20 h-20 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L1 7l11 5 11-5-11-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
                        </div>
                        <span class="text-white/30 font-black text-4xl uppercase tracking-tighter select-none">
                            {{ substr($course->course_code, 0, 3) }}
                        </span>
                    </div>

                    <div class="p-6 flex-1 flex flex-col">
                        <div class="flex-1 mb-6">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-2 py-0.5 bg-orange-100 text-orange-600 text-[10px] font-black uppercase rounded-md">
                                    {{ $course->course_code }}
                                </span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white group-hover:text-orange-600 transition-colors leading-tight">
                                {{ $course->title }}
                            </h3>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 line-clamp-2">
                                {{ $course->description ?? 'Start mastering this course today.' }}
                            </p>
                        </div>

                        {{-- Footer with Progress & Action --}}
                        <div class="pt-6 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between">
                            <div class="flex flex-col">
                                <span class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Progress</span>
                                <span class="text-sm font-bold text-gray-700 dark:text-gray-300">0% Complete</span>
                            </div>

                            {{-- THE CRITICAL LINK: Points to the Curriculum Map --}}
                            <a href="{{ route('student.course.curriculum', ['course' => $course->slug]) }}" 
                               class="inline-flex items-center justify-center px-6 py-2.5 bg-orange-600 hover:bg-orange-700 text-white text-sm font-bold rounded-2xl shadow-lg shadow-orange-100 dark:shadow-none transition-all active:scale-95 group-hover:-translate-y-1">
                                Start Learning
                                <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center bg-gray-50 dark:bg-gray-800/50 rounded-3xl border-2 border-dashed border-gray-200 dark:border-gray-700">
                    <div class="mx-auto w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4 text-gray-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">No Enrolled Courses</h3>
                    <p class="text-gray-500">You are not currently enrolled in any courses.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-filament-panels::page>
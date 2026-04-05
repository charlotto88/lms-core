<x-filament-panels::page>
    <div style="margin: -2rem -2rem 0 -2rem; background-color: #f7f9fa; min-height: 100vh;">
        
        {{-- DARK UDEMY HERO HEADER --}}
        <div style="background-color: #2d2f31; color: white; padding: 3.5rem 4rem; margin-bottom: 2.5rem;">
            <div style="max-width: 1340px; margin: 0 auto;">
                <h1 style="font-size: 2.2rem; font-weight: 800; letter-spacing: -0.02em; font-family: 'SuisseWorks', Georgia, Times, serif;">My learning</h1>
                
                {{-- Navigation Tabs --}}
                <div style="display: flex; gap: 1.5rem; margin-top: 2.5rem; font-size: 0.95rem; font-weight: 700;">
                    <div style="padding-bottom: 0.5rem; border-bottom: 5px solid white; cursor: pointer; color: white;">All courses</div>
                    <div style="padding-bottom: 0.5rem; color: #b1b3b5; cursor: pointer; border-bottom: 5px solid transparent;">My Lists</div>
                    <div style="padding-bottom: 0.5rem; color: #b1b3b5; cursor: pointer; border-bottom: 5px solid transparent;">Wishlist</div>
                    <div style="padding-bottom: 0.5rem; color: #b1b3b5; cursor: pointer; border-bottom: 5px solid transparent;">Archived</div>
                </div>
            </div>
        </div>

        {{-- COURSE GRID --}}
        <div style="max-width: 1340px; margin: 0 auto; padding: 0 4rem; display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 2.5rem 1.5rem;">
            @forelse($this->courses as $course)
                <div style="display: flex; flex-direction: column; background: white; border: 1px solid #d1d7dc; transition: box-shadow 0.2s;" class="group hover-shadow">
                    
                    {{-- Thumbnail Area --}}
                    <div style="aspect-ratio: 16/9; width: 100%; background-color: #f0f2f5; overflow: hidden; position: relative;">
                        @if($course->banner_image)
                            <img src="{{ asset('storage/' . $course->banner_image) }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div style="width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; background: #e3e7ed;">
                                <span style="font-weight: 900; font-size: 1.5rem; color: #adb5bd;">{{ substr($course->course_code, 0, 3) }}</span>
                                <span style="font-size: 0.6rem; font-weight: 800; color: #adb5bd; margin-top: 4px;">{{ $course->course_code }}</span>
                            </div>
                        @endif
                        
                        {{-- Link overlay for the image only --}}
                        <a href="{{ route('student.course.curriculum', $course->slug) }}" style="position: absolute; inset: 0; background: rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.2s;" class="group-hover:opacity-100">
                            <div style="background: white; border-radius: 50%; padding: 0.8rem;">
                                <svg style="width: 1.5rem; height: 1.5rem; color: black;" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/></svg>
                            </div>
                        </a>
                    </div>

                    {{-- Card Content --}}
                    {{-- Inside your @forelse loop --}}
                    <div style="padding: 1rem; flex: 1; display: flex; flex-direction: column;">
                        <h3 style="font-weight: 700; font-size: 1rem; color: #2d2f31; margin-bottom: 4px;">
                            {{ $course->title }}
                        </h3>
                        
                        {{-- Total Time Display --}}
                        <div style="display: flex; align-items: center; gap: 4px; margin-bottom: 12px;">
                            <svg style="width: 12px; height: 12px; color: #6a6f73;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span style="font-size: 0.75rem; color: #6a6f73;">
                                {{ $course->chapters->flatMap->modules->flatMap->materials->sum('duration') }} min total
                            </span>
                        </div>
                        
                        {{-- Progress Bar --}}
                        <div style="margin-bottom: 1rem;">
                            <div style="width: 100%; height: 2px; background: #d1d7dc;">
                                {{-- DYNAMIC WIDTH --}}
                                <div style="width: {{ $course->progress_percent }}%; height: 100%; background: #5624d0; transition: width 0.5s ease-in-out;"></div>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-top: 6px;">
                                <span style="font-size: 0.75rem; font-weight: 700; color: #2d2f31;">
                                    {{ $course->progress_percent }}% complete
                                </span>
                            </div>
                        </div>

                        <a href="{{ route('student.course.curriculum', $course->slug) }}" 
                        style="display: block; width: 100%; text-align: center; background-color: #a435f0; color: white; padding: 10px 0; font-weight: 700; font-size: 0.9rem; text-decoration: none; border-radius: 4px;">
                            Begin leer
                        </a>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; padding: 4rem; text-align: center; background: white; border: 1px solid #d1d7dc;">
                    <p style="color: #6a6f73;">Geen kursusse gevind nie.</p>
                </div>
            @endforelse
        </div>

        <style>
            .hover-shadow:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
            .group:hover .group-hover\:opacity-100 { opacity: 1 !important; }
        </style>
    </div>

    <style>
        /* This handles the hover states that Inline styles can't do */
        .udemy-card:hover { opacity: 0.9; }
        .group:hover .group-hover\:opacity-100 { opacity: 1 !important; }
        .group:hover .group-hover\:text-\[\#5624d0\] { color: #5624d0 !important; }
    </style>
</x-filament-panels::page>
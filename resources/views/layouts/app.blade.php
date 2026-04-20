<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'ESUT Law Clinic — Free, confidential legal guidance for the ESUT community, provided by qualified law students under faculty supervision.')">
    <title>@yield('title', 'ESUT Law Clinic') — Free Legal Guidance</title>

    <link rel="shortcut icon" href="{{ url('assets/img/elc-logo.jpg') }}" type="image/jpeg">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        crimson: { DEFAULT: '#711500', dark: '#4a0d00', mid: '#8a1a00', light: '#fdf0ed' },
                        gold:    { DEFAULT: '#C9A84C', light: '#e4c97e' },
                    },
                    fontFamily: {
                        serif: ['"DM Serif Display"', 'Georgia', 'serif'],
                        sans:  ['"DM Sans"', 'system-ui', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --crimson:       #711500;
            --crimson-dark:  #4a0d00;
            --crimson-mid:   #8a1a00;
            --crimson-light: #fdf0ed;
            --gold:          #C9A84C;
            --gold-light:    #e4c97e;
            --off-white:     #f8f5f2;
            --dark:          #1a0800;
            --text:          #2a1200;
            --text-mid:      #6b4a3a;
            --text-light:    #a08070;
            --border:        #e8ddd8;
        }
        [x-cloak] { display: none !important; }
        body { font-family: 'DM Sans', sans-serif; background: var(--off-white); color: var(--text); }

        /* NAV */
        .site-nav {
            position: sticky; top: 0; z-index: 999;
            background: rgba(255,255,255,0.96);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            transition: box-shadow 0.3s;
        }
        .site-nav.scrolled { box-shadow: 0 4px 24px rgba(113,21,0,0.08); }
        .nav-logo-mark {
            width: 38px; height: 38px; 
            /* background: var(--crimson); */
            border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .nav-logo-mark span { font-family: 'DM Serif Display', serif; font-size: 13px; color: var(--gold); }
        .nav-logo-mark img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
        .nav-link {
            font-size: 13.5px; font-weight: 500; color: var(--text-mid);
            padding: 8px 12px; border-radius: 6px;
            transition: color 0.2s, background 0.2s; white-space: nowrap; text-decoration: none;
        }
        .nav-link:hover, .nav-link.active { color: var(--crimson); background: var(--crimson-light); }
        .nav-btn-ghost {
            font-size: 13px; font-weight: 600; color: var(--crimson); text-decoration: none;
            padding: 8px 16px; border-radius: 6px;
            border: 1.5px solid rgba(113,21,0,0.2);
            transition: border-color 0.2s, background 0.2s;
        }
        .nav-btn-ghost:hover { border-color: var(--crimson); background: var(--crimson-light); text-decoration: none; }
        .nav-btn-primary {
            font-size: 13px; font-weight: 700; color: #fff; text-decoration: none;
            background: var(--crimson); padding: 9px 20px; border-radius: 7px;
            transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 4px 16px rgba(113,21,0,0.25);
        }
        .nav-btn-primary:hover {
            background: var(--gold); color: var(--dark); transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(201,168,76,0.35); text-decoration: none;
        }

        /* SHARED */
        .section-eyebrow {
            display: inline-block; font-size: 10.5px; font-weight: 700;
            letter-spacing: 3px; text-transform: uppercase;
            padding: 5px 16px; border-radius: 30px; margin-bottom: 14px;
        }
        .section-eyebrow-light { color: var(--gold); border: 1px solid rgba(201,168,76,0.35); background: rgba(201,168,76,0.08); }
        .section-eyebrow-dark  { color: var(--gold-light); border: 1px solid rgba(201,168,76,0.3); background: rgba(201,168,76,0.07); }

        .section-title { font-family: 'DM Serif Display', serif; font-size: clamp(28px, 3.5vw, 44px); font-weight: 400; line-height: 1.12; letter-spacing: -0.5px; margin-bottom: 14px; }
        .section-title-light { color: var(--text); }
        .section-title-light span { color: var(--crimson); }
        .section-title-dark { color: #fff; }
        .section-title-dark em { font-style: italic; color: var(--gold-light); }

        .title-rule { display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 16px; }
        .title-rule span { display: block; width: 44px; height: 1px; }
        .title-rule-light span { background: var(--crimson); }
        .title-rule-dark  span { background: rgba(201,168,76,0.5); }
        .title-rule i { display: block; width: 6px; height: 6px; transform: rotate(45deg); border-radius: 1px; }
        .title-rule-light i { background: var(--gold); }
        .title-rule-dark  i { background: var(--gold); }

        /* PROSE */
        .prose-legal h2 { font-family: 'DM Serif Display', serif; font-size: 1.3rem; color: var(--crimson); margin: 1.5rem 0 0.75rem; font-weight: 400; }
        .prose-legal h3 { font-size: 1.05rem; font-weight: 600; color: var(--text); margin: 1.2rem 0 0.5rem; }
        .prose-legal p  { margin-bottom: 0.85rem; color: #4a3a30; line-height: 1.75; }
        .prose-legal ul, .prose-legal ol { margin: 0 0 0.85rem 1.25rem; color: #4a3a30; }
        .prose-legal ul { list-style: disc; }
        .prose-legal ol { list-style: decimal; }
        .prose-legal li { margin-bottom: 0.3rem; line-height: 1.7; }
        .prose-legal strong { font-weight: 600; color: var(--text); }
        .prose-legal a { color: var(--crimson); text-decoration: underline; }

        /* FOOTER */
        .site-footer { background: var(--crimson-dark); color: #fff; }
    </style>
    @stack('styles')
</head>
<body>

<!-- ═══ NAV ═══════════════════════════════════════════════════════════════ -->
<nav class="site-nav" x-data="{ open: false }"
     x-on:scroll.window="$el.classList.toggle('scrolled', window.scrollY > 20)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 gap-6">
            <a href="{{ route('home') }}" class="flex items-center gap-3 flex-shrink-0">
                <div class="nav-logo-mark">
                    <img src="{{url('assets/img/elc-logo.jpg')}}" alt="ESUT Law Clinic">
                </div>
                <div class="leading-tight">
                    <span class="block font-serif text-base" style="color:var(--crimson)">ESUT Law Clinic</span>
                    <span class="block text-xs" style="color:var(--text-light)">Faculty of Law</span>
                </div>
            </a>
            <div class="hidden md:flex items-center gap-1 flex-1 justify-center">
                @php $navLinks = [['route'=>'home','label'=>'Home'],['route'=>'about','label'=>'About'],['route'=>'faq.index','label'=>'Legal Resources'],['route'=>'events.index','label'=>'Events'],['route'=>'contact.index','label'=>'Contact']]; @endphp
                @foreach($navLinks as $link)
                    <a href="{{ route($link['route']) }}" class="nav-link {{ request()->routeIs($link['route']) ? 'active' : '' }}">{{ $link['label'] }}</a>
                @endforeach
            </div>
            <div class="hidden md:flex items-center gap-2 flex-shrink-0">
                <a href="{{ route('enquiry.track') }}" class="nav-btn-ghost flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Track Case
                </a>
                <a href="{{ route('enquiry.create') }}" class="nav-btn-primary">Get Free Help</a>
            </div>
            <button @click="open = !open" class="md:hidden p-2 rounded-lg" style="color:var(--text-mid)">
                <svg x-show="!open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="open" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>
    <div x-show="open" x-cloak x-transition class="md:hidden border-t bg-white" style="border-color:var(--border)">
        <div class="px-4 py-3 space-y-1">
            @foreach($navLinks as $link)
                <a href="{{ route($link['route']) }}" @click="open = false" class="block nav-link {{ request()->routeIs($link['route']) ? 'active' : '' }}">{{ $link['label'] }}</a>
            @endforeach
            <div class="pt-3 border-t space-y-2" style="border-color:var(--border)">
                <a href="{{ route('enquiry.track') }}" class="block nav-link">Track My Case</a>
                <a href="{{ route('enquiry.create') }}" class="block text-center nav-btn-primary py-2.5">Get Free Legal Help</a>
            </div>
        </div>
    </div>
</nav>

@if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-transition class="bg-green-50 border-b border-green-200 px-4 py-3">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
            <div class="flex items-center gap-2 text-green-800 text-sm font-medium">
                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
            <button @click="show = false"><svg class="w-4 h-4 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
    </div>
@endif

<main>@yield('content')</main>

<!-- ═══ FOOTER ════════════════════════════════════════════════════════════ -->
<footer class="site-footer">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center" style="background:rgba(201,168,76,0.15);border:1px solid rgba(201,168,76,0.25)">
                        <img style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;" src="{{url('assets/img/elc-logo.jpg')}}" alt="ESUT Law Clinic">
                    </div>
                    <div>
                        <span class="block font-serif text-white">ESUT Law Clinic</span>
                        <span class="block text-xs" style="color:rgba(255,255,255,0.4)">Faculty of Law, ESUT</span>
                    </div>
                </div>
                <p class="text-sm leading-relaxed" style="color:rgba(255,255,255,0.4)">Free, confidential legal guidance powered by law students under faculty supervision.</p>
            </div>
            <div>
                <h4 class="text-xs font-bold uppercase tracking-widest mb-4" style="color:var(--gold)">Quick Links</h4>
                <ul class="space-y-2 text-sm" style="color:rgba(255,255,255,0.45)">
                    @foreach([['about','About the Clinic'],['faq.index','Legal Resources'],['events.index','Events & Outreach'],['contact.index','Contact Us'],['portal.login','Access Portal']] as $l)
                    <li><a href="{{ route($l[0]) }}" class="hover:text-white transition-colors">{{ $l[1] }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h4 class="text-xs font-bold uppercase tracking-widest mb-4" style="color:var(--gold)">Get Legal Help</h4>
                <ul class="space-y-2 text-sm" style="color:rgba(255,255,255,0.45)">
                    <li><a href="{{ route('enquiry.create') }}" class="hover:text-white transition-colors">Submit an Enquiry</a></li>
                    <li><a href="{{ route('enquiry.track') }}" class="hover:text-white transition-colors">Track Your Case</a></li>
                    <li><a href="{{ route('faq.index') }}" class="hover:text-white transition-colors">Know Your Rights</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-xs font-bold uppercase tracking-widest mb-4" style="color:var(--gold)">Find Us</h4>
                <ul class="space-y-3 text-sm" style="color:rgba(255,255,255,0.45)">
                    <li class="flex gap-2 items-start">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" style="color:var(--gold)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Faculty of Law, ESUT, Agbani, Enugu State
                    </li>
                    <li class="flex gap-2 items-start">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" style="color:var(--gold)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Mon – Fri · 9:00 AM – 5:00 PM
                    </li>
                    <li class="flex gap-2 items-start">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" style="color:var(--gold)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <a href="mailto:elc@esut.edu.ng" class="hover:text-white transition-colors">elc@esut.edu.ng</a>
                    </li>
                </ul>

                {{-- Social media --}}
                <div class="mt-5">
                    <p class="text-xs font-bold uppercase tracking-widest mb-3" style="color:rgba(255,255,255,0.25)">Follow Us</p>
                    <div class="flex items-center gap-2">
                        {{-- Instagram --}}
                        <a href="https://www.instagram.com/esut.lawclinic" target="_blank" rel="noopener"
                           class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors"
                           style="background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.5)"
                           onmouseover="this.style.background='rgba(201,168,76,0.2)';this.style.color='#C9A84C'"
                           onmouseout="this.style.background='rgba(255,255,255,0.07)';this.style.color='rgba(255,255,255,0.5)'"
                           title="Instagram">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                        </a>
                        {{-- X / Twitter --}}
                        <a href="https://x.com/Lawclinicesut" target="_blank" rel="noopener"
                           class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors"
                           style="background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.5)"
                           onmouseover="this.style.background='rgba(201,168,76,0.2)';this.style.color='#C9A84C'"
                           onmouseout="this.style.background='rgba(255,255,255,0.07)';this.style.color='rgba(255,255,255,0.5)'"
                           title="X / Twitter">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        {{-- YouTube --}}
                        <a href="https://youtube.com/@lawclinicesut" target="_blank" rel="noopener"
                           class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors"
                           style="background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.5)"
                           onmouseover="this.style.background='rgba(201,168,76,0.2)';this.style.color='#C9A84C'"
                           onmouseout="this.style.background='rgba(255,255,255,0.07)';this.style.color='rgba(255,255,255,0.5)')"
                           title="YouTube">
                            
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="border-t mt-10 pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs" style="border-color:rgba(255,255,255,0.08);color:rgba(255,255,255,0.3)">
            <p>&copy; {{ now()->year }} ESUT Law Clinic. All rights reserved. A pro bono initiative · Faculty of Law, Enugu State University of Science and Technology.</p>
            <p><a href="http://teranium.co" target="_blank" rel="noopener noreferrer">Tech Partner: Teranium Co</a></p>
        </div>
    </div>
</footer>

@stack('scripts')
</body>
</html>

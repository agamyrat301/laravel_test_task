<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Agamyrat — Full-Stack Developer</title>
    <meta name="description" content="Full-stack developer specialising in PHP, Laravel, and modern web APIs." />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.14.1/dist/cdn.min.js"></script>
</head>

<body class="bg-slate-900 text-slate-100 antialiased">

{{-- ══════════════════════════════════════════ NAVBAR ══ --}}
<header
    x-data="{ open: false, scrolled: false }"
    x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 20)"
    :class="scrolled ? 'bg-slate-900/95 backdrop-blur shadow-lg shadow-black/20' : 'bg-transparent'"
    class="fixed inset-x-0 top-0 z-50 transition-all duration-300"
>
    <div class="mx-auto max-w-6xl px-6 flex items-center justify-between h-16">
        <a href="#home" class="text-lg font-bold tracking-tight text-white">
            Agamyrat<span class="text-indigo-400">.</span>
        </a>

        <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-slate-300">
            <a href="#about"    class="hover:text-white transition-colors">About</a>
            <a href="#skills"   class="hover:text-white transition-colors">Skills</a>
            <a href="#projects" class="hover:text-white transition-colors">Projects</a>
            <a href="#contact"  class="hover:text-white transition-colors">Contact</a>
            <a href="#contact"
               class="px-4 py-1.5 rounded-full bg-indigo-600 text-white hover:bg-indigo-500 transition-colors">
                Hire Me
            </a>
        </nav>

        <button @click="open = !open" class="md:hidden text-slate-300 hover:text-white p-1">
            <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <svg x-show="open" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="md:hidden bg-slate-800 border-t border-slate-700 px-6 py-4 flex flex-col gap-4 text-sm font-medium text-slate-300">
        <a href="#about"    @click="open=false" class="hover:text-white">About</a>
        <a href="#skills"   @click="open=false" class="hover:text-white">Skills</a>
        <a href="#projects" @click="open=false" class="hover:text-white">Projects</a>
        <a href="#contact"  @click="open=false" class="hover:text-white">Contact</a>
        <a href="#contact"  @click="open=false"
           class="inline-block w-fit px-4 py-1.5 rounded-full bg-indigo-600 text-white">Hire Me</a>
    </div>
</header>


{{-- ══════════════════════════════════════════ HERO ══ --}}
<section id="home" class="min-h-screen flex items-center relative overflow-hidden">
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#1e293b_1px,transparent_1px),linear-gradient(to_bottom,#1e293b_1px,transparent_1px)] bg-[size:4rem_4rem] opacity-30"></div>
    <div class="absolute top-1/3 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-indigo-600/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative mx-auto max-w-6xl px-6 py-32 text-center">
        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/30 text-indigo-400 text-sm font-medium mb-8">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            Available for hire
        </span>

        <h1 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight leading-tight mb-6">
            Hi, I'm&nbsp;
            <span class="bg-linear-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">
                Agamyrat
            </span>
        </h1>

        <p class="text-xl sm:text-2xl text-slate-400 font-medium mb-4">
            Full-Stack Developer · PHP / Laravel · REST API
        </p>

        <p class="max-w-2xl mx-auto text-slate-400 text-base sm:text-lg leading-relaxed mb-10">
            I build clean, scalable backend services and polished web experiences.
            5+ years turning complex requirements into elegant, maintainable code.
        </p>

        <div class="flex flex-wrap justify-center gap-4">
            <a href="#projects"
               class="px-6 py-3 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-500 transition-colors shadow-lg shadow-indigo-600/20">
                View My Work
            </a>
            <a href="#contact"
               class="px-6 py-3 rounded-xl bg-slate-800 text-slate-200 font-semibold border border-slate-700 hover:bg-slate-700 transition-colors">
                Contact Me
            </a>
        </div>

        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex flex-col items-center gap-1 text-slate-500 text-xs">
            <span>Scroll</span>
            <svg class="w-4 h-4 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════ ABOUT ══ --}}
<section id="about" class="py-24 bg-slate-800/50">
    <div class="mx-auto max-w-6xl px-6">
        <div class="grid md:grid-cols-2 gap-16 items-center">
            <div class="flex justify-center">
                <div class="relative">
                    <div class="w-64 h-64 rounded-2xl bg-linear-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-7xl font-bold text-white shadow-2xl shadow-indigo-500/30">
                        AG
                    </div>
                    <div class="absolute -bottom-4 -right-4 w-24 h-24 rounded-xl bg-slate-900 border border-slate-700 flex items-center justify-center">
                        <span class="text-2xl font-bold text-indigo-400">5+</span>
                        <span class="text-xs text-slate-400 ml-1 leading-tight">yrs<br>exp.</span>
                    </div>
                </div>
            </div>

            <div>
                <p class="text-indigo-400 text-sm font-semibold uppercase tracking-widest mb-3">About Me</p>
                <h2 class="text-3xl sm:text-4xl font-bold text-white mb-5">
                    Turning ideas into<br/>working software
                </h2>
                <p class="text-slate-400 leading-relaxed mb-5">
                    I'm a full-stack developer with a strong focus on backend architecture.
                    I enjoy building RESTful APIs, designing clean service layers, and
                    integrating AI capabilities into real-world applications.
                </p>
                <p class="text-slate-400 leading-relaxed mb-8">
                    My workflow is driven by clean code, proper error handling, and thoughtful
                    architecture decisions — not just getting things to work, but getting them
                    right the first time.
                </p>

                <div class="grid grid-cols-3 gap-4">
                    @foreach([['50+', 'Projects'], ['5+', 'Years'], ['15+', 'Technologies']] as [$num, $label])
                    <div class="bg-slate-800 rounded-xl p-4 text-center border border-slate-700">
                        <p class="text-2xl font-bold text-indigo-400">{{ $num }}</p>
                        <p class="text-sm text-slate-400 mt-1">{{ $label }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════ SKILLS ══ --}}
<section id="skills" class="py-24">
    <div class="mx-auto max-w-6xl px-6">
        <div class="text-center mb-14">
            <p class="text-indigo-400 text-sm font-semibold uppercase tracking-widest mb-3">What I work with</p>
            <h2 class="text-3xl sm:text-4xl font-bold text-white">Skills & Technologies</h2>
        </div>

        @php
        $groups = [
            'Backend' => [
                ['PHP 8.1+',  'bg-blue-500/15 text-blue-300 border-blue-500/30'],
                ['Laravel',   'bg-red-500/15 text-red-300 border-red-500/30'],
                ['Python',    'bg-yellow-500/15 text-yellow-300 border-yellow-500/30'],
                ['REST API',  'bg-purple-500/15 text-purple-300 border-purple-500/30'],
                ['GraphQL',   'bg-pink-500/15 text-pink-300 border-pink-500/30'],
            ],
            'Frontend' => [
                ['JavaScript',  'bg-yellow-500/15 text-yellow-300 border-yellow-500/30'],
                ['TypeScript',  'bg-blue-500/15 text-blue-300 border-blue-500/30'],
                ['Vue.js',      'bg-emerald-500/15 text-emerald-300 border-emerald-500/30'],
                ['Tailwind CSS','bg-cyan-500/15 text-cyan-300 border-cyan-500/30'],
                ['Alpine.js',   'bg-indigo-500/15 text-indigo-300 border-indigo-500/30'],
            ],
            'Infrastructure' => [
                ['Docker',      'bg-blue-500/15 text-blue-300 border-blue-500/30'],
                ['PostgreSQL',  'bg-sky-500/15 text-sky-300 border-sky-500/30'],
                ['Redis',       'bg-red-500/15 text-red-300 border-red-500/30'],
                ['Linux',       'bg-orange-500/15 text-orange-300 border-orange-500/30'],
                ['Git',         'bg-orange-500/15 text-orange-300 border-orange-500/30'],
            ],
            'AI & Tools' => [
                ['Anthropic API',    'bg-violet-500/15 text-violet-300 border-violet-500/30'],
                ['OpenAI API',       'bg-emerald-500/15 text-emerald-300 border-emerald-500/30'],
                ['OpenAPI / Swagger','bg-lime-500/15 text-lime-300 border-lime-500/30'],
                ['Postman',          'bg-orange-500/15 text-orange-300 border-orange-500/30'],
                ['Composer',         'bg-slate-500/15 text-slate-300 border-slate-500/30'],
            ],
        ];
        @endphp

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($groups as $category => $skills)
            <div class="bg-slate-800/60 border border-slate-700/60 rounded-2xl p-6">
                <h3 class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-4">{{ $category }}</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($skills as [$name, $classes])
                    <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $classes }}">{{ $name }}</span>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════ PROJECTS ══ --}}
<section id="projects" class="py-24 bg-slate-800/50">
    <div class="mx-auto max-w-6xl px-6">
        <div class="text-center mb-14">
            <p class="text-indigo-400 text-sm font-semibold uppercase tracking-widest mb-3">What I've built</p>
            <h2 class="text-3xl sm:text-4xl font-bold text-white">Featured Projects</h2>
        </div>

        @php
        $projects = [
            [
                'title' => 'Developer Portfolio API',
                'desc'  => 'Production-ready Laravel 10 backend with AI-powered contact analysis (Anthropic Claude), rate limiting, structured JSONL logging, and OpenAPI 3.0 documentation.',
                'tech'  => ['Laravel', 'Anthropic AI', 'Swagger', 'Docker'],
                'color' => 'bg-indigo-500/15 text-indigo-300',
                'href'  => '#contact',
            ],
            [
                'title' => 'E-Commerce REST API',
                'desc'  => 'Scalable product catalog and order management API with RBAC, real-time inventory, and Stripe payment integration. 40k+ requests per day in production.',
                'tech'  => ['PHP', 'Laravel', 'PostgreSQL', 'Redis'],
                'color' => 'bg-emerald-500/15 text-emerald-300',
                'href'  => '#',
            ],
            [
                'title' => 'AI Document Processor',
                'desc'  => 'Python service that extracts, classifies, and summarises uploaded documents using the OpenAI API with async queue processing and S3 storage.',
                'tech'  => ['Python', 'FastAPI', 'OpenAI', 'Docker'],
                'color' => 'bg-purple-500/15 text-purple-300',
                'href'  => '#',
            ],
        ];
        @endphp

        <div class="grid md:grid-cols-3 gap-6">
            @foreach($projects as $p)
            <div class="group bg-slate-900 border border-slate-700 rounded-2xl p-6 flex flex-col hover:border-indigo-500/50 hover:-translate-y-1 transition-all duration-300">
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-white mb-3 group-hover:text-indigo-400 transition-colors">{{ $p['title'] }}</h3>
                    <p class="text-slate-400 text-sm leading-relaxed mb-5">{{ $p['desc'] }}</p>
                </div>
                <div>
                    <div class="flex flex-wrap gap-2 mb-4">
                        @foreach($p['tech'] as $tag)
                        <span class="px-2.5 py-0.5 rounded-md text-xs font-medium {{ $p['color'] }}">{{ $tag }}</span>
                        @endforeach
                    </div>
                    <a href="{{ $p['href'] }}"
                       class="inline-flex items-center gap-1 text-sm text-indigo-400 hover:text-indigo-300 font-medium transition-colors">
                        View project
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════ CONTACT ══ --}}
<section id="contact" class="py-24">
    <div class="mx-auto max-w-6xl px-6">
        <div class="text-center mb-14">
            <p class="text-indigo-400 text-sm font-semibold uppercase tracking-widest mb-3">Get in touch</p>
            <h2 class="text-3xl sm:text-4xl font-bold text-white">Let's Work Together</h2>
            <p class="mt-4 text-slate-400 max-w-xl mx-auto">
                Have a project in mind or just want to say hello? Fill in the form —
                our AI assistant will analyse your message and I'll get back to you promptly.
            </p>
        </div>

        <div class="grid lg:grid-cols-5 gap-12 items-start" x-data="contactForm()">

            {{-- Info column --}}
            <div class="lg:col-span-2 space-y-6">
                @foreach([
                    ['Email',    'agajan301@gmail.com', 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                    ['Phone',    '+40 791 392 970',       'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z'],
                    ['Location', 'Remote / Worldwide',      'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z'],
                ] as [$label, $value, $icon])
                <div class="flex gap-4 items-start">
                    <div class="shrink-0 w-10 h-10 rounded-xl bg-indigo-600/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $icon }}"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-400">{{ $label }}</p>
                        <p class="text-slate-200 mt-0.5">{{ $value }}</p>
                    </div>
                </div>
                @endforeach

                <div class="mt-8 p-4 rounded-xl bg-indigo-600/10 border border-indigo-500/20 text-sm text-indigo-300">
                    <p class="font-semibold mb-1">Powered by Anthropic AI</p>
                    <p class="text-indigo-400/80 leading-relaxed">
                        Your message is analysed for sentiment and intent. You'll receive
                        a personalised AI-generated reply instantly on submission.
                    </p>
                </div>
            </div>

            {{-- Form column --}}
            <div class="lg:col-span-3">

                {{-- Success state --}}
                <div x-show="submitted" x-cloak
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="bg-slate-800 border border-slate-700 rounded-2xl p-8">

                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-full bg-emerald-500/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-white">Message sent!</p>
                            <p class="text-sm text-slate-400">Check your inbox for a confirmation email.</p>
                        </div>
                    </div>

                    <div class="bg-slate-900/60 rounded-xl p-5 mb-6">
                        <p class="text-xs font-bold uppercase tracking-widest text-indigo-400 mb-3">AI-Generated Response</p>
                        <p class="text-slate-300 leading-relaxed" x-text="apiResponse?.auto_response"></p>
                    </div>

                    <div class="flex flex-wrap gap-4 mb-6">
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Sentiment</p>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold border"
                                  :class="{
                                    'bg-emerald-500/15 text-emerald-300 border-emerald-500/30': apiResponse?.sentiment === 'positive',
                                    'bg-slate-500/15 text-slate-300 border-slate-500/30':      apiResponse?.sentiment === 'neutral',
                                    'bg-red-500/15 text-red-300 border-red-500/30':            apiResponse?.sentiment === 'negative',
                                  }"
                                  x-text="apiResponse?.sentiment
                                    ? apiResponse.sentiment.charAt(0).toUpperCase() + apiResponse.sentiment.slice(1)
                                    : ''">
                            </span>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Request type</p>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/15 text-indigo-300 border border-indigo-500/30"
                                  x-text="apiResponse?.request_type
                                    ? apiResponse.request_type.replace(/_/g,' ').replace(/\b\w/g, c => c.toUpperCase())
                                    : ''">
                            </span>
                        </div>
                    </div>

                    <button @click="submitted = false"
                            class="text-sm text-indigo-400 hover:text-indigo-300 transition-colors font-medium">
                        ← Send another message
                    </button>
                </div>

                {{-- Form --}}
                <form x-show="!submitted"
                      @submit.prevent="submit"
                      class="bg-slate-800 border border-slate-700 rounded-2xl p-8 space-y-5">

                    <div x-show="errorMsg" x-cloak
                         class="p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400 text-sm"
                         x-text="errorMsg"></div>

                    <div class="grid sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">
                                Name <span class="text-red-400">*</span>
                            </label>
                            <input x-model="form.name" type="text" placeholder="Jane Smith"
                                   :class="errors.name ? 'border-red-500 focus:border-red-500' : 'border-slate-600 focus:border-indigo-500'"
                                   class="w-full bg-slate-900/60 rounded-xl px-4 py-3 text-slate-100 placeholder-slate-500 border outline-none transition-colors text-sm" />
                            <p x-show="errors.name" x-cloak class="mt-1.5 text-xs text-red-400" x-text="errors.name?.[0]"></p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">
                                Phone <span class="text-red-400">*</span>
                            </label>
                            <input x-model="form.phone" type="tel" placeholder="+1 555 000 0000"
                                   :class="errors.phone ? 'border-red-500 focus:border-red-500' : 'border-slate-600 focus:border-indigo-500'"
                                   class="w-full bg-slate-900/60 rounded-xl px-4 py-3 text-slate-100 placeholder-slate-500 border outline-none transition-colors text-sm" />
                            <p x-show="errors.phone" x-cloak class="mt-1.5 text-xs text-red-400" x-text="errors.phone?.[0]"></p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">
                            Email <span class="text-red-400">*</span>
                        </label>
                        <input x-model="form.email" type="email" placeholder="jane@example.com"
                               :class="errors.email ? 'border-red-500 focus:border-red-500' : 'border-slate-600 focus:border-indigo-500'"
                               class="w-full bg-slate-900/60 rounded-xl px-4 py-3 text-slate-100 placeholder-slate-500 border outline-none transition-colors text-sm" />
                        <p x-show="errors.email" x-cloak class="mt-1.5 text-xs text-red-400" x-text="errors.email?.[0]"></p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">
                            Message <span class="text-red-400">*</span>
                        </label>
                        <textarea x-model="form.comment" rows="5"
                                  placeholder="Tell me about your project, idea, or question…"
                                  :class="errors.comment ? 'border-red-500 focus:border-red-500' : 'border-slate-600 focus:border-indigo-500'"
                                  class="w-full bg-slate-900/60 rounded-xl px-4 py-3 text-slate-100 placeholder-slate-500 border outline-none transition-colors text-sm resize-none"></textarea>
                        <div class="flex justify-between mt-1.5">
                            <p x-show="errors.comment" x-cloak class="text-xs text-red-400" x-text="errors.comment?.[0]"></p>
                            <p class="text-xs text-slate-500 ml-auto" x-text="`${form.comment.length} / 2000`"></p>
                        </div>
                    </div>

                    <button type="submit" :disabled="loading"
                            class="w-full flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-500 disabled:opacity-60 disabled:cursor-not-allowed transition-all">
                        <svg x-show="loading" x-cloak class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                        </svg>
                        <span x-text="loading ? 'Sending…' : 'Send Message'"></span>
                    </button>

                    <p class="text-center text-xs text-slate-500">
                        Responses are analysed by AI · Your data is never shared
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>


{{-- ══════════════════════════════════════════ FOOTER ══ --}}
<footer class="border-t border-slate-800 py-8">
    <div class="mx-auto max-w-6xl px-6 flex flex-col sm:flex-row items-center justify-between gap-4">
        <p class="text-slate-500 text-sm">© {{ date('Y') }} Agamyrat. All rights reserved.</p>
        <div class="flex items-center gap-2 text-slate-500 text-xs">
            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
            API powered by Laravel 10 + Google Gemini
        </div>
    </div>
</footer>


<script>
function contactForm() {
    return {
        form:        { name: '', phone: '', email: '', comment: '' },
        errors:      {},
        loading:     false,
        submitted:   false,
        apiResponse: null,
        errorMsg:    '',

        async submit() {
            this.loading   = true;
            this.errors    = {};
            this.errorMsg  = '';
            this.submitted = false;

            try {
                const res  = await fetch('/api/contact', {
                    method:  'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body:    JSON.stringify(this.form),
                });
                const data = await res.json();

                if (res.ok) {
                    this.submitted   = true;
                    this.apiResponse = data.data;
                    this.form        = { name: '', phone: '', email: '', comment: '' };
                } else if (res.status === 422) {
                    this.errors = data.errors ?? {};
                } else {
                    this.errorMsg = data.message ?? 'Something went wrong. Please try again.';
                }
            } catch {
                this.errorMsg = 'Network error. Please check your connection and try again.';
            } finally {
                this.loading = false;
            }
        },
    };
}
</script>

</body>
</html>

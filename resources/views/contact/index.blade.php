@extends('layouts.app')
@section('title', 'Contact Us')

@section('content')
<div class="bg-crimson py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center">
        <h1 class="font-serif text-3xl sm:text-4xl font-bold text-white mb-3">Contact Us</h1>
        <p class="text-gray-300">For general enquiries that are not legal matters. For legal help, use our <a href="{{ route('enquiry.create') }}" class="text-gold hover:underline">free enquiry form</a>.</p>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        {{-- Contact info --}}
        <div class="space-y-6">
            <div>
                <h2 class="font-serif text-lg font-bold text-crimson mb-4">Find Us</h2>
                <div class="space-y-4 text-sm">
                    <div class="flex gap-3">
                        <div class="w-8 h-8 bg-crimson/5 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-crimson" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Address</p>
                            <p class="text-gray-500 mt-0.5">Faculty of Law, ESUT,<br>Agbani Road, Enugu State</p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <div class="w-8 h-8 bg-crimson/5 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-crimson" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Email</p>
                            <a href="mailto:elc@esut.edu.ng" class="text-crimson hover:text-gold transition-colors mt-0.5 block">elc@esut.edu.ng</a>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <div class="w-8 h-8 bg-crimson/5 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-crimson" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Clinic Hours</p>
                            <p class="text-gray-500 mt-0.5">Monday – Friday<br>9:00 AM – 5:00 PM</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gold/10 border border-gold/20 rounded-xl p-4">
                <p class="text-sm font-semibold text-crimson mb-1">Have a legal question?</p>
                <p class="text-xs text-gray-600 mb-3">Use our dedicated legal enquiry form for legal matters — it's free, confidential, and tracked.</p>
                <a href="{{ route('enquiry.create') }}" class="inline-block bg-crimson text-white text-xs font-semibold px-4 py-2 rounded-lg hover:bg-gold hover:text-crimson transition-colors">
                    Get Free Legal Help
                </a>
            </div>
        </div>

        {{-- Contact form --}}
        <div class="lg:col-span-2 bg-white border border-gray-100 rounded-2xl p-8 shadow-sm">
            <h2 class="font-serif text-lg font-bold text-crimson mb-6">Send a Message</h2>

            <form action="{{ route('contact.store') }}" method="POST" class="space-y-5">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-crimson/30 focus:border-crimson @error('name') border-red-400 @enderror">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Address *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-crimson/30 focus:border-crimson @error('email') border-red-400 @enderror">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Subject</label>
                    <input type="text" name="subject" value="{{ old('subject') }}"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-crimson/30 focus:border-crimson">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Message *</label>
                    <textarea name="message" rows="5" required
                              class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-crimson/30 focus:border-crimson resize-y @error('message') border-red-400 @enderror">{{ old('message') }}</textarea>
                    @error('message') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-xs text-gray-400">We respond within 2 business days.</p>
                    <button type="submit"
                            class="inline-flex items-center gap-2 bg-crimson text-white font-semibold px-6 py-3 rounded-xl hover:bg-gold hover:text-crimson transition-colors text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Send Message
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

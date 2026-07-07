@extends('layouts.app')
@section('title', 'Enquiry Submitted — ' . $enquiry->reference_code)

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center px-4 py-16">
    <div class="max-w-lg w-full bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Success header --}}
        <div class="bg-crimson p-8 text-center">
            <div class="w-16 h-16 bg-gold/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h1 class="font-serif text-2xl font-bold text-white mb-2">Enquiry Received!</h1>
            <p class="text-gray-300 text-sm">We've received your legal enquiry and will respond within 2–3 business days.</p>
        </div>

        {{-- Reference code spotlight --}}
        <div class="p-8">
            <div class="text-center mb-8">
                <p class="text-sm text-gray-500 mb-2">Your Reference Code</p>
                <div class="inline-flex items-center gap-3 bg-crimson/5 border-2 border-navy/20 rounded-xl px-6 py-4">
                    <span class="font-serif text-2xl font-bold text-navy tracking-widest">{{ $enquiry->reference_code }}</span>
                    <button onclick="navigator.clipboard.writeText('{{ $enquiry->reference_code }}').then(() => this.textContent = '✓')"
                            class="text-xs text-gray-400 hover:text-navy transition-colors border border-gray-200 rounded px-2 py-1">
                        Copy
                    </button>
                </div>
                <p class="text-xs text-gray-400 mt-3">
                    @if($enquiry->email)
                        A confirmation has been sent to <strong>{{ $enquiry->email }}</strong>
                    @else
                        Save this code — it is the only way to track your enquiry.
                    @endif
                </p>
            </div>

            {{-- Details summary --}}
            <div class="space-y-3 text-sm mb-8">
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-500">Category</span>
                    <span class="font-medium text-gray-900">{{ $enquiry->category_label }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-500">Urgency</span>
                    <span class="font-medium {{ $enquiry->urgency === 'urgent' ? 'text-red-600' : 'text-gray-900' }}">
                        {{ ucfirst($enquiry->urgency) }}
                        @if($enquiry->urgency === 'urgent') · Reviewed within 24 hrs @endif
                    </span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-500">Status</span>
                    <span class="inline-flex items-center gap-1 font-medium text-blue-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                        Received
                    </span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-gray-500">Submitted</span>
                    <span class="font-medium text-gray-900">{{ $enquiry->created_at->format('d M Y, g:i A') }}</span>
                </div>
            </div>

            {{-- CTA buttons --}}
            <div class="flex flex-col gap-3">
                <a href="{{ route('enquiry.track') }}"
                   class="w-full text-center bg-crimson text-white font-semibold py-3 rounded-xl hover:bg-gold hover:text-navy transition-colors">
                    Track My Enquiry
                </a>
                <a href="{{ route('faq.index') }}"
                   class="w-full text-center border border-gray-200 text-gray-700 font-medium py-3 rounded-xl hover:border-navy/30 transition-colors text-sm">
                    Browse Legal Resources While You Wait
                </a>
                <a href="{{ route('home') }}" class="text-center text-sm text-gray-400 hover:text-navy transition-colors">
                    Return to Home
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

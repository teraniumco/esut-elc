@extends('layouts.app')
@section('title', 'Case Status — ' . $enquiry->reference_code)

@section('content')
<div class="bg-crimson py-14">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 text-center">
        <p class="text-gray-400 text-sm mb-2">Reference Code</p>
        <h1 class="font-serif text-3xl font-bold text-white mb-1 tracking-widest">{{ $enquiry->reference_code }}</h1>
        <p class="text-gray-400 text-sm">Submitted {{ $enquiry->created_at->diffForHumans() }}</p>
    </div>
</div>

<div class="max-w-2xl mx-auto px-4 sm:px-6 py-12">

    {{-- Status badge --}}
    @php
        $colorMap = [
            'received'          => ['bg' => 'bg-blue-100',   'text' => 'text-blue-800',  'dot' => 'bg-blue-500'],
            'under_review'      => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800','dot' => 'bg-yellow-500'],
            'in_progress'       => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-800','dot' => 'bg-indigo-500'],
            'awaiting_approval' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800','dot' => 'bg-orange-500'],
            'responded'         => ['bg' => 'bg-green-100',  'text' => 'text-green-800', 'dot' => 'bg-green-500'],
            'closed'            => ['bg' => 'bg-gray-100',   'text' => 'text-gray-700',  'dot' => 'bg-gray-400'],
        ];
        $color = $colorMap[$enquiry->status] ?? $colorMap['received'];
    @endphp

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide font-medium mb-1">Current Status</p>
                <span class="inline-flex items-center gap-2 {{ $color['bg'] }} {{ $color['text'] }} px-3 py-1.5 rounded-full text-sm font-semibold">
                    <span class="w-2 h-2 rounded-full {{ $color['dot'] }}"></span>
                    {{ $enquiry->status_label }}
                </span>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-400 mb-1">Category</p>
                <p class="text-sm font-medium text-gray-700">{{ $enquiry->category_label }}</p>
            </div>
        </div>

        {{-- Progress timeline --}}
        <div class="p-6">
            @php
                $allStatuses = array_keys(\App\Models\Enquiry::STATUSES);
                $currentIndex = array_search($enquiry->status, $allStatuses);
            @endphp
            <div class="flex items-center gap-0">
                @foreach(\App\Models\Enquiry::STATUSES as $key => $label)
                @php $idx = array_search($key, $allStatuses); $done = $idx <= $currentIndex; @endphp
                <div class="flex-1 flex flex-col items-center gap-1.5 relative">
                    {{-- Connector line before --}}
                    @if($idx > 0)
                    <div class="absolute left-0 top-3.5 w-1/2 h-0.5 {{ $done ? 'bg-crimson' : 'bg-gray-200' }} -translate-y-1/2"></div>
                    @endif
                    {{-- Connector line after --}}
                    @if($idx < count($allStatuses) - 1)
                    <div class="absolute right-0 top-3.5 w-1/2 h-0.5 {{ $idx < $currentIndex ? 'bg-crimson' : 'bg-gray-200' }} -translate-y-1/2"></div>
                    @endif
                    <div class="w-7 h-7 rounded-full border-2 z-10 flex items-center justify-center
                        {{ $key === $enquiry->status ? 'border-navy bg-crimson' : ($done ? 'border-navy bg-white' : 'border-gray-200 bg-white') }}">
                        @if($key === $enquiry->status)
                            <span class="w-2 h-2 rounded-full bg-white"></span>
                        @elseif($done)
                            <svg class="w-3.5 h-3.5 text-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        @endif
                    </div>
                    <p class="text-[10px] text-center leading-tight {{ $key === $enquiry->status ? 'text-navy font-semibold' : 'text-gray-400' }}">
                        {{ $label }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Enquiry details --}}
        <div class="px-6 pb-6 space-y-3 text-sm">
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">Urgency</span>
                <span class="{{ $enquiry->urgency === 'urgent' ? 'text-red-600 font-medium' : 'text-gray-700' }}">
                    {{ ucfirst($enquiry->urgency) }}
                </span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">Submitted</span>
                <span class="text-gray-700">{{ $enquiry->created_at->format('d M Y, g:i A') }}</span>
            </div>
            @if($enquiry->responded_at)
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">Responded</span>
                <span class="text-green-700 font-medium">{{ $enquiry->responded_at->format('d M Y, g:i A') }}</span>
            </div>
            @endif
        </div>
    </div>

    {{-- Response (shown once responded) --}}
    @if($enquiry->response && in_array($enquiry->status, ['responded', 'closed']))
    <div class="bg-white rounded-2xl border border-green-200 shadow-sm overflow-hidden mb-6">
        <div class="bg-green-50 px-6 py-4 flex items-center gap-2 border-b border-green-100">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <h2 class="font-semibold text-green-800">Legal Guidance Response</h2>
        </div>
        <div class="p-6">
            <div class="prose-legal text-sm">
                {!! nl2br(e($enquiry->response)) !!}
            </div>
            <p class="text-xs text-gray-400 mt-6 pt-4 border-t border-gray-100">
                This advice was prepared by ESUT Law Clinic student advisors under faculty supervision. It constitutes general legal information and not formal legal representation.
            </p>
        </div>
    </div>
    @else
    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 text-sm text-blue-800 flex gap-3 mb-6">
        <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div>
            <p class="font-medium">Your enquiry is being processed</p>
            <p class="text-blue-700 mt-0.5">
                @switch($enquiry->status)
                    @case('received') Our team has received your enquiry and will assign it to an advisor shortly. @break
                    @case('under_review') A student advisor is currently reviewing your matter. @break
                    @case('in_progress') Your case is actively being worked on. A response is being prepared. @break
                    @case('awaiting_approval') A response has been drafted and is awaiting supervisor approval before being sent to you. @break
                    @default Response will be sent to your provided email once ready.
                @endswitch
            </p>
        </div>
    </div>
    @endif

    {{-- Actions --}}
    <div class="flex flex-col sm:flex-row gap-3">
        <a href="{{ route('enquiry.track') }}"
           class="flex-1 text-center border border-gray-200 text-gray-700 font-medium py-3 rounded-xl hover:border-navy/30 transition-colors text-sm">
            Track Another Enquiry
        </a>
        <a href="{{ route('faq.index') }}"
           class="flex-1 text-center bg-crimson text-white font-semibold py-3 rounded-xl hover:bg-gold hover:text-navy transition-colors text-sm">
            Browse Legal Resources
        </a>
    </div>
</div>
@endsection

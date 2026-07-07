@extends('layouts.app')
@section('title', 'Track Your Enquiry')
@section('meta_description', 'Enter your reference code to check the status of your legal enquiry with ESUT Law Clinic.')

@section('content')
<div class="bg-crimson py-14">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 text-center">
        <h1 class="font-serif text-3xl sm:text-4xl font-bold text-white mb-3">Track Your Enquiry</h1>
        <p class="text-gray-300">Enter the reference code you received after submitting your enquiry.</p>
    </div>
</div>

<div class="max-w-lg mx-auto px-4 sm:px-6 py-14">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <form action="{{ route('enquiry.lookup') }}" method="POST">
            @csrf
            <label for="reference_code" class="block text-sm font-medium text-gray-700 mb-2">
                Reference Code
            </label>
            <div class="flex gap-3">
                <input type="text" name="reference_code" id="reference_code"
                       value="{{ old('reference_code') }}"
                       placeholder="ELC-2025-00142"
                       autocomplete="off"
                       class="flex-1 border border-gray-300 rounded-xl px-4 py-3 text-sm font-mono uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-navy/30 focus:border-navy @error('reference_code') border-red-400 @enderror">
                <button type="submit"
                        class="bg-crimson text-white font-semibold px-5 py-3 rounded-xl hover:bg-gold hover:text-navy transition-colors text-sm">
                    Track
                </button>
            </div>
            @error('reference_code')
                <p class="text-red-500 text-xs mt-2 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ $message }}
                </p>
            @enderror
            <p class="text-xs text-gray-400 mt-3">Format: ELC-YYYY-NNNNN &nbsp;·&nbsp; Case-insensitive</p>
        </form>

        <div class="border-t border-gray-100 mt-8 pt-6 text-center">
            <p class="text-sm text-gray-500 mb-3">Don't have a reference code yet?</p>
            <a href="{{ route('enquiry.create') }}" class="text-navy font-semibold text-sm hover:text-gold transition-colors">
                Submit a new enquiry →
            </a>
        </div>
    </div>

    {{-- Help text --}}
    <div class="mt-8 text-center text-sm text-gray-400">
        <p>Reference codes are sent to the email address provided at submission.<br>
        If you submitted anonymously without saving your code, please
        <a href="{{ route('contact.index') }}" class="text-navy hover:text-gold">contact us</a>.</p>
    </div>
</div>
@endsection

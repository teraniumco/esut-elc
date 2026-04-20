@extends('layouts.app')
@section('title', 'Get Free Legal Help')
@section('meta_description', 'Submit a free, confidential legal enquiry to the ESUT Law Clinic. No login required.')

@section('content')

{{-- Page Header --}}
<div class="bg-crimson py-14">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 text-center">
        <h1 class="font-serif text-3xl sm:text-4xl font-bold text-white mb-3">Get Free Legal Help</h1>
        <p class="text-gray-300">Fill in the form below. We'll review your enquiry and respond within 2–3 business days.</p>
    </div>
</div>

<div class="max-w-3xl mx-auto px-4 sm:px-6 py-12">

    {{-- Info bar --}}
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-8 flex gap-3">
        <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="text-sm text-blue-800">
            <strong>Confidential:</strong> Your enquiry is handled with full confidentiality by student advisors under faculty supervision. After submitting, you'll receive a <strong>reference code</strong> to track your case — no account needed.
        </p>
    </div>

    <form action="{{ route('enquiry.store') }}" method="POST" enctype="multipart/form-data"
          x-data="enquiryForm()" class="space-y-6">
        @csrf

        {{-- Anonymous toggle --}}
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-5" x-data>
            <label class="flex items-start gap-3 cursor-pointer">
                <div class="relative mt-0.5">
                    <input type="checkbox" name="is_anonymous" value="1" x-model="isAnonymous"
                           id="is_anonymous" class="sr-only peer">
                    <div class="w-10 h-6 bg-gray-200 peer-checked:bg-crimson rounded-full transition-colors"></div>
                    <div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full shadow peer-checked:translate-x-4 transition-transform"></div>
                </div>
                <div>
                    <p class="font-medium text-crimson text-sm">Submit Anonymously</p>
                    <p class="text-xs text-gray-500 mt-0.5">Choose this for sensitive matters such as sexual harassment or discrimination. Only your email will be collected to deliver the response.</p>
                </div>
            </label>
        </div>

        {{-- Personal details (hidden when anonymous) --}}
        <div x-show="!isAnonymous" x-transition class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}"
                       placeholder="Your full name"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-crimson/30 focus:border-crimson @error('full_name') border-red-400 @enderror">
                @error('full_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">Phone Number</label>
                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                       placeholder="e.g. 08012345678"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-crimson/30 focus:border-crimson @error('phone') border-red-400 @enderror">
                @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Email (always shown) --}}
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                Email Address <span x-show="isAnonymous" class="text-red-500">*</span>
                <span x-show="!isAnonymous" class="text-gray-400 font-normal">(or provide a phone number above)</span>
            </label>
            <input type="email" name="email" id="email" value="{{ old('email') }}"
                   placeholder="your.email@example.com"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-crimson/30 focus:border-crimson @error('email') border-red-400 @enderror">
            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            <p class="text-xs text-gray-400 mt-1">Your reference code and response will be sent to this address.</p>
        </div>

        {{-- Matter category --}}
        <div>
            <label for="matter_category" class="block text-sm font-medium text-gray-700 mb-1.5">Area of Legal Matter <span class="text-red-500">*</span></label>
            <select name="matter_category" id="matter_category"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-crimson/30 focus:border-crimson @error('matter_category') border-red-400 @enderror">
                <option value="">— Select a category —</option>
                @foreach($categories as $key => $label)
                    <option value="{{ $key }}" {{ old('matter_category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('matter_category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Description --}}
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">
                Describe Your Legal Issue <span class="text-red-500">*</span>
            </label>
            <textarea name="description" id="description" rows="7"
                      placeholder="Please describe your situation in as much detail as possible — what happened, when it happened, who is involved, and what outcome you are seeking. The more detail you provide, the better we can help you."
                      class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-crimson/30 focus:border-crimson resize-y @error('description') border-red-400 @enderror">{{ old('description') }}</textarea>
            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            <p class="text-xs text-gray-400 mt-1">Minimum 30 characters. Maximum 3,000 characters.</p>
        </div>

        {{-- Urgency + Attachment row --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Urgency Level <span class="text-red-500">*</span></label>
                <div class="flex gap-3">
                    @foreach(['normal' => 'Normal', 'urgent' => 'Urgent'] as $value => $label)
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="urgency" value="{{ $value }}"
                               {{ old('urgency', 'normal') === $value ? 'checked' : '' }} class="sr-only peer">
                        <div class="border-2 border-gray-200 peer-checked:border-crimson peer-checked:bg-crimson/5 rounded-xl p-3 text-center transition-all">
                            <p class="text-sm font-semibold text-gray-700 peer-checked:text-crimson">{{ $label }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                {{ $value === 'normal' ? '2–3 business days' : 'Within 24 hours' }}
                            </p>
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('urgency') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="attachment" class="block text-sm font-medium text-gray-700 mb-1.5">Attach a Document <span class="text-gray-400 font-normal">(optional)</span></label>
                <div class="border-2 border-dashed border-gray-200 rounded-xl p-4 text-center hover:border-crimson/30 transition-colors cursor-pointer" onclick="document.getElementById('attachment').click()">
                    <svg class="w-6 h-6 text-gray-400 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                    <p class="text-xs text-gray-500" id="file-label">PDF, DOC, JPG, PNG · Max 5MB</p>
                    <input type="file" name="attachment" id="attachment" class="hidden" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                           onchange="document.getElementById('file-label').textContent = this.files[0]?.name || 'PDF, DOC, JPG, PNG · Max 5MB'">
                </div>
                @error('attachment') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Privacy notice --}}
        <div class="bg-gray-50 rounded-xl p-4 text-xs text-gray-500 leading-relaxed">
            By submitting this form, you confirm that the information provided is accurate to the best of your knowledge. This clinic provides general legal information and guidance, not formal legal representation. For matters requiring formal legal representation, we will advise you accordingly.
        </div>

        {{-- Submit --}}
        <div class="flex items-center justify-between pt-2">
            <a href="{{ route('enquiry.track') }}" class="text-sm text-gray-500 hover:text-crimson transition-colors">
                Already submitted? Track your case →
            </a>
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-crimson text-white font-bold px-8 py-3.5 rounded-xl hover:bg-gold hover:text-crimson transition-colors shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                Submit Enquiry
            </button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
function enquiryForm() {
    return {
        isAnonymous: {{ old('is_anonymous') ? 'true' : 'false' }},
    }
}
</script>
@endpush

@extends('portal.layout')
@section('title', 'Homepage Content')
@section('page-title', 'Homepage Content')
@section('page-subtitle', 'Manage hero slides, gallery, steps, stats and marquee text')

@push('styles')
<style>
.content-tabs { display: flex; gap: 4px; border-bottom: 1px solid var(--border); margin-bottom: 24px; overflow-x: auto; }
.content-tab {
    padding: 10px 18px; font-size: 13px; font-weight: 600; color: var(--text-light);
    border-bottom: 2px solid transparent; cursor: pointer; white-space: nowrap;
    transition: color 0.2s, border-color 0.2s; background: none; border-left: none; border-right: none; border-top: none;
}
.content-tab.active { color: var(--crimson); border-bottom-color: var(--crimson); }
.content-tab:hover { color: var(--crimson); }

.drag-row {
    display: flex; align-items: center; gap: 12px;
    background: #fff; border: 1px solid var(--border); border-radius: 10px;
    padding: 12px 14px; margin-bottom: 8px; cursor: default;
}
.drag-handle { cursor: grab; color: var(--text-light); flex-shrink: 0; touch-action: none; }
.drag-handle:active { cursor: grabbing; }
.sortable-ghost { opacity: 0.4; }

.thumb-sm {
    width: 56px; height: 56px; border-radius: 8px; flex-shrink: 0; overflow: hidden;
    background: linear-gradient(135deg, var(--crimson-light) 0%, #ede3de 100%);
    display: flex; align-items: center; justify-content: center; font-size: 20px;
}
.thumb-sm img { width: 100%; height: 100%; object-fit: cover; }

.modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 50; display: flex; align-items: center; justify-content: center; padding: 20px; }
.modal-box { background: #fff; border-radius: 16px; max-width: 560px; width: 100%; max-height: 90vh; overflow-y: auto; padding: 28px; }

.toggle-switch { position: relative; display: inline-block; width: 38px; height: 22px; flex-shrink: 0; }
.toggle-switch input { opacity: 0; width: 0; height: 0; }
.toggle-slider { position: absolute; cursor: pointer; inset: 0; background: #e2dcd8; border-radius: 22px; transition: background 0.2s; }
.toggle-slider::before { position: absolute; content: ""; height: 16px; width: 16px; left: 3px; bottom: 3px; background: #fff; border-radius: 50%; transition: transform 0.2s; }
.toggle-switch input:checked + .toggle-slider { background: var(--crimson); }
.toggle-switch input:checked + .toggle-slider::before { transform: translateX(16px); }

.img-input-toggle { display: flex; gap: 6px; margin-bottom: 10px; }
.img-input-toggle button {
    flex: 1; padding: 7px 10px; font-size: 12px; font-weight: 600; border-radius: 7px;
    border: 1.5px solid var(--border); background: #fff; color: var(--text-mid); cursor: pointer;
}
.img-input-toggle button.active { border-color: var(--crimson); color: var(--crimson); background: var(--crimson-light); }
</style>
@endpush

@section('content')
<div x-data="contentHub()" x-init="init()">

    {{-- ═══ TABS ═══ --}}
    <div class="content-tabs">
        <button class="content-tab" :class="{active: tab==='hero'}" @click="tab='hero'">Hero Slides</button>
        <button class="content-tab" :class="{active: tab==='gallery'}" @click="tab='gallery'">Gallery</button>
        <button class="content-tab" :class="{active: tab==='steps'}" @click="tab='steps'">How It Works</button>
        <button class="content-tab" :class="{active: tab==='stats'}" @click="tab='stats'">Stats</button>
        <button class="content-tab" :class="{active: tab==='marquee'}" @click="tab='marquee'">Marquee</button>
        <button class="content-tab" :class="{active: tab==='about'}" @click="tab='about'">About Page</button>
        <button class="content-tab" :class="{active: tab==='contact'}" @click="tab='contact'">Contact Info</button>
    </div>

    {{-- ═══ HERO SLIDES TAB ═══ --}}
    <div x-show="tab==='hero'" x-cloak>
        <div class="flex items-center justify-between mb-4">
            <p class="text-xs" style="color:var(--text-light)">Drag the handle to reorder. These appear on the homepage hero carousel.</p>
            <button class="btn-crimson btn-sm" @click="openHeroModal()">+ Add Slide</button>
        </div>

        <div id="hero-sortable">
            @foreach($heroSlides as $slide)
            <div class="drag-row" data-id="{{ $slide->id }}">
                <span class="drag-handle">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><circle cx="8" cy="6" r="1.5"/><circle cx="8" cy="12" r="1.5"/><circle cx="8" cy="18" r="1.5"/><circle cx="16" cy="6" r="1.5"/><circle cx="16" cy="12" r="1.5"/><circle cx="16" cy="18" r="1.5"/></svg>
                </span>
                <div class="thumb-sm">
                    @if($slide->image_url)
                        <img src="{{ $slide->image_url }}" alt="">
                    @else
                        🖼️
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-semibold truncate" style="color:var(--text)">{{ Str::limit(str_replace("\n", ' · ', $slide->heading), 60) }}</div>
                    <div class="text-xs truncate" style="color:var(--text-light)">{{ Str::limit($slide->subtitle, 70) }}</div>
                </div>
                <span class="text-xs px-2 py-0.5 rounded-full font-semibold flex-shrink-0" style="{{ $slide->is_active ? 'background:#f0fdf4;color:#15803d' : 'background:#f9fafb;color:#9ca3af' }}">
                    {{ $slide->is_active ? 'Active' : 'Hidden' }}
                </span>
                <button class="btn-ghost btn-sm" @click='openHeroModal(@json($slide))'>Edit</button>
                <form method="POST" action="{{ route('portal.admin.content.hero.destroy', $slide) }}" onsubmit="return confirm('Delete this slide?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-danger btn-sm">Delete</button>
                </form>
            </div>
            @endforeach
        </div>
        @if($heroSlides->isEmpty())
        <p class="text-sm text-center py-10" style="color:var(--text-light)">No hero slides yet. Add one to get started.</p>
        @endif
    </div>

    {{-- ═══ GALLERY TAB ═══ --}}
    <div x-show="tab==='gallery'" x-cloak>
        <div class="flex items-center justify-between mb-4">
            <p class="text-xs" style="color:var(--text-light)">Drag to reorder. Shown in the "Activities & Achievements" section.</p>
            <button class="btn-crimson btn-sm" @click="openGalleryModal()">+ Add Photo</button>
        </div>

        <div id="gallery-sortable">
            @foreach($galleryItems as $item)
            <div class="drag-row" data-id="{{ $item->id }}">
                <span class="drag-handle">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><circle cx="8" cy="6" r="1.5"/><circle cx="8" cy="12" r="1.5"/><circle cx="8" cy="18" r="1.5"/><circle cx="16" cy="6" r="1.5"/><circle cx="16" cy="12" r="1.5"/><circle cx="16" cy="18" r="1.5"/></svg>
                </span>
                <div class="thumb-sm">
                    @if($item->image_url)<img src="{{ $item->image_url }}" alt="">@else 📷 @endif
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-semibold truncate" style="color:var(--text)">{{ $item->caption ?: '(no caption)' }}</div>
                    <div class="text-xs" style="color:var(--text-light)">Height: {{ $item->height ?: 260 }}px</div>
                </div>
                <span class="text-xs px-2 py-0.5 rounded-full font-semibold flex-shrink-0" style="{{ $item->is_active ? 'background:#f0fdf4;color:#15803d' : 'background:#f9fafb;color:#9ca3af' }}">
                    {{ $item->is_active ? 'Active' : 'Hidden' }}
                </span>
                <button class="btn-ghost btn-sm" @click='openGalleryModal(@json($item))'>Edit</button>
                <form method="POST" action="{{ route('portal.admin.content.gallery.destroy', $item) }}" onsubmit="return confirm('Delete this photo?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-danger btn-sm">Delete</button>
                </form>
            </div>
            @endforeach
        </div>
        @if($galleryItems->isEmpty())
        <p class="text-sm text-center py-10" style="color:var(--text-light)">No gallery photos yet.</p>
        @endif
    </div>

    {{-- ═══ HOW IT WORKS TAB ═══ --}}
    <div x-show="tab==='steps'" x-cloak>
        <div class="flex items-center justify-between mb-4">
            <p class="text-xs" style="color:var(--text-light)">Drag to reorder. Step numbers are generated automatically from this order.</p>
            <button class="btn-crimson btn-sm" @click="openStepModal()">+ Add Step</button>
        </div>

        <div id="steps-sortable">
            @foreach($steps as $i => $step)
            <div class="drag-row" data-id="{{ $step->id }}">
                <span class="drag-handle">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><circle cx="8" cy="6" r="1.5"/><circle cx="8" cy="12" r="1.5"/><circle cx="8" cy="18" r="1.5"/><circle cx="16" cy="6" r="1.5"/><circle cx="16" cy="12" r="1.5"/><circle cx="16" cy="18" r="1.5"/></svg>
                </span>
                <div class="thumb-sm" style="background:var(--crimson-light)">
                    <svg class="w-5 h-5" style="color:var(--crimson)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $step->icon_path }}"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-xs font-bold mb-0.5" style="color:var(--gold)">STEP {{ $i + 1 }}</div>
                    <div class="text-sm font-semibold truncate" style="color:var(--text)">{{ $step->title }}</div>
                    <div class="text-xs truncate" style="color:var(--text-light)">{{ Str::limit($step->description, 70) }}</div>
                </div>
                <span class="text-xs px-2 py-0.5 rounded-full font-semibold flex-shrink-0" style="{{ $step->is_active ? 'background:#f0fdf4;color:#15803d' : 'background:#f9fafb;color:#9ca3af' }}">
                    {{ $step->is_active ? 'Active' : 'Hidden' }}
                </span>
                <button class="btn-ghost btn-sm" @click='openStepModal(@json($step))'>Edit</button>
                <form method="POST" action="{{ route('portal.admin.content.steps.destroy', $step) }}" onsubmit="return confirm('Delete this step?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-danger btn-sm">Delete</button>
                </form>
            </div>
            @endforeach
        </div>
        @if($steps->isEmpty())
        <p class="text-sm text-center py-10" style="color:var(--text-light)">No steps yet.</p>
        @endif
    </div>

    {{-- ═══ STATS TAB ═══ --}}
    <div x-show="tab==='stats'" x-cloak>
        <p class="text-xs mb-4" style="color:var(--text-light)">These four numbers appear in the hero stats bar and the "How It Works" stats strip. Toggle "Auto" to calculate live from the database, or switch it off to enter a fixed number.</p>

        <form method="POST" action="{{ route('portal.admin.content.stats.update') }}">
            @csrf @method('PUT')
            <div class="portal-card space-y-4">
                @foreach($stats as $i => $stat)
                <div class="flex items-center gap-4 pb-4 {{ !$loop->last ? 'border-b' : '' }}" style="border-color:var(--border)">
                    <input type="hidden" name="stats[{{ $i }}][id]" value="{{ $stat->id }}">

                    <div class="flex-1">
                        <label class="form-label text-xs">Label</label>
                        <input type="text" name="stats[{{ $i }}][label]" value="{{ $stat->label }}" class="form-input" required>
                    </div>
                    <div style="width:90px">
                        <label class="form-label text-xs">Suffix</label>
                        <input type="text" name="stats[{{ $i }}][suffix]" value="{{ $stat->suffix }}" class="form-input" placeholder="e.g. + or h">
                    </div>
                    <div style="width:140px">
                        <label class="form-label text-xs">Manual Value</label>
                        <input type="text" name="stats[{{ $i }}][manual_value]" value="{{ $stat->manual_value }}" class="form-input"
                               x-data="{ auto: {{ $stat->is_auto ? 'true' : 'false' }} }"
                               :disabled="auto" :style="auto ? 'opacity:.5' : ''">
                    </div>
                    <div class="text-center" style="width:70px" x-data="{ auto: {{ $stat->is_auto ? 'true' : 'false' }} }">
                        <label class="form-label text-xs">Auto</label>
                        <label class="toggle-switch">
                            <input type="checkbox" name="stats[{{ $i }}][is_auto]" value="1" x-model="auto" {{ $stat->is_auto ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                        <div class="text-xs mt-1" style="color:var(--text-light)">{{ $stat->display_value }}{{ $stat->suffix }}</div>
                    </div>
                </div>
                @endforeach
            </div>
            <button type="submit" class="btn-crimson mt-4">Save Stats</button>
        </form>
    </div>

    {{-- ═══ MARQUEE TAB ═══ --}}
    <div x-show="tab==='marquee'" x-cloak>
        <div class="flex items-center justify-between mb-4">
            <p class="text-xs" style="color:var(--text-light)">Drag to reorder. These short phrases scroll in the ticker band.</p>
            <button class="btn-crimson btn-sm" @click="openMarqueeModal()">+ Add Text</button>
        </div>

        <div id="marquee-sortable">
            @foreach($marqueeItems as $item)
            <div class="drag-row" data-id="{{ $item->id }}">
                <span class="drag-handle">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><circle cx="8" cy="6" r="1.5"/><circle cx="8" cy="12" r="1.5"/><circle cx="8" cy="18" r="1.5"/><circle cx="16" cy="6" r="1.5"/><circle cx="16" cy="12" r="1.5"/><circle cx="16" cy="18" r="1.5"/></svg>
                </span>
                <div class="flex-1 text-sm font-medium" style="color:var(--text)">{{ $item->text }}</div>
                <span class="text-xs px-2 py-0.5 rounded-full font-semibold flex-shrink-0" style="{{ $item->is_active ? 'background:#f0fdf4;color:#15803d' : 'background:#f9fafb;color:#9ca3af' }}">
                    {{ $item->is_active ? 'Active' : 'Hidden' }}
                </span>
                <button class="btn-ghost btn-sm" @click='openMarqueeModal(@json($item))'>Edit</button>
                <form method="POST" action="{{ route('portal.admin.content.marquee.destroy', $item) }}" onsubmit="return confirm('Delete this text?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-danger btn-sm">Delete</button>
                </form>
            </div>
            @endforeach
        </div>
        @if($marqueeItems->isEmpty())
        <p class="text-sm text-center py-10" style="color:var(--text-light)">No marquee text yet.</p>
        @endif
    </div>

    {{-- ═══ ABOUT PAGE TAB ═══ --}}
    <div x-show="tab==='about'" x-cloak>
        <p class="text-xs mb-4" style="color:var(--text-light)">These two paragraphs appear on the About page under "Our Mission" and "Our Vision".</p>

        <form method="POST" action="{{ route('portal.admin.content.settings.about.update') }}">
            @csrf @method('PUT')
            <div class="portal-card space-y-5">
                <div>
                    <label class="form-label">Our Mission</label>
                    <textarea name="about_mission" class="form-input" rows="4" required>{{ old('about_mission', $aboutSettings['about_mission']) }}</textarea>
                </div>
                <div>
                    <label class="form-label">Our Vision</label>
                    <textarea name="about_vision" class="form-input" rows="4" required>{{ old('about_vision', $aboutSettings['about_vision']) }}</textarea>
                </div>
            </div>
            <button type="submit" class="btn-crimson mt-4">Save About Page</button>
        </form>
    </div>

    {{-- ═══ CONTACT INFO TAB ═══ --}}
    <div x-show="tab==='contact'" x-cloak>
        <p class="text-xs mb-4" style="color:var(--text-light)">Shown on the Contact page and used as the address for incoming contact-form notifications.</p>

        <form method="POST" action="{{ route('portal.admin.content.settings.contact.update') }}">
            @csrf @method('PUT')
            <div class="portal-card space-y-5">
                <div>
                    <label class="form-label">Address <span class="font-normal" style="color:var(--text-light)">(use a new line to break it onto two lines)</span></label>
                    <textarea name="contact_address" class="form-input" rows="2" required>{{ old('contact_address', $contactSettings['contact_address']) }}</textarea>
                </div>
                <div>
                    <label class="form-label">Email Address</label>
                    <input type="email" name="contact_email" class="form-input" required value="{{ old('contact_email', $contactSettings['contact_email']) }}">
                </div>
                <div>
                    <label class="form-label">Clinic Hours <span class="font-normal" style="color:var(--text-light)">(use a new line to break it onto two lines)</span></label>
                    <textarea name="contact_hours" class="form-input" rows="2" required>{{ old('contact_hours', $contactSettings['contact_hours']) }}</textarea>
                </div>
            </div>
            <button type="submit" class="btn-crimson mt-4">Save Contact Info</button>
        </form>
    </div>


    {{-- ═══════════════════════════════════════════════════════════════════
         MODALS
    ═══════════════════════════════════════════════════════════════════ --}}

    {{-- Hero Slide Modal --}}
    <div class="modal-overlay" x-show="heroModalOpen" x-cloak @click.self="heroModalOpen=false">
        <div class="modal-box">
            <h3 class="text-lg font-semibold mb-5" style="font-family:'DM Serif Display',serif;color:var(--text)" x-text="heroForm.id ? 'Edit Hero Slide' : 'Add Hero Slide'"></h3>
            <form :action="heroForm.id ? heroUpdateUrl(heroForm.id) : heroStoreUrl" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <template x-if="heroForm.id"><input type="hidden" name="_method" value="PUT"></template>

                <div>
                    <label class="form-label">Heading <span class="font-normal" style="color:var(--text-light)">(last line shows in gold — use a new line to break it)</span></label>
                    <textarea name="heading" x-model="heroForm.heading" class="form-input" rows="3" required placeholder="Free Legal&#10;Guidance for Every Need"></textarea>
                </div>
                <div>
                    <label class="form-label">Subtitle</label>
                    <textarea name="subtitle" x-model="heroForm.subtitle" class="form-input" rows="2"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="form-label text-xs">Primary button label</label>
                        <input type="text" name="primary_cta_label" x-model="heroForm.primary_cta_label" class="form-input">
                    </div>
                    <div>
                        <label class="form-label text-xs">Primary button link</label>
                        <input type="text" name="primary_cta_url" x-model="heroForm.primary_cta_url" class="form-input" placeholder="/get-legal-help">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="form-label text-xs">Secondary button label</label>
                        <input type="text" name="secondary_cta_label" x-model="heroForm.secondary_cta_label" class="form-input">
                    </div>
                    <div>
                        <label class="form-label text-xs">Secondary button link</label>
                        <input type="text" name="secondary_cta_url" x-model="heroForm.secondary_cta_url" class="form-input">
                    </div>
                </div>

                <div>
                    <label class="form-label">Background Image</label>
                    <template x-if="heroForm.image_url_existing">
                        <img :src="heroForm.image_url_existing" class="w-full h-28 object-cover rounded-lg mb-2" style="border:1px solid var(--border)">
                    </template>
                    <div class="img-input-toggle">
                        <button type="button" :class="{active: heroImgMode==='upload'}" @click="heroImgMode='upload'">Upload File</button>
                        <button type="button" :class="{active: heroImgMode==='url'}" @click="heroImgMode='url'">Paste URL / Path</button>
                    </div>
                    <input type="file" name="image" accept="image/*" class="form-input" x-show="heroImgMode==='upload'">
                    <input type="text" name="image_url" x-model="heroForm.image_url" class="form-input" x-show="heroImgMode==='url'" placeholder="https://... or assets/img/hero/x.jpg">
                </div>

                <template x-if="heroForm.id">
                <div class="flex items-center gap-2">
                    <label class="toggle-switch"><input type="checkbox" name="is_active" value="1" x-model="heroForm.is_active"><span class="toggle-slider"></span></label>
                    <span class="text-sm" style="color:var(--text-mid)">Visible on homepage</span>
                </div>
                </template>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-crimson">Save</button>
                    <button type="button" class="btn-ghost" @click="heroModalOpen=false">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Gallery Modal --}}
    <div class="modal-overlay" x-show="galleryModalOpen" x-cloak @click.self="galleryModalOpen=false">
        <div class="modal-box">
            <h3 class="text-lg font-semibold mb-5" style="font-family:'DM Serif Display',serif;color:var(--text)" x-text="galleryForm.id ? 'Edit Photo' : 'Add Photo'"></h3>
            <form :action="galleryForm.id ? galleryUpdateUrl(galleryForm.id) : galleryStoreUrl" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <template x-if="galleryForm.id"><input type="hidden" name="_method" value="PUT"></template>

                <div>
                    <label class="form-label">Caption</label>
                    <input type="text" name="caption" x-model="galleryForm.caption" class="form-input" placeholder="e.g. Legal Aid Clinic Session">
                </div>
                <div>
                    <label class="form-label">Tile Height (px) <span class="font-normal" style="color:var(--text-light)">— controls masonry variation, 120–600</span></label>
                    <input type="number" name="height" x-model="galleryForm.height" class="form-input" min="120" max="600" placeholder="260">
                </div>

                <div>
                    <label class="form-label">Photo</label>
                    <template x-if="galleryForm.image_url_existing">
                        <img :src="galleryForm.image_url_existing" class="w-full h-28 object-cover rounded-lg mb-2" style="border:1px solid var(--border)">
                    </template>
                    <div class="img-input-toggle">
                        <button type="button" :class="{active: galleryImgMode==='upload'}" @click="galleryImgMode='upload'">Upload File</button>
                        <button type="button" :class="{active: galleryImgMode==='url'}" @click="galleryImgMode='url'">Paste URL / Path</button>
                    </div>
                    <input type="file" name="image" accept="image/*" class="form-input" x-show="galleryImgMode==='upload'">
                    <input type="text" name="image_url" x-model="galleryForm.image_url" class="form-input" x-show="galleryImgMode==='url'" placeholder="https://... or assets/img/gallery/x.jpg">
                </div>

                <template x-if="galleryForm.id">
                <div class="flex items-center gap-2">
                    <label class="toggle-switch"><input type="checkbox" name="is_active" value="1" x-model="galleryForm.is_active"><span class="toggle-slider"></span></label>
                    <span class="text-sm" style="color:var(--text-mid)">Visible on homepage</span>
                </div>
                </template>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-crimson">Save</button>
                    <button type="button" class="btn-ghost" @click="galleryModalOpen=false">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Step Modal --}}
    <div class="modal-overlay" x-show="stepModalOpen" x-cloak @click.self="stepModalOpen=false">
        <div class="modal-box">
            <h3 class="text-lg font-semibold mb-5" style="font-family:'DM Serif Display',serif;color:var(--text)" x-text="stepForm.id ? 'Edit Step' : 'Add Step'"></h3>
            <form :action="stepForm.id ? stepUpdateUrl(stepForm.id) : stepStoreUrl" method="POST" class="space-y-4">
                @csrf
                <template x-if="stepForm.id"><input type="hidden" name="_method" value="PUT"></template>

                <div>
                    <label class="form-label">Title</label>
                    <input type="text" name="title" x-model="stepForm.title" class="form-input" required placeholder="Submit Your Enquiry">
                </div>
                <div>
                    <label class="form-label">Description</label>
                    <textarea name="description" x-model="stepForm.description" class="form-input" rows="3" required></textarea>
                </div>
                <div>
                    <label class="form-label">Icon</label>
                    <select name="icon_key" x-model="stepForm.icon_key" class="form-input">
                        @foreach($iconOptions as $key => $opt)
                        <option value="{{ $key }}">{{ $opt['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <template x-if="stepForm.id">
                <div class="flex items-center gap-2">
                    <label class="toggle-switch"><input type="checkbox" name="is_active" value="1" x-model="stepForm.is_active"><span class="toggle-slider"></span></label>
                    <span class="text-sm" style="color:var(--text-mid)">Visible on homepage</span>
                </div>
                </template>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-crimson">Save</button>
                    <button type="button" class="btn-ghost" @click="stepModalOpen=false">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Marquee Modal --}}
    <div class="modal-overlay" x-show="marqueeModalOpen" x-cloak @click.self="marqueeModalOpen=false">
        <div class="modal-box" style="max-width:420px">
            <h3 class="text-lg font-semibold mb-5" style="font-family:'DM Serif Display',serif;color:var(--text)" x-text="marqueeForm.id ? 'Edit Text' : 'Add Text'"></h3>
            <form :action="marqueeForm.id ? marqueeUpdateUrl(marqueeForm.id) : marqueeStoreUrl" method="POST" class="space-y-4">
                @csrf
                <template x-if="marqueeForm.id"><input type="hidden" name="_method" value="PUT"></template>

                <div>
                    <label class="form-label">Text</label>
                    <input type="text" name="text" x-model="marqueeForm.text" class="form-input" required maxlength="100" placeholder="e.g. Know Your Rights">
                </div>

                <template x-if="marqueeForm.id">
                <div class="flex items-center gap-2">
                    <label class="toggle-switch"><input type="checkbox" name="is_active" value="1" x-model="marqueeForm.is_active"><span class="toggle-slider"></span></label>
                    <span class="text-sm" style="color:var(--text-mid)">Visible in ticker</span>
                </div>
                </template>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-crimson">Save</button>
                    <button type="button" class="btn-ghost" @click="marqueeModalOpen=false">Cancel</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.2/Sortable.min.js"></script>
<script>
function contentHub() {
    return {
        tab: 'hero',

        heroModalOpen: false,
        heroForm: {},
        heroImgMode: 'upload',
        heroStoreUrl: "{{ route('portal.admin.content.hero.store') }}",
        heroUpdateUrl(id) { return "{{ url('/portal/admin/content/hero') }}/" + id; },
        openHeroModal(slide = null) {
            this.heroForm = slide ? {
                id: slide.id,
                heading: slide.heading,
                subtitle: slide.subtitle,
                primary_cta_label: slide.primary_cta_label,
                primary_cta_url: slide.primary_cta_url,
                secondary_cta_label: slide.secondary_cta_label,
                secondary_cta_url: slide.secondary_cta_url,
                is_active: slide.is_active,
                image_url_existing: slide.image_url,
                image_url: '',
            } : { id: null, heading: '', subtitle: '', is_active: true, image_url_existing: null, image_url: '' };
            this.heroImgMode = 'upload';
            this.heroModalOpen = true;
        },

        galleryModalOpen: false,
        galleryForm: {},
        galleryImgMode: 'upload',
        galleryStoreUrl: "{{ route('portal.admin.content.gallery.store') }}",
        galleryUpdateUrl(id) { return "{{ url('/portal/admin/content/gallery') }}/" + id; },
        openGalleryModal(item = null) {
            this.galleryForm = item ? {
                id: item.id, caption: item.caption, height: item.height,
                is_active: item.is_active, image_url_existing: item.image_url, image_url: '',
            } : { id: null, caption: '', height: 260, is_active: true, image_url_existing: null, image_url: '' };
            this.galleryImgMode = 'upload';
            this.galleryModalOpen = true;
        },

        stepModalOpen: false,
        stepForm: {},
        stepStoreUrl: "{{ route('portal.admin.content.steps.store') }}",
        stepUpdateUrl(id) { return "{{ url('/portal/admin/content/steps') }}/" + id; },
        openStepModal(step = null) {
            this.stepForm = step ? {
                id: step.id, title: step.title, description: step.description,
                icon_key: step.icon_key, is_active: step.is_active,
            } : { id: null, title: '', description: '', icon_key: 'document', is_active: true };
            this.stepModalOpen = true;
        },

        marqueeModalOpen: false,
        marqueeForm: {},
        marqueeStoreUrl: "{{ route('portal.admin.content.marquee.store') }}",
        marqueeUpdateUrl(id) { return "{{ url('/portal/admin/content/marquee') }}/" + id; },
        openMarqueeModal(item = null) {
            this.marqueeForm = item ? { id: item.id, text: item.text, is_active: item.is_active }
                                     : { id: null, text: '', is_active: true };
            this.marqueeModalOpen = true;
        },

        init() {
            this.$nextTick(() => {
                this.initSortable('hero-sortable', "{{ route('portal.admin.content.hero.reorder') }}");
                this.initSortable('gallery-sortable', "{{ route('portal.admin.content.gallery.reorder') }}");
                this.initSortable('steps-sortable', "{{ route('portal.admin.content.steps.reorder') }}");
                this.initSortable('marquee-sortable', "{{ route('portal.admin.content.marquee.reorder') }}");
            });
        },
        initSortable(containerId, endpoint) {
            const el = document.getElementById(containerId);
            if (!el || typeof Sortable === 'undefined') return;
            new Sortable(el, {
                handle: '.drag-handle',
                animation: 150,
                onEnd: function () {
                    const order = Array.from(el.children).map(c => c.dataset.id);
                    fetch(endpoint, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ order }),
                    });
                },
            });
        },
    };
}
</script>
@endpush

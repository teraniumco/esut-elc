@extends('portal.layout')
@section('title', 'Enquiries')
@section('page-title', 'Enquiries')
@section('page-subtitle', 'Legal enquiry inbox')

@section('content')

{{-- Filters --}}
<div class="portal-card mb-5">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="form-label text-xs">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Reference, name, description…" class="form-input" style="width:220px">
        </div>
        <div>
            <label class="form-label text-xs">Status</label>
            <select name="status" class="form-input" style="width:160px">
                <option value="">All statuses</option>
                @foreach(\App\Models\Enquiry::STATUSES as $k => $v)
                <option value="{{ $k }}" {{ request('status') === $k ? 'selected' : '' }}>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label text-xs">Urgency</label>
            <select name="urgency" class="form-input" style="width:130px">
                <option value="">All</option>
                <option value="urgent" {{ request('urgency')==='urgent'?'selected':'' }}>Urgent</option>
                <option value="normal" {{ request('urgency')==='normal'?'selected':'' }}>Normal</option>
            </select>
        </div>
        <div>
            <label class="form-label text-xs">Category</label>
            <select name="category" class="form-input" style="width:180px">
                <option value="">All categories</option>
                @foreach(\App\Models\Enquiry::MATTER_CATEGORIES as $k => $v)
                <option value="{{ $k }}" {{ request('category')===$k?'selected':'' }}>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn-crimson btn-sm">Filter</button>
            <a href="{{ route('portal.enquiries.index') }}" class="btn-ghost btn-sm">Clear</a>
        </div>
    </form>
</div>

{{-- Status tab pills --}}
<div class="flex flex-wrap gap-2 mb-5">
    <a href="{{ route('portal.enquiries.index') }}" class="text-xs px-3 py-1.5 rounded-full font-semibold transition-colors {{ !request('status') ? 'text-white' : 'bg-white border hover:bg-gray-50' }}" style="{{ !request('status') ? 'background:var(--crimson)' : 'border-color:var(--border);color:var(--text-mid)' }}">
        All ({{ $statusCounts->sum() }})
    </a>
    @foreach(\App\Models\Enquiry::STATUSES as $k => $v)
    @if($statusCounts->get($k, 0) > 0)
    <a href="{{ route('portal.enquiries.index', ['status'=>$k]) }}" class="text-xs px-3 py-1.5 rounded-full font-semibold transition-colors {{ request('status')===$k ? 'text-white' : 'bg-white border hover:bg-gray-50' }}" style="{{ request('status')===$k ? 'background:var(--crimson)' : 'border-color:var(--border);color:var(--text-mid)' }}">
        {{ $v }} ({{ $statusCounts->get($k, 0) }})
    </a>
    @endif
    @endforeach
</div>

{{-- Table --}}
<div class="portal-card overflow-hidden p-0">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="border-b" style="border-color:var(--border);background:var(--off-white)">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-semibold" style="color:var(--text-light)">Reference</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold" style="color:var(--text-light)">From</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold hidden md:table-cell" style="color:var(--text-light)">Category</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold hidden lg:table-cell" style="color:var(--text-light)">Advisor</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold" style="color:var(--text-light)">Status</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold hidden md:table-cell" style="color:var(--text-light)">Submitted</th>
                </tr>
            </thead>
            <tbody>
            @forelse($enquiries as $enq)
            <tr class="border-b hover:bg-gray-50 cursor-pointer" style="border-color:var(--border)" onclick="window.location='{{ route('portal.enquiries.show', $enq) }}'">
                <td class="px-5 py-3.5">
                    <div class="flex items-center gap-2">
                        @if($enq->urgency === 'urgent') <span class="w-1.5 h-1.5 rounded-full bg-red-500 flex-shrink-0"></span> @endif
                        <a href="{{ route('portal.enquiries.show', $enq) }}" class="font-mono text-xs font-bold hover:underline" style="color:var(--crimson)">{{ $enq->reference_code }}</a>
                    </div>
                </td>
                <td class="px-4 py-3.5 text-xs" style="color:var(--text-mid)">{{ $enq->display_name }}</td>
                <td class="px-4 py-3.5 text-xs hidden md:table-cell" style="color:var(--text-mid)">{{ $enq->category_label }}</td>
                <td class="px-4 py-3.5 text-xs hidden lg:table-cell" style="color:var(--text-mid)">{{ $enq->activeAssignment?->advisor->name ?? '—' }}</td>
                <td class="px-4 py-3.5"><span class="status-badge status-{{ $enq->status }}">{{ $enq->status_label }}</span></td>
                <td class="px-4 py-3.5 text-xs hidden md:table-cell" style="color:var(--text-light)">{{ $enq->created_at->format('d M Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-5 py-12 text-center text-sm" style="color:var(--text-light)">No enquiries match your filters.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($enquiries->hasPages())
    <div class="px-5 py-4 border-t" style="border-color:var(--border)">{{ $enquiries->links() }}</div>
    @endif
</div>
@endsection

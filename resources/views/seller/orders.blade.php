@extends('layouts.seller')

@section('title', 'Antrian Pesanan')
@section('heading', 'Antrian Pesanan')

@section('content')
<div class="space-y-6" x-data="orderQueue()">

    {{-- ── Header ──────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-dark">Antrian Pesanan</h2>
            <p class="text-gray-500 mt-1">Kelola pesanan masuk berdasarkan waktu pengambilan.</p>
        </div>
        <div class="flex items-center gap-2">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                <input type="date" x-model="dateFilter" @change="applyFilters()"
                       class="pl-9 pr-3 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-brand-400/30 focus:border-brand-400 outline-none transition-all">
            </div>
        </div>
    </div>

    {{-- ── Summary Cards ───────────────────────────────────────── --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-yellow-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </span>
                <div>
                    <p class="text-2xl font-bold text-dark">{{ $summary['pending'] }}</p>
                    <p class="text-xs text-gray-400 font-medium">Menunggu</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0 1 12 21 8.25 8.25 0 0 1 6.038 7.047 8.287 8.287 0 0 0 9 9.601a8.983 8.983 0 0 1 3.361-6.867 8.21 8.21 0 0 0 3 2.48Z"/></svg>
                </span>
                <div>
                    <p class="text-2xl font-bold text-dark">{{ $summary['preparing'] }}</p>
                    <p class="text-xs text-gray-400 font-medium">Diproses</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </span>
                <div>
                    <p class="text-2xl font-bold text-dark">{{ $summary['ready'] }}</p>
                    <p class="text-xs text-gray-400 font-medium">Siap Ambil</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                </span>
                <div>
                    <p class="text-2xl font-bold text-dark">{{ $summary['completed'] }}</p>
                    <p class="text-xs text-gray-400 font-medium">Selesai</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Pickup Time Tabs + Status Filter ────────────────────── --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-gray-100">
            {{-- Pickup tabs --}}
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0 scrollbar-hide">
                <button @click="pickupFilter = ''; applyFilters()"
                        :class="pickupFilter === '' ? 'bg-brand-500 text-white shadow-sm' : 'bg-gray-50 text-gray-600 hover:bg-gray-100'"
                        class="px-4 py-2 rounded-xl text-sm font-semibold transition-all whitespace-nowrap">
                    Semua
                    <span class="ml-1 text-xs opacity-75">({{ $summary['total'] }})</span>
                </button>
                @php
                    $pickupLabels = [
                        'Istirahat 1' => ['label' => 'Istirahat Awal'],
                        'Istirahat 2' => ['label' => 'Istirahat Akhir'],
                        'Pulang'      => ['label' => 'Pulang'],
                        'Sekarang'    => ['label' => 'Sekarang'],
                    ];
                @endphp
                @foreach($pickupTimes as $pt)
                @php $count = isset($orders[$pt]) ? $orders[$pt]->count() : 0; @endphp
                <button @click="pickupFilter = '{{ $pt }}'; applyFilters()"
                        :class="pickupFilter === '{{ $pt }}' ? 'bg-brand-500 text-white shadow-sm' : 'bg-gray-50 text-gray-600 hover:bg-gray-100'"
                        class="px-4 py-2 rounded-xl text-sm font-semibold transition-all whitespace-nowrap">
                    {{ $pickupLabels[$pt]['label'] ?? $pt }}
                    @if($count > 0)
                    <span class="ml-0.5 text-xs opacity-75">({{ $count }})</span>
                    @endif
                </button>
                @endforeach
            </div>

            {{-- Status filter --}}
            <div class="flex items-center gap-1.5 flex-wrap">
                <button @click="statusFilter = ''; applyFilters()"
                        :class="statusFilter === '' ? 'bg-gray-800 text-white' : 'bg-gray-50 text-gray-500 hover:bg-gray-100'"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all">Semua Status</button>
                <button @click="statusFilter = 'pending'; applyFilters()"
                        :class="statusFilter === 'pending' ? 'bg-yellow-500 text-white' : 'bg-yellow-50 text-yellow-600 hover:bg-yellow-100'"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all">Menunggu</button>
                <button @click="statusFilter = 'preparing'; applyFilters()"
                        :class="statusFilter === 'preparing' ? 'bg-blue-500 text-white' : 'bg-blue-50 text-blue-600 hover:bg-blue-100'"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all">Diproses</button>
                <button @click="statusFilter = 'ready'; applyFilters()"
                        :class="statusFilter === 'ready' ? 'bg-green-500 text-white' : 'bg-green-50 text-green-600 hover:bg-green-100'"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all">Siap Ambil</button>
                <button @click="statusFilter = 'completed'; applyFilters()"
                        :class="statusFilter === 'completed' ? 'bg-gray-500 text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all">Selesai</button>
            </div>
        </div>
    </div>

    {{-- ── Orders ──────────────────────────────────────────────── --}}
    @if($orders->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 p-16 text-center">
            <div class="w-20 h-20 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z"/></svg>
            </div>
            <p class="text-gray-400 font-semibold text-lg">Belum ada pesanan</p>
            <p class="text-gray-300 text-sm mt-1">Pesanan untuk tanggal ini akan muncul di sini.</p>
        </div>
    @else
        @foreach($orders as $pickupTime => $group)
        @php
            $pickupInfo = $pickupLabels[$pickupTime] ?? ['label' => $pickupTime];
            $pickupColorMap = [
                'Istirahat 1' => 'bg-amber-500',
                'Istirahat 2' => 'bg-sky-500',
                'Pulang'      => 'bg-violet-500',
                'Sekarang'    => 'bg-emerald-500',
            ];
            $pickupBg = $pickupColorMap[$pickupTime] ?? 'bg-gray-500';
            $activeCount = $group->whereNotIn('status', ['completed', 'cancelled'])->count();
        @endphp
        <div class="space-y-4">
            {{-- Section header --}}
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2.5 px-4 py-2.5 {{ $pickupBg }} rounded-xl shadow-sm">
                    <span class="text-sm font-bold text-white">{{ $pickupInfo['label'] }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-white border border-gray-200 rounded-lg text-xs font-semibold text-gray-500">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0"/></svg>
                        {{ $group->count() }} pesanan
                    </span>
                    @if($activeCount > 0)
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-red-50 border border-red-100 rounded-lg text-xs font-bold text-red-500 animate-pulse">
                        {{ $activeCount }} aktif
                    </span>
                    @endif
                </div>
                <div class="flex-1 border-t border-dashed border-gray-200"></div>
            </div>

            {{-- Order cards --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach($group as $queueIndex => $order)
                @php
                    $statusConfig = [
                        'pending'   => ['color' => 'yellow', 'label' => 'Menunggu',    'border' => 'border-l-yellow-400', 'bg' => 'bg-yellow-50/40', 'badge' => 'bg-yellow-100 text-yellow-700 ring-yellow-200'],
                        'preparing' => ['color' => 'blue',   'label' => 'Diproses',    'border' => 'border-l-blue-400',   'bg' => 'bg-blue-50/40',   'badge' => 'bg-blue-100 text-blue-700 ring-blue-200'],
                        'ready'     => ['color' => 'green',  'label' => 'Siap Ambil',  'border' => 'border-l-green-400',  'bg' => 'bg-green-50/40',  'badge' => 'bg-green-100 text-green-700 ring-green-200'],
                        'completed' => ['color' => 'gray',   'label' => 'Selesai',     'border' => 'border-l-gray-300',   'bg' => 'bg-gray-50/40',   'badge' => 'bg-gray-100 text-gray-500 ring-gray-200'],
                        'cancelled' => ['color' => 'red',    'label' => 'Dibatalkan',  'border' => 'border-l-red-300',    'bg' => 'bg-red-50/40',    'badge' => 'bg-red-100 text-red-600 ring-red-200'],
                    ];
                    $cfg = $statusConfig[$order->status] ?? $statusConfig['pending'];
                @endphp
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden border-l-4 {{ $cfg['border'] }} {{ in_array($order->status, ['completed', 'cancelled']) ? 'opacity-60' : '' }} hover:shadow-md transition-shadow">

                    {{-- Card Header --}}
                    <div class="px-5 py-4 {{ $cfg['bg'] }}">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-3">
                                {{-- Queue number --}}
                                <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center border border-gray-100">
                                    <span class="text-sm font-extrabold text-dark">#{{ $queueIndex + 1 }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-dark">{{ $order->user->name }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                        {{ $order->created_at->format('H:i') }}
                                        <span class="mx-0.5">·</span>
                                        #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                                    </p>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 text-[11px] font-bold rounded-lg ring-1 {{ $cfg['badge'] }}">
                                {{ $cfg['label'] }}
                            </span>
                        </div>
                    </div>

                    {{-- Items --}}
                    <div class="px-5 py-3">
                        <div class="space-y-2">
                            @foreach($order->items as $item)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-7 h-7 rounded-lg bg-brand-50 text-brand-600 text-xs font-bold flex items-center justify-center shrink-0">{{ $item->quantity }}x</span>
                                    <span class="text-sm text-gray-700 font-medium">{{ $item->menu_name }}</span>
                                </div>
                                <span class="text-xs text-gray-400 font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-between">
                        <div>
                            <span class="text-[11px] text-gray-400 uppercase tracking-wider font-semibold">Total</span>
                            <p class="text-base font-extrabold text-dark">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                        </div>

                        @if(!in_array($order->status, ['completed', 'cancelled']))
                        <div class="flex items-center gap-2">
                            @if($order->status === 'pending')
                                <form method="POST" action="{{ route('seller.orders.update', $order) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="preparing">
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition-colors shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0 1 12 21 8.25 8.25 0 0 1 6.038 7.047 8.287 8.287 0 0 0 9 9.601a8.983 8.983 0 0 1 3.361-6.867 8.21 8.21 0 0 0 3 2.48Z"/></svg>
                                        Proses
                                    </button>
                                </form>
                            @elseif($order->status === 'preparing')
                                <form method="POST" action="{{ route('seller.orders.update', $order) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="ready">
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold bg-green-500 text-white rounded-xl hover:bg-green-600 transition-colors shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                                        Siap Ambil
                                    </button>
                                </form>
                            @elseif($order->status === 'ready')
                                <form method="POST" action="{{ route('seller.orders.update', $order) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold bg-gray-700 text-white rounded-xl hover:bg-gray-800 transition-colors shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                                        Selesai
                                    </button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('seller.orders.update', $order) }}" onsubmit="return confirm('Batalkan pesanan ini?')">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="cancelled">
                                <button type="submit" class="p-2 text-red-400 bg-red-50 rounded-xl hover:bg-red-100 hover:text-red-500 transition-colors" title="Batalkan">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>

                    {{-- Dismiss button for completed/cancelled --}}
                    @if(in_array($order->status, ['completed', 'cancelled']))
                    <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-end">
                        <form method="POST" action="{{ route('seller.orders.dismiss', $order) }}" onsubmit="return confirm('Hapus pesanan ini dari antrian?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-gray-400 bg-gray-50 rounded-lg hover:bg-red-50 hover:text-red-500 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                                Hapus dari antrian
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    @endif
</div>
@endsection

@push('scripts')
<script>
function orderQueue() {
    return {
        dateFilter: '{{ request("date", today()->format("Y-m-d")) }}',
        pickupFilter: '{{ request("pickup", "") }}',
        statusFilter: '{{ request("status", "") }}',

        applyFilters() {
            const params = new URLSearchParams();
            if (this.dateFilter) params.set('date', this.dateFilter);
            if (this.pickupFilter) params.set('pickup', this.pickupFilter);
            if (this.statusFilter) params.set('status', this.statusFilter);
            window.location.href = '{{ route("seller.orders") }}?' + params.toString();
        }
    }
}
</script>
@endpush

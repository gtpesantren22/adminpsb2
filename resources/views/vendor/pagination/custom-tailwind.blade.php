@if ($paginator->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 flex flex-col md:flex-row md:items-center justify-between">

        {{-- INFO JUMLAH DATA --}}
        <div class="text-sm text-gray-500 mb-4 md:mb-0">
            Menampilkan
            {{ $paginator->firstItem() }}
            sampai
            {{ $paginator->lastItem() }}
            dari
            {{ $paginator->total() }}
            entri
        </div>

        {{-- PAGINATION --}}
        <nav class="flex space-x-2">

            {{-- PREVIOUS --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-1 border border-gray-300 rounded-lg text-sm text-gray-400 cursor-not-allowed">
                    Previous
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="px-3 py-1 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">
                    Previous
                </a>
            @endif

            {{-- PAGE NUMBERS --}}
            @php
                $current = $paginator->currentPage();
                $last = $paginator->lastPage();
            @endphp

            {{-- PAGE NUMBERS --}}
            @if ($last <= 3)
                {{-- JIKA PAGE SEDIKIT --}}
                @for ($i = 1; $i <= $last; $i++)
                    @if ($i == $current)
                        <span
                            class="px-3 py-1 border border-secondary-blue bg-secondary-blue text-white rounded-lg text-sm">
                            {{ $i }}
                        </span>
                    @else
                        <a href="{{ $paginator->url($i) }}"
                            class="px-3 py-1 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">
                            {{ $i }}
                        </a>
                    @endif
                @endfor
            @else
                {{-- PAGE BANYAK --}}

                {{-- PAGE 1 --}}
                @if ($current == 1)
                    <span
                        class="px-3 py-1 border border-secondary-blue bg-secondary-blue text-white rounded-lg text-sm">1</span>
                @else
                    <a href="{{ $paginator->url(1) }}"
                        class="px-3 py-1 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">1</a>
                @endif

                {{-- TITIK KIRI --}}
                @if ($current > 3)
                    <span class="px-3 py-1 text-gray-400">…</span>
                @endif

                {{-- PAGE TENGAH --}}
                @for ($i = max(2, $current - 1); $i <= min($last - 1, $current + 1); $i++)
                    @if ($i == $current)
                        <span
                            class="px-3 py-1 border border-secondary-blue bg-secondary-blue text-white rounded-lg text-sm">
                            {{ $i }}
                        </span>
                    @else
                        <a href="{{ $paginator->url($i) }}"
                            class="px-3 py-1 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">
                            {{ $i }}
                        </a>
                    @endif
                @endfor

                {{-- TITIK KANAN --}}
                @if ($current < $last - 2)
                    <span class="px-3 py-1 text-gray-400">…</span>
                @endif

                {{-- PAGE TERAKHIR --}}
                @if ($current == $last)
                    <span
                        class="px-3 py-1 border border-secondary-blue bg-secondary-blue text-white rounded-lg text-sm">
                        {{ $last }}
                    </span>
                @else
                    <a href="{{ $paginator->url($last) }}"
                        class="px-3 py-1 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">
                        {{ $last }}
                    </a>
                @endif
            @endif

            {{-- NEXT --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="px-3 py-1 border border-gray-300 rounded-lg text-sm hover:bg-gray-50">
                    Next
                </a>
            @else
                <span class="px-3 py-1 border border-gray-300 rounded-lg text-sm text-gray-400 cursor-not-allowed">
                    Next
                </span>
            @endif

        </nav>
    </div>
@endif

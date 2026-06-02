@props(['items'])

<nav class="flex text-sm text-gray-500" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-2">
        @foreach($items as $item)
            <li class="inline-flex items-center">
                @if(!$loop->first)
                    <svg class="h-4 w-4 text-gray-400 mx-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                @endif

                @if(isset($item['url']) && !$loop->last)
                    <a href="{{ $item['url'] }}" class="hover:text-teal-600 transition-colors">
                        {{ $item['label'] }}
                    </a>
                @else
                    <span class="{{ $loop->last ? 'text-gray-800 font-medium' : '' }}">
                        {{ $item['label'] }}
                    </span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>

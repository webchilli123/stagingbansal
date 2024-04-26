@props(['title', 'url', 'icon', 'color'])

<article class="p-4 mb-4 border rounded">
    <a href="{{ $url }}" class="d-flex align-items-center">
       <span class="fa {{ $icon }} h6 me-2 
       bg-{{ $color }} p-1 rounded text-white"></span>
       <h6 class="ml-2 text-secondary">{{ ucwords($title) }}</h6>
    </a>
 </article>
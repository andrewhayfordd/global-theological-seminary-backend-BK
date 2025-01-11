
<li class="nav-item">
    <a {{ $attributes->merge(["class"=>"nav-link" , "data-toggle"=>"tab", "role"=>"tab"]) }}
        aria-controls="@yield('page-name')" aria-selected="false">{{  $label ?? $slot }}</a>
</li>

{{-- You need to pass an id and href(#(id of the tab content)) and the label for the button --}}
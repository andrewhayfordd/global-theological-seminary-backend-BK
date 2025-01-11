<ul {{ $attributes->merge(["class"=>"nav nav-tabs","id"=>"myTab", "role"=>"tablist"]) }}>
    {{ $slot }}
</ul>
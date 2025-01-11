<div {{  $attributes->merge([ "class"=>"tab-pane fade show mt-3", "role"=>"tabpanel"])}}>
    <div class="card shadow mb-4">
        {{ $slot }}
    </div>
</div>

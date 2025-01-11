<div class="page-header">
    <div class="d-sm-flex align-items-center justify-content-between">
        <h3 class="page-title">@yield('page-name')
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">@yield("page-name")</li>
            </ul>
        </h3>

        {{ $slot ?? null }}
        {{-- <div class="">
            <a href="#" data-toggle="modal" data-target="#add-bill-prog-modal"
                class="btn btn-sm btn-info shadow-sm mx-0"><i class=""></i>Add Programme Bill Item</a>
            <a href="#" data-toggle="modal" data-target="#add-bill-modal"
                class="btn btn-sm btn-info shadow-sm mx-0"><i class=""></i>Add Student Bill Item</a>
            <a href="#" data-toggle="modal" data-target="#add-bill-amount-modal"
                class="btn btn-sm btn-primary shadow-sm mx-0"><i class=""></i>Add Programme Bill</a>
            <a href="#" data-toggle="modal" data-target="#individual-bill-modal"
                class="btn btn-sm btn-primary shadow-sm mx-0"><i class=""></i>Add Individual Bill</a>
        </div> --}}
    </div>

</div>
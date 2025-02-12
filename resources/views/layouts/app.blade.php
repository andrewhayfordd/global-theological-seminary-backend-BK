@extends('layout.app')
@section('page-content')

@yield('content')
<script>
    $('.library-img').each(function(index, element){
       $(element).hover(function(e){
     $(element).children('.overlay').toggle('slow', 'linear');
       })
    });
    
    $('#table_id').DataTable();
    <!-- Add this in your main Blade layout (layouts/app.blade.php) -->

</script>

<!-- Add this in your main Blade layout (layouts/app.blade.php) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@endsection
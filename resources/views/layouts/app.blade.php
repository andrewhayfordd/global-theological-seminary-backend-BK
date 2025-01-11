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

</script>
@endsection
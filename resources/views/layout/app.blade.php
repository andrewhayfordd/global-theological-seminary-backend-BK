

<!DOCTYPE html>
<html lang="en">
<head>
    @include("includes.head")
    @include("includes.styles")
</head>
<body>
    <div class="container-scroller">
        @include("includes.navbar")
        <div class="container-fluid page-body-wrapper">
            @include("includes.sidebar")
           <div class="main-panel">
            
                <div class="content-wrapper">
                     @yield('page-content')
                </div>
              
                
           </div>
        </div>
    </div>
 @include("includes.scripts")
 @stack('scripts')
</body>
</html>


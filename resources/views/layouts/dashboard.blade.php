<!DOCTYPE html>
<html lang="en">

<head>
    @include('includes.head')
    <title>{{ $title ?? 'Dashboard' }} - Smart School</title>
</head>

<body class="bg-gray-100">
        <!-- Sidebar -->
        @include('admin.component.sidebar')

        <main>
            @yield('content')
        </main>
        <!-- Content -->
        

</body>


</html>

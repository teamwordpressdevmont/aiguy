<script src="{{ asset('js/tailwind.js') }}"></script>
<body>
    @include('partials.header') <!-- Include the header here -->
    <div class="mx-auto max-w-7xl">
    <div class="container mx-auto px-4">
        @yield('content')
    </div>
</body>
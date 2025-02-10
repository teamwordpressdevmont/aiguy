<script src="{{ asset('js/tailwind.js') }}"></script>
<body>
    @include('partials.header') <!-- Include the header here -->
    <div class="mx-auto max-w-7xl">
        @yield('content')
    </div>
</body>
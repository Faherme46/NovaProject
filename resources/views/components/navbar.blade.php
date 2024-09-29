<nav class=" bg-primary header d-flex justify-content-between align-items-center px-3 pt-1 " id="header">


    <h2 class="mb-0 text-light ">{{ $asamblea ? $asamblea['folder'] : '-' }}</h2>
    @auth
        <h4 class="mb-0 text-light">
        {{ ucfirst(auth()->user()->name) }} — {{ ucfirst(auth()->user()->getRoleNames()->first()) }}</h4>
        @endauth
    <livewire:quorum-state />

</nav>

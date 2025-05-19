<!-- Header Component -->
<header class="bg-white shadow-sm">
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="/">
                <img src="{{ asset('images/logo.png') }}" alt="Hospital Logo" height="40">
            </a>

            <!-- Mobile Toggle Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigation Links -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/find-doctors">Find Doctors</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/video-consult">Video Consult</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/surgeries">Surgeries</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/medicines">Medicines</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/lab-tests">Lab Tests</a>
                    </li>
                </ul>

                <!-- Search Bar -->
                <div class="search-container mx-3 flex-grow-1" style="max-width: 600px;">
                    <form action="{{ route('search') }}" method="GET" class="d-flex">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-map-marker-alt text-primary"></i>
                            </span>
                            <input type="text"
                                   class="form-control border-start-0"
                                   name="location"
                                   placeholder="Enter location"
                                   value="{{ request('location') }}"
                                   list="locationSuggestions">
                            <datalist id="locationSuggestions">
                                <!-- This will be populated dynamically with JavaScript -->
                            </datalist>

                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-user-md text-primary"></i>
                            </span>
                            <input type="text"
                                   class="form-control border-start-0"
                                   name="search"
                                   placeholder="Search doctors, clinics, hospitals..."
                                   value="{{ request('search') }}"
                                   list="searchSuggestions">
                            <datalist id="searchSuggestions">
                                <!-- This will be populated dynamically with JavaScript -->
                            </datalist>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- User Profile Dropdown -->
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <img src="{{ asset('images/default-avatar.png') }}" alt="User Avatar" class="rounded-circle" width="32" height="32">
                        <span class="ms-2">{{ Auth::user()->name ?? 'Guest' }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="/profile">My Profile</a></li>
                        <li><a class="dropdown-item" href="/appointments">My Appointments</a></li>
                        <li><a class="dropdown-item" href="/medical-records">Medical Records</a></li>
                        <li><hr class="dropdown-divider"></li>
                        @auth
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        @else
                            <li><a class="dropdown-item" href="{{ route('login') }}">Login</a></li>
                            <li><a class="dropdown-item" href="{{ route('register') }}">Register</a></li>
                        @endauth
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const locationInput = document.querySelector('input[name="location"]');
    const searchInput = document.querySelector('input[name="search"]');
    const locationSuggestions = document.getElementById('locationSuggestions');
    const searchSuggestions = document.getElementById('searchSuggestions');

    // Debounce function to limit API calls
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Location suggestions
    locationInput.addEventListener('input', debounce(async function(e) {
        if (e.target.value.length < 2) return;

        try {
            const response = await fetch(`/api/locations/suggest?q=${encodeURIComponent(e.target.value)}`);
            const data = await response.json();

            locationSuggestions.innerHTML = data.map(location =>
                `<option value="${location.name}">`
            ).join('');
        } catch (error) {
            console.error('Error fetching location suggestions:', error);
        }
    }, 300));

    // Search suggestions
    searchInput.addEventListener('input', debounce(async function(e) {
        if (e.target.value.length < 2) return;

        try {
            const response = await fetch(`/api/search/suggest?q=${encodeURIComponent(e.target.value)}`);
            const data = await response.json();

            searchSuggestions.innerHTML = data.map(item =>
                `<option value="${item.name}">`
            ).join('');
        } catch (error) {
            console.error('Error fetching search suggestions:', error);
        }
    }, 300));
});
</script>
@endpush

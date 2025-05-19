<!-- Quick Actions Section -->
<section class="quick-actions py-5">
    <div class="container">
        <h2 class="text-center mb-4">Quick Actions</h2>
        <div class="row g-4">
            <!-- Video Consultation Card -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body text-center p-4">
                        <div class="icon-wrapper mb-3">
                            <i class="fas fa-video fa-3x text-primary"></i>
                        </div>
                        <h3 class="card-title h5">Video Consultation</h3>
                        <p class="card-text text-muted">Connect with top doctors online for instant medical advice</p>
                        <a href="/video-consult" class="btn btn-primary mt-3">
                            Book Now
                            <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Find Doctors Card -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body text-center p-4">
                        <div class="icon-wrapper mb-3">
                            <i class="fas fa-user-md fa-3x text-primary"></i>
                        </div>
                        <h3 class="card-title h5">Find Doctors</h3>
                        <p class="card-text text-muted">Search and book appointments with specialists near you</p>
                        <a href="/find-doctors" class="btn btn-primary mt-3">
                            Search Now
                            <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Surgeries Card -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body text-center p-4">
                        <div class="icon-wrapper mb-3">
                            <i class="fas fa-procedures fa-3x text-primary"></i>
                        </div>
                        <h3 class="card-title h5">Surgeries</h3>
                        <p class="card-text text-muted">Explore surgical procedures and book with expert surgeons</p>
                        <a href="/surgeries" class="btn btn-primary mt-3">
                            Learn More
                            <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.quick-actions {
    background-color: #f8f9fa;
}

.hover-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.icon-wrapper {
    width: 80px;
    height: 80px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(13, 110, 253, 0.1);
    border-radius: 50%;
}

.btn-primary {
    padding: 0.5rem 1.5rem;
    border-radius: 25px;
}

.card {
    border-radius: 15px;
    overflow: hidden;
}

.card-body {
    padding: 2rem;
}
</style>

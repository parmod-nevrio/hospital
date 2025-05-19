<!-- Top Doctors Section -->
<section class="top-doctors py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="mb-3">Consult Top Doctors</h2>
            <p class="text-muted">Get expert medical advice from our specialized doctors</p>
        </div>

        <div class="row g-4">
            <!-- Cardiology -->
            <div class="col-6 col-md-4 col-lg-3">
                <div class="specialty-card text-center">
                    <div class="icon-wrapper mb-3">
                        <i class="fas fa-heartbeat fa-2x text-primary"></i>
                    </div>
                    <h3 class="specialty-title h6">Cardiology</h3>
                    <p class="specialty-doctors text-muted small">15+ Doctors</p>
                    <a href="/specialty/cardiology" class="stretched-link"></a>
                </div>
            </div>

            <!-- Neurology -->
            <div class="col-6 col-md-4 col-lg-3">
                <div class="specialty-card text-center">
                    <div class="icon-wrapper mb-3">
                        <i class="fas fa-brain fa-2x text-primary"></i>
                    </div>
                    <h3 class="specialty-title h6">Neurology</h3>
                    <p class="specialty-doctors text-muted small">12+ Doctors</p>
                    <a href="/specialty/neurology" class="stretched-link"></a>
                </div>
            </div>

            <!-- Orthopedics -->
            <div class="col-6 col-md-4 col-lg-3">
                <div class="specialty-card text-center">
                    <div class="icon-wrapper mb-3">
                        <i class="fas fa-bone fa-2x text-primary"></i>
                    </div>
                    <h3 class="specialty-title h6">Orthopedics</h3>
                    <p class="specialty-doctors text-muted small">18+ Doctors</p>
                    <a href="/specialty/orthopedics" class="stretched-link"></a>
                </div>
            </div>

            <!-- Pediatrics -->
            <div class="col-6 col-md-4 col-lg-3">
                <div class="specialty-card text-center">
                    <div class="icon-wrapper mb-3">
                        <i class="fas fa-baby fa-2x text-primary"></i>
                    </div>
                    <h3 class="specialty-title h6">Pediatrics</h3>
                    <p class="specialty-doctors text-muted small">20+ Doctors</p>
                    <a href="/specialty/pediatrics" class="stretched-link"></a>
                </div>
            </div>

            <!-- Dermatology -->
            <div class="col-6 col-md-4 col-lg-3">
                <div class="specialty-card text-center">
                    <div class="icon-wrapper mb-3">
                        <i class="fas fa-allergies fa-2x text-primary"></i>
                    </div>
                    <h3 class="specialty-title h6">Dermatology</h3>
                    <p class="specialty-doctors text-muted small">14+ Doctors</p>
                    <a href="/specialty/dermatology" class="stretched-link"></a>
                </div>
            </div>

            <!-- Ophthalmology -->
            <div class="col-6 col-md-4 col-lg-3">
                <div class="specialty-card text-center">
                    <div class="icon-wrapper mb-3">
                        <i class="fas fa-eye fa-2x text-primary"></i>
                    </div>
                    <h3 class="specialty-title h6">Ophthalmology</h3>
                    <p class="specialty-doctors text-muted small">10+ Doctors</p>
                    <a href="/specialty/ophthalmology" class="stretched-link"></a>
                </div>
            </div>

            <!-- Dentistry -->
            <div class="col-6 col-md-4 col-lg-3">
                <div class="specialty-card text-center">
                    <div class="icon-wrapper mb-3">
                        <i class="fas fa-tooth fa-2x text-primary"></i>
                    </div>
                    <h3 class="specialty-title h6">Dentistry</h3>
                    <p class="specialty-doctors text-muted small">16+ Doctors</p>
                    <a href="/specialty/dentistry" class="stretched-link"></a>
                </div>
            </div>

            <!-- View All -->
            <div class="col-6 col-md-4 col-lg-3">
                <div class="specialty-card text-center view-all">
                    <div class="icon-wrapper mb-3">
                        <i class="fas fa-ellipsis-h fa-2x text-primary"></i>
                    </div>
                    <h3 class="specialty-title h6">View All</h3>
                    <p class="specialty-doctors text-muted small">50+ Specialties</p>
                    <a href="/specialties" class="stretched-link"></a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.top-doctors {
    background-color: #ffffff;
}

.specialty-card {
    background: #ffffff;
    border-radius: 15px;
    padding: 1.5rem;
    transition: all 0.3s ease;
    position: relative;
    border: 1px solid #e9ecef;
}

.specialty-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    border-color: #0d6efd;
}

.icon-wrapper {
    width: 60px;
    height: 60px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(13, 110, 253, 0.1);
    border-radius: 50%;
    transition: all 0.3s ease;
}

.specialty-card:hover .icon-wrapper {
    background-color: #0d6efd;
}

.specialty-card:hover .icon-wrapper i {
    color: #ffffff !important;
}

.specialty-title {
    margin-bottom: 0.5rem;
    color: #212529;
}

.specialty-doctors {
    margin-bottom: 0;
}

.view-all .icon-wrapper {
    background-color: #f8f9fa;
}

.view-all:hover .icon-wrapper {
    background-color: #0d6efd;
}

@media (max-width: 768px) {
    .specialty-card {
        padding: 1rem;
    }

    .icon-wrapper {
        width: 50px;
        height: 50px;
    }

    .specialty-title {
        font-size: 0.9rem;
    }

    .specialty-doctors {
        font-size: 0.8rem;
    }
}
</style>

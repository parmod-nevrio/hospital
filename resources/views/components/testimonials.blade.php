<!-- Testimonials Section -->
<section class="testimonials py-5 bg-light">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="mb-3">Patient Testimonials</h2>
            <p class="text-muted">What our patients say about their experience</p>
        </div>

        <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
            <!-- Carousel Indicators -->
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="2"></button>
            </div>

            <!-- Carousel Items -->
            <div class="carousel-inner">
                <!-- Testimonial 1 -->
                <div class="carousel-item active">
                    <div class="testimonial-card">
                        <div class="testimonial-content">
                            <div class="rating mb-3">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                            </div>
                            <p class="testimonial-text">
                                "The care and attention I received at this hospital was exceptional. The doctors were knowledgeable and took the time to explain everything clearly. The staff was friendly and made me feel comfortable throughout my stay."
                            </p>
                            <div class="testimonial-author">
                                <img src="{{ asset('images/testimonials/user1.jpg') }}" alt="John Smith" class="author-image">
                                <div class="author-info">
                                    <h4 class="author-name">John Smith</h4>
                                    <p class="author-title">Cardiac Patient</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="carousel-item">
                    <div class="testimonial-card">
                        <div class="testimonial-content">
                            <div class="rating mb-3">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                            </div>
                            <p class="testimonial-text">
                                "I was impressed by the efficiency and professionalism of the entire team. From scheduling my appointment to the follow-up care, everything was handled smoothly. The facilities are modern and well-maintained."
                            </p>
                            <div class="testimonial-author">
                                <img src="{{ asset('images/testimonials/user2.jpg') }}" alt="Sarah Johnson" class="author-image">
                                <div class="author-info">
                                    <h4 class="author-name">Sarah Johnson</h4>
                                    <p class="author-title">Orthopedic Patient</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="carousel-item">
                    <div class="testimonial-card">
                        <div class="testimonial-content">
                            <div class="rating mb-3">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star-half-alt text-warning"></i>
                            </div>
                            <p class="testimonial-text">
                                "The pediatric department is outstanding. They made my child feel comfortable and at ease during the entire treatment. The doctors were patient and explained everything in a way that was easy to understand."
                            </p>
                            <div class="testimonial-author">
                                <img src="{{ asset('images/testimonials/user3.jpg') }}" alt="Maria Garcia" class="author-image">
                                <div class="author-info">
                                    <h4 class="author-name">Maria Garcia</h4>
                                    <p class="author-title">Parent</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carousel Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</section>

<style>
.testimonials {
    background-color: #f8f9fa;
}

.testimonial-card {
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    margin: 2rem auto;
    max-width: 800px;
    padding: 2rem;
}

.testimonial-content {
    text-align: center;
}

.rating {
    font-size: 1.25rem;
}

.rating i {
    margin: 0 0.125rem;
}

.testimonial-text {
    color: #6c757d;
    font-size: 1.1rem;
    line-height: 1.8;
    margin-bottom: 2rem;
    font-style: italic;
}

.testimonial-author {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
}

.author-image {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #0d6efd;
}

.author-info {
    text-align: left;
}

.author-name {
    color: #212529;
    font-size: 1.1rem;
    margin-bottom: 0.25rem;
}

.author-title {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 0;
}

/* Carousel Customization */
.carousel-indicators {
    margin-bottom: 0;
}

.carousel-indicators button {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: #dee2e6;
    margin: 0 5px;
}

.carousel-indicators button.active {
    background-color: #0d6efd;
}

.carousel-control-prev,
.carousel-control-next {
    width: 5%;
    opacity: 1;
}

.carousel-control-prev-icon,
.carousel-control-next-icon {
    background-color: #0d6efd;
    border-radius: 50%;
    padding: 1.5rem;
    background-size: 50%;
}

@media (max-width: 768px) {
    .testimonial-card {
        margin: 1rem;
        padding: 1.5rem;
    }

    .testimonial-text {
        font-size: 1rem;
    }

    .author-image {
        width: 50px;
        height: 50px;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        padding: 1rem;
    }
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize carousel with custom settings
    new bootstrap.Carousel(document.getElementById('testimonialCarousel'), {
        interval: 5000,
        wrap: true,
        keyboard: true,
        pause: 'hover'
    });
});
</script>
@endpush

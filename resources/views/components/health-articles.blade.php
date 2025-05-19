<!-- Health Articles Section -->
<section class="health-articles py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2 class="mb-3">Health Articles</h2>
            <p class="text-muted">Expert insights and latest updates from our medical professionals</p>
        </div>

        <div class="row g-4">
            <!-- Article 1 -->
            <div class="col-md-6 col-lg-4">
                <article class="article-card">
                    <div class="article-image">
                        <img src="{{ asset('images/articles/healthy-diet.jpg') }}" alt="Healthy Diet Tips" class="img-fluid">
                        <div class="article-category">Nutrition</div>
                    </div>
                    <div class="article-content">
                        <div class="article-meta">
                            <span class="author">
                                <img src="{{ asset('images/doctors/dr-smith.jpg') }}" alt="Dr. Smith" class="author-image">
                                Dr. Sarah Smith
                            </span>
                            <span class="reading-time">
                                <i class="far fa-clock"></i> 5 min read
                            </span>
                        </div>
                        <h3 class="article-title">
                            <a href="/articles/healthy-diet-tips">10 Essential Tips for a Balanced Diet in 2024</a>
                        </h3>
                        <p class="article-excerpt">
                            Discover the latest research-backed nutrition advice and practical tips for maintaining a healthy diet in today's fast-paced world.
                        </p>
                        <div class="article-footer">
                            <span class="date">March 15, 2024</span>
                            <a href="/articles/healthy-diet-tips" class="read-more">
                                Read More <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </article>
            </div>

            <!-- Article 2 -->
            <div class="col-md-6 col-lg-4">
                <article class="article-card">
                    <div class="article-image">
                        <img src="{{ asset('images/articles/mental-health.jpg') }}" alt="Mental Health" class="img-fluid">
                        <div class="article-category">Mental Health</div>
                    </div>
                    <div class="article-content">
                        <div class="article-meta">
                            <span class="author">
                                <img src="{{ asset('images/doctors/dr-johnson.jpg') }}" alt="Dr. Johnson" class="author-image">
                                Dr. Michael Johnson
                            </span>
                            <span class="reading-time">
                                <i class="far fa-clock"></i> 7 min read
                            </span>
                        </div>
                        <h3 class="article-title">
                            <a href="/articles/mental-wellness">Understanding and Managing Stress in Modern Life</a>
                        </h3>
                        <p class="article-excerpt">
                            Learn effective strategies for managing stress and maintaining mental wellness in our increasingly complex world.
                        </p>
                        <div class="article-footer">
                            <span class="date">March 12, 2024</span>
                            <a href="/articles/mental-wellness" class="read-more">
                                Read More <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </article>
            </div>

            <!-- Article 3 -->
            <div class="col-md-6 col-lg-4">
                <article class="article-card">
                    <div class="article-image">
                        <img src="{{ asset('images/articles/exercise.jpg') }}" alt="Exercise Tips" class="img-fluid">
                        <div class="article-category">Fitness</div>
                    </div>
                    <div class="article-content">
                        <div class="article-meta">
                            <span class="author">
                                <img src="{{ asset('images/doctors/dr-patel.jpg') }}" alt="Dr. Patel" class="author-image">
                                Dr. Priya Patel
                            </span>
                            <span class="reading-time">
                                <i class="far fa-clock"></i> 6 min read
                            </span>
                        </div>
                        <h3 class="article-title">
                            <a href="/articles/exercise-tips">The Science of Effective Home Workouts</a>
                        </h3>
                        <p class="article-excerpt">
                            Expert advice on creating an effective home workout routine that fits your lifestyle and fitness goals.
                        </p>
                        <div class="article-footer">
                            <span class="date">March 10, 2024</span>
                            <a href="/articles/exercise-tips" class="read-more">
                                Read More <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </article>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="/articles" class="btn btn-outline-primary btn-lg">
                View All Articles
                <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<style>
.health-articles {
    background-color: #ffffff;
}

.article-card {
    background: #ffffff;
    border-radius: 15px;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transition: all 0.3s ease;
    height: 100%;
    overflow: hidden;
}

.article-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.article-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.article-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.article-card:hover .article-image img {
    transform: scale(1.05);
}

.article-category {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: rgba(13, 110, 253, 0.9);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.article-content {
    padding: 1.5rem;
}

.article-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    font-size: 0.9rem;
}

.author {
    display: flex;
    align-items: center;
    color: #6c757d;
}

.author-image {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    margin-right: 0.5rem;
}

.reading-time {
    color: #6c757d;
}

.article-title {
    font-size: 1.25rem;
    margin-bottom: 1rem;
    line-height: 1.4;
}

.article-title a {
    color: #212529;
    text-decoration: none;
    transition: color 0.3s ease;
}

.article-title a:hover {
    color: #0d6efd;
}

.article-excerpt {
    color: #6c757d;
    font-size: 0.95rem;
    margin-bottom: 1rem;
    line-height: 1.6;
}

.article-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #e9ecef;
}

.date {
    color: #6c757d;
    font-size: 0.9rem;
}

.read-more {
    color: #0d6efd;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.9rem;
    transition: color 0.3s ease;
}

.read-more:hover {
    color: #0a58ca;
}

.read-more i {
    margin-left: 0.5rem;
    transition: transform 0.3s ease;
}

.read-more:hover i {
    transform: translateX(5px);
}

.btn-outline-primary {
    padding: 0.75rem 2rem;
    border-radius: 8px;
    font-weight: 500;
}

@media (max-width: 768px) {
    .article-image {
        height: 180px;
    }

    .article-content {
        padding: 1rem;
    }

    .article-title {
        font-size: 1.1rem;
    }

    .article-excerpt {
        font-size: 0.9rem;
    }
}
</style>

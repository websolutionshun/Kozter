<?php

/** @var yii\web\View $this */
/** @var common\models\Post[] $column1Posts */
/** @var common\models\Post[] $column2Posts */
/** @var common\models\Post[] $column3Posts */
/** @var bool $hasMoreColumn1 */
/** @var bool $hasMoreColumn2 */
/** @var bool $hasMoreColumn3 */
/** @var common\models\Post[] $popularPosts */
/** @var array $tagSections */
/** @var int $totalPosts */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'KözTér - Főoldal';
?>

<div class="homepage-3columns">
    <div class="container">

        <!-- ÚJ 3 OSZLOPOS KOZTER-STÍLUSÚ LAYOUT -->
        <div class="row kozter-3col-layout">
            
            <!-- 1. OSZLOP (BAL) - 4 cikk -->
            <div class="col-lg-3 column-1">
                <div class="column-posts" id="column-1-container">
                    <?php foreach ($column1Posts as $index => $post): ?>
                        <article class="column-post mb-4">
                            <?php if ($index === 0): ?>
                                <!-- Első cikk kiemelt formátumban -->
                                <?php if ($post->featuredImage): ?>
                                    <div class="post-image mb-3">
                                        <img src="<?= Html::encode($post->featuredImage->getFileUrl()) ?>" 
                                             alt="<?= Html::encode($post->title) ?>" 
                                     class="img-fluid rounded">
    </div>
                        <?php endif; ?>
                        
                                <div class="post-content">
                                    <?php if (!empty($post->categories)): ?>
                                        <span class="badge bg-primary-soft mb-2"><?= Html::encode($post->categories[0]->name) ?></span>
                                    <?php endif; ?>
                                    
                                    <h3 class="post-title h4 mb-2">
                                        <?= Html::a(Html::encode($post->title), ['/post/view', 'slug' => $post->slug]) ?>
                                    </h3>
                                    
                                    <p class="post-excerpt mb-2"><?= Html::encode($post->getShortContent(140)) ?></p>
                                    
                                    <div class="post-meta-main">
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            <?= date('m.d H:i', $post->published_at) ?>
                                        </small>
                                        <small class="text-muted ms-3">
                                            <i class="fas fa-eye me-1"></i>
                                            <?= number_format($post->view_count) ?>
                                        </small>
                                    </div>
                                </div>
                            <?php else: ?>
                                <!-- Többi cikk kompakt formátumban (kép NINCS a kérés szerint) -->
                                <div class="post-compact d-flex">
                                    <div class="post-info">
                                        <h6 class="post-title-small mb-1">
                                            <?= Html::a(Html::encode($post->title), ['/post/view', 'slug' => $post->slug]) ?>
                                        </h6>
                                        <div class="post-meta-small">
                                            <small class="text-muted"><?= date('m.d H:i', $post->published_at) ?></small>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                </div>
                
                <?php if ($hasMoreColumn1): ?>
                    <div class="text-center">
                        <button class="btn btn-outline-primary btn-sm load-more-btn" 
                                data-column="1" 
                                data-offset="4" 
                                data-limit="4">
                            <i class="fas fa-plus me-1"></i> További cikkek
                        </button>
                    </div>
                <?php endif; ?>
            </div>

            <!-- 2. OSZLOP (KÖZÉP) - 3 cikk, első kiemelt LEAD -->
            <div class="col-lg-6 column-2">
                <div class="column-posts" id="column-2-container">
                    <?php foreach ($column2Posts as $index => $post): ?>
                        <article class="column-post mb-4">
                            <?php if ($index === 0): ?>
                                <!-- VEZÉRCIKK - LEAD formátum -->
                                <div class="lead-article">
                                    <?php if ($post->featuredImage): ?>
                                        <div class="lead-image mb-3">
                                            <img src="<?= Html::encode($post->featuredImage->getFileUrl()) ?>" 
                                                 alt="<?= Html::encode($post->title) ?>" 
                                                 class="img-fluid rounded" style="width: 100%; height: 360px; object-fit: cover;">
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="lead-content">
                                        <?php if (!empty($post->categories)): ?>
                                            <span class="badge bg-danger-soft mb-2">VEZÉRCIKK</span>
                                            <span class="badge bg-secondary-soft mb-2 ms-1"><?= Html::encode($post->categories[0]->name) ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-danger-soft mb-2">VEZÉRCIKK</span>
                            <?php endif; ?>
                            
                                        <h2 class="lead-title h3 mb-3">
                                            <?= Html::a(Html::encode($post->title), ['/post/view', 'slug' => $post->slug]) ?>
                            </h2>
                            
                                        <p class="lead-excerpt mb-3"><?= Html::encode($post->getShortContent(200)) ?></p>
                            
                                        <div class="post-meta-lead">
                                            <small class="text-muted">
                                    <i class="fas fa-user me-1"></i>
                                                <?= Html::encode($post->author->username ?? 'Szerkesztőség') ?>
                                </small>
                                            <small class="text-muted ms-3">
                                    <i class="fas fa-clock me-1"></i>
                                                <?= date('m.d H:i', $post->published_at) ?>
                                </small>
                            </div>
                        </div>
                                </div>
                            <?php else: ?>
                                <!-- Többi cikk normál formátum (kép nélkül) -->
                                <div class="post-content">
                                    <?php if (!empty($post->categories)): ?>
                                        <span class="badge bg-primary-soft mb-2"><?= Html::encode($post->categories[0]->name) ?></span>
                                    <?php endif; ?>
                                    
                                    <h5 class="post-title mb-2">
                                        <?= Html::a(Html::encode($post->title), ['/post/view', 'slug' => $post->slug]) ?>
                                    </h5>
                                    
                                    <p class="post-excerpt mb-2"><?= Html::encode($post->getShortContent(100)) ?></p>
                                    
                                    <div class="post-meta-normal">
                                        <small class="text-muted"><?= date('m.d H:i', $post->published_at) ?></small>
                                    </div>
                                </div>
                            <?php endif; ?>
                            </article>
                        <?php endforeach; ?>
                    </div>
                    
                <?php if ($hasMoreColumn2): ?>
                    <div class="text-center">
                            <button class="btn btn-outline-primary btn-sm load-more-btn" 
                                data-column="2" 
                                data-offset="3" 
                                data-limit="3">
                            <i class="fas fa-plus me-1"></i> További cikkek
                            </button>
                        </div>
                    <?php endif; ?>
                </div>

            <!-- 3. OSZLOP (JOBB) - 4 cikk + kiegészítő tartalmak -->
            <div class="col-lg-3 column-3">
                <div class="column-posts" id="column-3-container">
                    <?php foreach ($column3Posts as $index => $post): ?>
                        <article class="column-post mb-4">
                            <?php if ($index === 0): ?>
                                <!-- Első cikk kiemelt formátumban -->
                                        <?php if ($post->featuredImage): ?>
                                    <div class="post-image mb-3">
                                                <img src="<?= Html::encode($post->featuredImage->getFileUrl()) ?>" 
                                                     alt="<?= Html::encode($post->title) ?>" 
                                                     class="img-fluid rounded" style="width: 100%; height: 220px; object-fit: cover;">
                                            </div>
                                        <?php endif; ?>
                                        
                                <div class="post-content">
                                    <?php if (!empty($post->categories)): ?>
                                        <span class="badge bg-primary-soft mb-2"><?= Html::encode($post->categories[0]->name) ?></span>
                                    <?php endif; ?>
                                    
                                    <h4 class="post-title mb-2">
                                            <?= Html::a(Html::encode($post->title), ['/post/view', 'slug' => $post->slug]) ?>
                                    </h4>
                                    
                                    <p class="post-excerpt mb-2"><?= Html::encode($post->getShortContent(120)) ?></p>
                                    
                                    <div class="post-meta-main">
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            <?= date('m.d H:i', $post->published_at) ?>
                                        </small>
                                        <small class="text-muted ms-3">
                                            <i class="fas fa-eye me-1"></i>
                                            <?= number_format($post->view_count) ?>
                                        </small>
                                    </div>
                                </div>
                            <?php else: ?>
                                <!-- Többi cikk kompakt formátumban (kép nélkül) -->
                                <div class="post-compact d-flex">
                                    <div class="post-info">
                                        <h6 class="post-title-small mb-1">
                                            <?= Html::a(Html::encode($post->title), ['/post/view', 'slug' => $post->slug]) ?>
                                        </h6>
                                        <div class="post-meta-small">
                                            <small class="text-muted"><?= date('m.d H:i', $post->published_at) ?></small>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                                    </article>
                            <?php endforeach; ?>
                        </div>
                        
                <?php if ($hasMoreColumn3): ?>
                    <div class="text-center mb-4">
                        <button class="btn btn-outline-primary btn-sm load-more-btn" 
                                data-column="3" 
                                data-offset="4" 
                                data-limit="4">
                            <i class="fas fa-plus me-1"></i> További cikkek
                                </button>
                            </div>
                        <?php endif; ?>
                        
                <!-- KIEGÉSZÍTŐ TARTALMAK -->
                
                <!-- Legnépszerűbb cikkek blokk -->
                <?php if (!empty($popularPosts)): ?>
                <section class="popular-section mb-4">
                    <h5 class="section-title mb-3">📊 Legnépszerűbb cikkek</h5>
                    <div class="popular-posts">
                        <?php foreach (array_slice($popularPosts, 0, 5) as $index => $post): ?>
                            <article class="popular-item d-flex mb-2">
                                <div class="popular-rank me-2">
                                    <span class="badge bg-warning text-dark"><?= $index + 1 ?></span>
                                </div>
                            <div class="popular-content">
                                    <h6 class="popular-title mb-1">
                                    <?= Html::a(Html::encode($post->title), ['/post/view', 'slug' => $post->slug]) ?>
                                </h6>
                                    <div class="popular-meta">
                                        <small class="text-muted">
                                            <i class="fas fa-eye me-1"></i><?= number_format($post->view_count) ?>
                                        </small>
                                    </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                    </div>
                </section>
                <?php endif; ?>

                <!-- Címkék blokk -->
                <?php if (!empty($tagSections)): ?>
                <section class="tags-section mb-4">
                    <h5 class="section-title mb-3">🏷️ Népszerű témák</h5>
                <?php foreach ($tagSections as $tagSection): ?>
                    <?php $tag = $tagSection['tag']; $posts = $tagSection['posts']; ?>
                        <div class="tag-block mb-3">
                            <h6 class="tag-title">#<?= Html::encode($tag->name) ?></h6>
                            <?php foreach (array_slice($posts, 0, 3) as $post): ?>
                                <article class="tag-item mb-1">
                                <h6 class="tag-post-title">
                                    <?= Html::a(Html::encode($post->title), ['/post/view', 'slug' => $post->slug]) ?>
                                </h6>
                                    <small class="text-muted"><?= date('m.d H:i', $post->published_at) ?></small>
                            </article>
                        <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </section>
                <?php endif; ?>

                <!-- Támogatás blokk -->
                <section class="support-section">
                    <div class="support-card p-3 bg-light rounded">
                        <h6 class="support-title">💝 Támogasd a KözTeret!</h6>
                        <p class="support-text small">Független újságírásunk fenntartásához szükségünk van a támogatásodra.</p>
                        <?= Html::a('Támogatás', ['/site/support'], ['class' => 'btn btn-kozter btn-sm']) ?>
                    </div>
                </section>
            </div>
        </div>

        <!-- Statisztika -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="text-center text-muted">
                    <small>Összesen <?= number_format($totalPosts) ?> cikk található • Frissítve: <?= date('Y.m.d H:i') ?></small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome ikonok -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Új 3 oszlopos Magazin-stílusú CSS -->
<style>
/* 3 OSZLOPOS KOZTER LAYOUT */
.homepage-3columns {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.kozter-3col-layout {
    margin: 0 -15px;
}

.kozter-3col-layout .col-lg-4 {
    padding: 0 15px;
}

/* OSZLOP STÍLUSOK */
.column-posts {
    min-height: 600px;
}

.column-post {
    border-bottom: 1px solid #ffc218;
    padding-bottom: 0.5rem;
}

.column-post:last-child {
    border-bottom: none;
}

/* Bejegyzések közötti térköz csökkentése (Bootstrap mb-4 felülírása) */
.column-post.mb-4 { margin-bottom: 0.75rem !important; }

/* POST CÍMEK STÍLUSAI */
.post-title a {
    color: #212529;
    text-decoration: none;
    font-weight: 600;
    line-height: 1.3;
}

.post-title a:hover {
    color: #007bff;
    text-decoration: none;
}

.post-title-small a {
    color:#000;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.9rem;
    line-height: 1.4;
}

.post-title-small a:hover {
    color: #007bff;
}

/* LEAD ARTIKEL STÍLUSOK */
.lead-article {
    border-radius: 8px;
    margin-bottom: 1rem;
    padding: 1rem;
    background-color: var(--kozter-green);
}

.lead-title a {
    color: #212529;
    text-decoration: none;
    font-weight: 700;
    line-height: 1.2;
}

.lead-excerpt {
    color: #6c757d;
    font-size: 1rem;
    line-height: 1.5;
}

/* BADGE STÍLUSOK - HALVÁNYABB SZÍNEK */
.bg-primary-soft {
    background-color: #cce7ff !important;
    color: #0056b3 !important;
    border: 1px solid #b3d9ff;
}

.bg-danger-soft {
    background-color: #ffe6e6 !important;
    color: #b30000 !important;
    border: 1px solid #ffcccc;
}

.bg-secondary-soft {
    background-color: #f1f3f5 !important;
    color:#000 !important;
    border: 1px solid #dee2e6;
}

/* Badge színek finomítása (kevésbé kontrasztos, olvasható) */
.badge.bg-primary-soft { background-color: #e9f2ff !important; color: #1e40af !important; }
.badge.bg-secondary-soft { background-color: #f5f6f7 !important; color: #334155 !important; }
.badge.bg-danger-soft { background-color: #ffecec !important; color: #7f1d1d !important; }

.badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-weight: 500;
}

/* POST EXCERPT STÍLUSOK */
.post-excerpt {
    color:#000;
    font-size: 0.9rem;
    line-height: 1.4;
}

.lead-excerpt {
    color: #000;
    font-size: 1rem;
    line-height: 1.5;
}

/* POST META STÍLUSOK */
.post-meta-main,
.post-meta-normal,
.post-meta-lead,
.post-meta-small {
    color: #6c757d;
    font-size: 0.8rem;
}

.post-meta-lead {
    font-size: 0.85rem;
}

/* KOMPAKT POST STÍLUSOK */
.post-compact {
    align-items: flex-start;
}

.post-thumb {
    flex-shrink: 0;
}

.post-info {
    flex: 1;
    min-width: 0;
}

/* KIEGÉSZÍTŐ SZEKCIÓK */
.section-title {
    font-weight: 600;
    color: #343a40;
    border-bottom: 2px solid #007bff;
    padding-bottom: 0.5rem;
}

.popular-section,
.tags-section {
    background: var(--kozter-light-blue);
    border-radius: 8px;
    padding: 1rem;
}

.popular-item,
.tag-item {
    padding: 0.5rem 0;
    border-bottom: 1px solid #e9ecef;
}

.popular-item:last-child,
.tag-item:last-child {
    border-bottom: none;
}

.popular-rank .badge {
    font-size: 0.7rem;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

.popular-title a,
.tag-post-title a {
    color: #000;
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 500;
    line-height: 1.3;
}

.popular-title a:hover,
.tag-post-title a:hover {
    color: #007bff;
}

.tag-title {
    color: #007bff;
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

/* TÁMOGATÁS BLOKK */
.support-card {
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.support-card:hover {
    border-color: #007bff;
    box-shadow: 0 2px 8px rgba(0,123,255,0.1);
}

.support-title {
    color: #000;
    font-weight: 600;
}

.support-text {
    color: #6c757d;
    margin-bottom: 1rem;
}

/* GOMBOK */
.load-more-btn {
    border-radius: 20px;
    padding: 0.375rem 1rem;
    font-size: 0.85rem;
    transition: all 0.3s ease;
}

.load-more-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.btn-kozter {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
    border-radius: 4px;
    font-weight: 500;
}

.btn-kozter:hover {
    background-color: #0056b3;
    border-color: #0056b3;
    color: white;
}

/* RESZPONZÍV DESIGN */
@media (max-width: 991px) {
    .kozter-3col-layout .col-lg-4 {
        margin-bottom: 2rem;
    }
    
    .post-thumb {
        width: 60px !important;
    }
    
    .post-thumb img {
        width: 60px !important;
        height: 45px !important;
    }
    
    /* Tablet és mobil sorrend: ha nem 3 oszlopos a layout, a 2. oszlop legyen az első */
    .kozter-3col-layout .column-2 { order: 1; }
    .kozter-3col-layout .column-1 { order: 2; }
    .kozter-3col-layout .column-3 { order: 3; }
}

@media (max-width: 767px) {
    .kozter-3col-layout {
        margin: 0;
    }
    
    .kozter-3col-layout .col-lg-4 {
        padding: 0 10px;
    }
    
    /* Mobil sorrend: 2. oszlop legyen az első */
    .kozter-3col-layout .column-2 { order: 1; }
    .kozter-3col-layout .column-1 { order: 2; }
    .kozter-3col-layout .column-3 { order: 3; }
    
    .post-compact {
        flex-direction: column;
    }
    
    .post-thumb {
        width: 100% !important;
        margin-bottom: 0.5rem;
    }
    
    .post-thumb img {
        width: 100% !important;
        height: auto !important;
    }
}

/* SCROLL OPTIMALIZÁLÁS */
.column-posts {
    transform: translateZ(0);
    will-change: transform;
}

/* KÉPEK OPTIMALIZÁLÁSA */
img {
    transition: opacity 0.3s ease;
}

img.lazy {
    opacity: 0;
}

img:not(.lazy) {
    opacity: 1;
}

/* TELJESÍTMÉNY OPTIMALIZÁLÁS NAGY ADATBÁZISHOZ */
.kozter-3col-layout {
    contain: layout style;
}

.column-post {
    contain: layout;
}
</style>

<!-- AJAX Load More functionality az új oszlopos rendszerhez -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load More gomb eseménykezelő oszlopokhoz
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('load-more-btn') || e.target.closest('.load-more-btn')) {
            e.preventDefault();
            
            const btn = e.target.classList.contains('load-more-btn') ? e.target : e.target.closest('.load-more-btn');
            const column = btn.dataset.column;
            const offset = parseInt(btn.dataset.offset);
            const limit = parseInt(btn.dataset.limit);
            
            // Gomb letiltása a töltés alatt
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Betöltés...';
            
            // AJAX kérés az új oszlopos rendszerhez
            fetch('<?= Url::to(['/ajax/load-more-column-posts']) ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: new URLSearchParams({
                    column: column,
                    offset: offset,
                    limit: limit
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Új tartalom hozzáadása a megfelelő oszlophoz
                    const container = document.getElementById(`column-${column}-container`);
                    
                    if (container) {
                        container.insertAdjacentHTML('beforeend', data.html);
                    }
                    
                    // Offset frissítése
                    btn.dataset.offset = offset + limit;
                    
                    // Gomb visszaállítása vagy elrejtése
                    if (data.hasMore) {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-plus me-1"></i> További cikkek';
                    } else {
                        btn.style.display = 'none';
                    }
                } else {
                    console.error('Hiba a tartalom betöltésekor:', data.message);
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i> Hiba történt';
                }
            })
            .catch(error => {
                console.error('AJAX hiba:', error);
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i> Hiba történt';
            });
        }
    });
    
    // Dinamikus időfrissítés (megtartva)
    function updateRelativeTimes() {
        document.querySelectorAll('[data-timestamp]').forEach(element => {
            const timestamp = parseInt(element.dataset.timestamp);
            const now = Math.floor(Date.now() / 1000);
            const diff = now - timestamp;
            
            let relativeTime;
            if (diff < 60) {
                relativeTime = 'most';
            } else if (diff < 3600) {
                relativeTime = Math.floor(diff / 60) + ' perce';
            } else if (diff < 86400) {
                relativeTime = Math.floor(diff / 3600) + ' órája';
            } else {
                relativeTime = Math.floor(diff / 86400) + ' napja';
            }
            
            element.textContent = relativeTime;
        });
    }
    
    // Időfrissítés minden percben
    setInterval(updateRelativeTimes, 60000);
    
    // Lazy loading képekhez (megtartva)
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                    }
                }
            });
        });
        
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
    
    // Scroll optimalizálás nagy mennyiségű tartalomnál
    let isScrolling = false;
    window.addEventListener('scroll', function() {
        if (!isScrolling) {
            window.requestAnimationFrame(function() {
                // Passzív scroll kezelés nagy adatbázisokhoz
                isScrolling = false;
            });
            isScrolling = true;
        }
    });
});
</script>

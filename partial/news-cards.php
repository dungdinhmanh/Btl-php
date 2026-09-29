<?php
declare(strict_types=1);

/**
 * Server-side news markup, shared by index.php, news.php and news-post.php.
 * Every post comes from NewsRepository (MySQL) — nothing here is hardcoded.
 */

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function newsPostUrl(array $post): string
{
    return 'news-post.php?slug=' . rawurlencode((string) $post['slug']);
}

function newsCover(array $post): string
{
    return $post['coverImage'] ?: 'assets/img/branding/tnc.png';
}

function newsMeta(array $post): string
{
    return '<div class="news-meta"><span>' . e($post['category']) . '</span>'
        . '<time datetime="' . e($post['publishedAtIso']) . '">' . e($post['date']) . '</time></div>';
}

/** Large card (left column). */
function newsFeatureCard(array $post): string
{
    $url = e(newsPostUrl($post));

    return '<article class="news-feature-card h-100">'
        . '<a class="news-image-wrap" href="' . $url . '"><img src="' . e(newsCover($post)) . '" alt="' . e($post['title']) . '" /></a>'
        . '<div class="news-card-body">' . newsMeta($post)
        . '<h3><a href="' . $url . '">' . e($post['title']) . '</a></h3>'
        . '<p>' . e($post['excerpt']) . '</p>'
        . '<a class="news-read-link" href="' . $url . '">Đọc bài viết <i class="bi bi-arrow-right"></i></a>'
        . '</div></article>';
}

/** Small row with thumbnail (right column). */
function newsListItem(array $post): string
{
    $url = e(newsPostUrl($post));

    return '<article class="news-list-item">'
        . '<a class="news-thumb" href="' . $url . '"><img src="' . e(newsCover($post)) . '" alt="' . e($post['title']) . '" /></a>'
        . '<div>' . newsMeta($post) . '<h3><a href="' . $url . '">' . e($post['title']) . '</a></h3></div>'
        . '</article>';
}

/** Card for the 3-column grid. */
function newsGridCard(array $post): string
{
    $url = e(newsPostUrl($post));

    return '<article class="news-grid-card">'
        . '<a href="' . $url . '"><img src="' . e(newsCover($post)) . '" alt="' . e($post['title']) . '" /></a>'
        . '<div class="news-card-body">' . newsMeta($post)
        . '<h3><a href="' . $url . '">' . e($post['title']) . '</a></h3>'
        . '<p>' . e($post['excerpt']) . '</p></div></article>';
}

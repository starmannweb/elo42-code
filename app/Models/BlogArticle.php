<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class BlogArticle extends Model
{
    protected static string $table = 'blog_articles';
    protected static array $fillable = ['title', 'slug', 'summary', 'content', 'cover_image', 'author', 'status', 'published_at'];
}

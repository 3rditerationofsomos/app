<section class="category-block">
    <div class="category-title">
        <h2>
            {$category.type_name}
        </h2>
        <a href="/category/{$category.type_id}/1/by_date" class="btn-all-articles">
            View all
        </a>
    </div>

    <div class="articles-grid">
        {foreach $category.articles as $article}
            {include file="article_preview.tpl" article=$article}
        {/foreach}
    </div>
</section>
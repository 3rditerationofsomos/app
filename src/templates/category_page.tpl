<section class="category-block">
    <div class="category-page-title">
        <h2>
            {$category.type_name}
        </h2>
        <p>
            {$category.type_description}
        </p>
    </div>

    <div class="sort-block">
        <a href="/category/{$category.type_id}/{$category.page}/by_date" class="sort-button">
            Sort by date
        </a>

        <a href="/category/{$category.type_id}/{$category.page}/by_views" class="sort-button">
            Sort by views
        </a>
    </div>

    <div class="articles-grid">
        {foreach $category.articles as $article}
            {include file="article_preview.tpl" article=$article}
        {/foreach}
    </div>

    <div class="pagination-block">
        {if $category.page - 1 > 0}
            <a href="/category/{$category.type_id}/{$category.page - 1}/{$category.sort}" class="arrow-button">
                ←
            </a>
        {/if}
        {$category.page}
        {if $category.page + 1 <= $category.page_count}
            <a href="/category/{$category.type_id}/{$category.page + 1}/{$category.sort}" class="arrow-button">
                →
            </a>
        {/if}
    </div>
</section>
<section class="category-block">
    <div class="category-page-title">
        <h2>
            {$category.type_name}
        </h2>
        <p>
            {$category.type_description}
        </p>
    </div>

    <div class="articles-grid">
        {foreach $category.articles as $article}
            {include file="article_preview.tpl" article=$article}
        {/foreach}
    </div>
</section>
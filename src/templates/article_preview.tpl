<article class="article-preview">

    <a href="/article/{$article.article_id}" class="article-link">

        {if $article.image_link}
            <div class="article-image">
                <img src="{$article.image_link}" alt="{$article.article_name}">
            </div>
        {/if}

        <div class="article-content">
            <h3 class="article-title">{$article.article_name}</h3>

            <div class="article-excerpt">
                {if $article.article_description|strlen > 150}
                    {$article.article_description|truncate:150:"..."}
                {else}
                    {$article.article_description}
                {/if}
            </div>

            <div class="article-meta">
                <time datetime="{$article.created|date_format:'%Y-%m-%d'}">
                    {$article.created|date_format:'%d.%m.%Y'}
                </time>

                {$article.view_count} views
            </div>
        </div>
    </a>
</article>
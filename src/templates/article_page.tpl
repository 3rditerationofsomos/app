{if $article}
    <article class="article-full">

        <h3 class="article-title-full">{$article.name}</h3>

        {if $article.image_link}
            <div class="article-image-full">
                <img src="{$article.image_link}" alt="{$article.name}">
            </div>
        {/if}

        <div class="article-content-full">

            <div class="article-excerpt-full">
                {$article.description}
            </div>

            <div class="article-text">
                {$article.text}
            </div>

            <div class="article-meta">
                <time datetime="{$article.created|date_format:'%Y-%m-%d'}">
                    {$article.created|date_format:'%d.%m.%Y'}
                </time>

                {$article.view_count} views
            </div>
        </div>

        <h3>
            You may also like:
        </h3>
        <div class="similar-grid">
            {foreach $article.similar as $similar}
                {include file="article_preview.tpl" article=$similar}
            {/foreach}
        </div>
    </article>
{else}
    <div>
        No data.
    </div>
{/if}
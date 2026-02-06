<main class="container">
    {foreach $categories as $category}
        {include file="category_block.tpl" category=$category}
    {/foreach}
</main>
{use $model}


{foreach $model->cssLinks as $location}
    <link rel="Stylesheet" type="text/css" href="{$location}" media="screen" />
{/foreach}
{foreach $model->jsLinks as $location}
    <script src="{$location}" type="text/ecmascript"></script>
{/foreach}

<div id="ajax_main">
    <div id="ajax_content">

    </div>
</div>
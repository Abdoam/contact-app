<?php

use Illuminate\Support\Str;

function sortable($label, $column = null)
{
    $column = $column ?? Str::snake($label);
    $sortBy = request()->query('sort_by');
    $direction = "";
    if(ltrim($sortBy, '-') === $column) {
        $direction = strpos($sortBy, '-') === 0 ? "desc" : "asc";
    }
    if (!$sortBy || strpos($sortBy, "-") === 0 && ltrim(request()->query('sort_by'), "-") === $column)
        $sortBy =  $column;
    elseif (request()->query('sort_by') === $column) $sortBy = "-{$column}";
    else $sortBy = $column;
    $url = request()->fullUrlWithQuery(['sort_by' => $sortBy]);
    return "<a href='{$url}' class='sortable {$direction}'>{$label}</a>";
}

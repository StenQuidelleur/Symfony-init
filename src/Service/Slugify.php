<?php


namespace App\Service;


class Slugify
{
    public function generate(string $slug) : string
    {
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $slug);
        $slug = preg_replace('/[^a-z]+/i', '', $slug);
        return strtolower(str_replace(' ', '-', $slug));
    }
}
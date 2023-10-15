<?php

namespace App\Helpers;

use Exception;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class TextProcessor
{

    public static function store(string $title, string $package, string $text = ''): string
    {
        $description =  $text;
        $dom = new \DOMDocument();
        $dom->encoding = 'utf-8';
        $dom->loadHTML(utf8_decode($description), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING);
        $imageFile = $dom->getElementsByTagName('img');

        foreach ($imageFile as $item => $image) {
            $img = $image->getAttribute('src');
            if (filter_var($img, FILTER_VALIDATE_URL) == false) {
                list($type, $img) = explode(';', $img);
                list(, $img) = explode(',', $img);
                $imageData = base64_decode($img);
                $image_name =  Str::slug($title) . '-' . time() . $item . '.png';

                $destinationPath = storage_path() . '/app/public/' . $package . '/text';
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 755, true);
                }

                $path = $destinationPath . '/' . $image_name;
                file_put_contents($path, $imageData);
                $image->removeAttribute('src');
                $image->removeAttribute('data-filename');
                $image->setAttribute('alt', $title);
                $image->setAttribute('style', 'max-width: 100%;');
                $image->setAttribute('src', url('storage/' . $package . '/text/' . $image_name));
            }
        }

        $elements = $dom->getElementsByTagName("pre");

        for ($i = $elements->length - 1; $i >= 0; $i--) {
            $nodePre = $elements->item($i);
            try {
                $nodeDiv = $dom->createElement("code", $nodePre->nodeValue);
                $nodePre->parentNode->replaceChild($nodeDiv, $nodePre);
            } catch (Exception $e) {
                continue;
            }
        }

        $description = $dom->saveHTML();

        if ($description == "<p><br></p>\n") {
            $description = '';
        }

        return $description;
    }

    public static function urlImageTransform($text): string
    {
        $dom = new \DOMDocument();
        $dom->encoding = 'utf-8';
        $dom->loadHTML(utf8_decode($text), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING);
        $imageFile = $dom->getElementsByTagName('img');

        $url = str_replace('www.', '', env('APP_URL'));
        foreach ($imageFile as $item => $image) {
            $img = str_replace([$url, env('APP_URL')], ['', ''], $image->getAttribute('src'));

            try {
                $image->setAttribute('src', 'data:image/svg+xml;base64,' . base64_encode(file_get_contents(public_path($img))));
            } catch (Exception $e) {
                $image->parentNode->removeChild($image);
            }
        }

        $content = $dom->saveHTML();
        return $content;
    }
}

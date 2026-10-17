<?php

namespace App\Console\Commands;

use DOMDocument;
use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate the public sitemap without crawling remote or user-controlled URLs.';

    /**
     * Keep this list limited to known public routes. Adding pages to the CMS does
     * not automatically expose unpublished or church-private URLs in the sitemap.
     */
    private const PUBLIC_ROUTES = [
        'web.home' => '1.0',
        'web.events' => '0.8',
        'web.bulletins' => '0.8',
        'web.contact' => '0.7',
        'web.faq' => '0.7',
        'web.gallery' => '0.7',
        'web.pages' => '0.7',
        'web.posts' => '0.8',
        'web.sermons' => '0.7',
    ];

    public function handle(): int
    {
        file_put_contents(public_path('sitemap.xml'), $this->renderXml());
        $this->info('Sitemap generated at public/sitemap.xml');

        return self::SUCCESS;
    }

    public function renderXml(): string
    {
        $document = new DOMDocument('1.0', 'UTF-8');
        $document->formatOutput = true;
        $urlSet = $document->createElementNS('http://www.sitemaps.org/schemas/sitemap/0.9', 'urlset');
        $document->appendChild($urlSet);

        foreach (self::PUBLIC_ROUTES as $routeName => $priority) {
            $url = $document->createElement('url');
            $location = $document->createElement('loc');
            $location->appendChild($document->createTextNode(route($routeName)));
            $url->appendChild($location);
            $url->appendChild($document->createElement('priority', $priority));
            $urlSet->appendChild($url);
        }

        return $document->saveXML();
    }
}

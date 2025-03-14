<?php

namespace FurisonTech\LaraveditorJS\Utils;

class EmbedServicesRegex
{

    protected array $services = [
        'vimeo' => [
            'source' => '(?:http[s]?:\/\/)?(?:www.)?(?:player.)?vimeo\.co(?:.+\/([^\/]\d+)(?:#t=[\d]+)?s?$)',
            'embed' => 'https:\/\/player\.vimeo\.com\/video\/([^\/\?\&]+)\?title=0&byline=0',
        ],
        'youtube' => [
            'source' => '(?:https?:\/\/)?(?:www\.)?(?:(?:youtu\.be\/)|(?:youtube\.com)\/(?:v\/|u\/\w\/|embed\/|watch))(?:(?:\?v=)?([^#&?=]*))?((?:[?&]\w*=\w*)*)',
            'embed' => 'https:\/\/www\.youtube\.com\/embed\/([^\/\?\&]+)',
        ],
        'coub' => [
            'source' => 'https?:\/\/coub\.com\/view\/([^\/\?\&]+)',
            'embed' => 'https:\/\/coub\.com\/embed\/([^\/\?\&]+)',
        ],
        'vine' => [
            'source' => 'https?:\/\/vine\.co\/v\/([^\/\?\&]+)',
            'embed' => 'https:\/\/vine\.co\/v\/([^\/\?\&]+)\/embed\/simple',
        ],
        'imgur' => [
            'source' => 'https?:\/\/(?:i\.)?imgur\.com.*\/([a-zA-Z0-9]+)(?:\.gifv)?',
            'embed' => 'http:\/\/imgur\.com\/([^\/\?\&]+)',
        ],
        'gfycat' => [
            'source' => 'https?:\/\/gfycat\.com(?:\/detail)?\/([a-zA-Z]+)',
            'embed' => 'https:\/\/gfycat\.com\/ifr\/([^\/\?\&]+)',
        ],
        'twitch-channel' => [
            'source' => 'https?:\/\/www\.twitch\.tv\/([^\/\?\&]*)\/?$',
            'embed' => 'https:\/\/player\.twitch\.tv\/\?channel=([^\/\?\&]+)',
        ],
        'twitch-video' => [
            'source' => 'https?:\/\/www\.twitch\.tv\/(?:[^\/\?\&]*\/v|videos)\/([0-9]*)',
            'embed' => 'https:\/\/player\.twitch\.tv\/\?video=v([^\/\?\&]+)',
        ],
        'yandex-music-album' => [
            'source' => 'https?:\/\/music\.yandex\.ru\/album\/([0-9]*)\/?$',
            'embed' => 'https:\/\/music\.yandex\.ru\/iframe\/#album\/([^\/\?\&]+)',
        ],
        'yandex-music-track' => [
            'source' => 'https?:\/\/music\.yandex\.ru\/album\/([0-9]*)\/track\/([^\/\?\&]*)',
            'embed' => 'https:\/\/music\.yandex\.ru\/iframe\/#track\/([^\/\?\&]+)',
        ],
        'yandex-music-playlist' => [
            'source' => 'https?:\/\/music\.yandex\.ru\/users\/([^\/\?\&]*)\/playlists\/([0-9]*)',
            'embed' => 'https:\/\/music\.yandex\.ru\/iframe\/#playlist\/([^\/\?\&]+)',
        ],
        'codepen' => [
            'source' => 'https?:\/\/codepen\.io\/([^\/\?\&]*)\/pen\/([^\/\?\&]*)',
            'embed' => 'https:\/\/codepen\.io\/([^\/\?\&]+)\?height=300&theme-id=0&default-tab=css,result&embed-version=2',
        ],
        'instagram' => [
            'source' => '^https:\/\/(?:www\.)?instagram\.com\/(?:reel|p)\/(.*)',
            'embed' => 'https:\/\/www\.instagram\.com\/p\/([^\/\?\&]+)',
        ],
        'twitter' => [
            'source' => '^https?:\/\/(www\.)?(?:twitter\.com|x\.com)\/.+\/status\/(\d+)',
            'embed' => 'https:\/\/platform\.twitter\.com\/embed\/Tweet\.html\?id=([^\/\?\&]+)',
        ],
        'pinterest' => [
            'source' => 'https?:\/\/([^\/\?\&]*).pinterest.com\/pin\/([^\/\?\&]*)\/?',
            'embed' => 'https:\/\/assets\.pinterest\.com\/ext\/embed\.html\?id=([^\/\?\&]+)',
        ],
        'facebook' => [
            'source' => 'https?:\/\/www.facebook.com\/([^\/\?\&]*)\/(.*)',
            'embed' => 'https:\/\/www\.facebook\.com\/plugins\/post\.php\?href=https:\/\/www\.facebook\.com\/([^\/\?\&]+)&width=500',
        ],
        'aparat' => [
            'source' => '(?:http[s]?:\/\/)?(?:www.)?aparat\.com\/v\/([^\/\?\&]+)\/?',
            'embed' => 'https:\/\/www\.aparat\.com\/video\/video\/embed\/videohash\/([^\/\?\&]+)',
        ],
        'miro' => [
            'source' => 'https:\/\/miro.com\/\S+(\S{12})\/(\S+)?',
            'embed' => 'https:\/\/miro.com\/app\/live-embed\/([^\/\?\&]+)',
        ],
        'github' => [
            'source' => 'https?:\/\/gist.github.com\/([^\/\?\&]*)\/([^\/\?\&]*)',
            'embed' => 'https:\/\/gist.github.com\/([^\/\?\&]*)',
        ],
    ];


    public function getRegexRulesForServices(array $allowedServices = []): array
    {
        if (empty($allowedServices)) {
            return $this->services;
        }
        return array_intersect_key($this->services, array_flip($allowedServices));
    }

    public function putCustomService(string $serviceName, string $sourceRegex, string $embedRegex): void
    {
        $this->services[$serviceName] = [
            'source' => $sourceRegex,
            'embed' => $embedRegex,
        ];
    }

}
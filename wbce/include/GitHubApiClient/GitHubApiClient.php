<?php

namespace Neoflow;

use RuntimeException;

class GitHubApiClient
{
    /**
     * Default options
     *
     * @var array
     */
    protected $options = [
        'api' => [
            'baseUrl' => 'https://api.github.com',
        ],
        'cache' => [
            'lifetime' => 300, // 300 seconds (5 minutes)
            'directory' => null,
        ],
        'curl' => [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ACCEPT_ENCODING => 'application/vnd.github.v3+json'
        ],
    ];

    /**
     * Constructor.
     *
     * @param string $identifier GitHub username, or the name of your application/project
     * @param array $options Custom options
     */
    public function __construct(string $identifier, array $options = [])
    {
        $this->options['cache']['directory'] = sys_get_temp_dir();
        $this->options['response']['contentCallback'] = function (string $content) {
            return json_decode($content, true);
        };

        $this->options['curl'][CURLOPT_USERAGENT] = $identifier;

        $this->options = array_replace_recursive($this->options, $options);
    }

    /**
     * Send GET request to GitHub API.
     *
     * @param string $urlPath GitHub API path without base URL
     *
     * @return array
     */
    public function get(string $urlPath): array
    {
        $url = $this->options['api']['baseUrl'] . $urlPath;

        return $this->send($url);
    }

    /**
     * Send HTTP request.
     *
     * @param string $url Request url
     *
     * @return array
     *
     * @throws RuntimeException
     */
    protected function send(string $url): array
    {
        $cache = $this->getCache($url);

        if (!is_array($cache)) {
            $this->options['curl'][CURLOPT_URL] = $url;

            $ch = curl_init();

            curl_setopt_array($ch, $this->options['curl']);

            $content = curl_exec($ch);

            if (false === $content) {
                throw new RuntimeException('Sending request to GitHub API failed. ' . curl_error($ch));
            }

            $response = [
                'header' => curl_getinfo($ch),
                'content' => json_decode($content, true)
            ];

            curl_close($ch);

            $this->setCache($url, $response);

            return $response;
        }

        return $cache;
    }

    /**
     * Create cache file name.
     *
     * @param string $url Requested URL
     *
     * @return string
     */
    protected function createCacheFileName(string $url): string
    {
        return $this->options['cache']['directory'] . DIRECTORY_SEPARATOR . 'github-api-client-' . hash('crc32', $url) . '.json';
    }

    /**
     * Set cache of response content.
     *
     * @param string $url Requested URL
     * @param array $response Response
     */
    protected function setCache(string $url, array $response): void
    {
        $cacheFilename = $this->createCacheFileName($url);
        file_put_contents($cacheFilename, json_encode($response));
    }

    /**
     * Get cache of response content.
     *
     * @param string $url Requested URL
     *
     * @return array|null
     */
    protected function getCache(string $url): ?array
    {
        $cacheFilename = $this->createCacheFileName($url);
        if (is_file($cacheFilename)) {
            if (filemtime($cacheFilename) > (time() - $this->options['cache']['lifetime'])) {
                return json_decode(file_get_contents($cacheFilename), true);
            }
            unlink($cacheFilename);
        }

        return null;
    }
}
<?php

namespace Neoflow;

use RuntimeException;

class GitHubApiClient
{
    /**
     * @var string
     */
    protected $repoPath;

    /**
     * @var int
     */
    protected $options = [
        'apiUrl' => 'https://api.github.com',
        'cache' => true,
        'cacheLifetime' => 300, // 300 seconds (5 minutes)
        'cacheDirectory' => null,
        'curl' => [],
    ];

    /**
     * Default cURL options.
     *
     * @var array
     */
    protected $curlOptions = [
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_FOLLOWLOCATION => 1,
    ];

    /**
     * Constructor.
     *
     * @param array $options Client options
     */
    public function __construct($options = [])
    {
        $this->options['cacheDirectory'] = sys_get_temp_dir();

        $this->options = array_merge($this->options, $options);

        if (isset($options['curl'])) {
            $this->curlOptions = $this->curlOptions += $options['curl'];
        }
    }

    /**
     * Call GitHub API.
     *
     * @param string $urlPath Additional API url path
     * @param array $curlOptions Additional cURL options
     * @param bool $cache Set FALSE to prevent to cache the response content
     *
     * @return string
     */
    public function call($urlPath = '', $curlOptions = [], $cache = true)
    {
        return $this->send($this->options['apiUrl'] . $urlPath, $curlOptions, $cache);
    }

    /**
     * Build and send HTTP request.
     *
     * @param string $url Request url
     * @param array $curlOptions Additional cURL options
     * @param bool $cache Set FALSE to prevent to cache the response content
     *
     * @return string
     *
     * @throws RuntimeException
     */
    public function send($url = '', $curlOptions = [], $cache = true)
    {
        if (!$cache || !$this->options['cache'] || !$this->isCached($url)) {
            // Get cURL resource
            $ch = curl_init();

            // Set destination url
            $this->curlOptions[CURLOPT_URL] = $url;

            // Merge and set cURL options
            curl_setopt_array($ch, $this->options['curl'] + $this->curlOptions + $curlOptions);

            // Send cURL request and get response and HTTP code
            $response = curl_exec($ch);

            // Check whether request was successful
            if (false === $response) {
                throw new RuntimeException('Connection error. ' . curl_error($ch));
            }

            // Close cURL request to clear up resources
            curl_close($ch);

            // Set cache
            if ($cache) {
                $this->setCache($url, $response);
            }

            return $response;
        } else {
            return $this->getCache($url);
        }
    }

    /**
     * Create cache filename based on API url.
     *
     * @param string $url GitHub API url
     *
     * @return string
     */
    protected function createCacheFilename($url)
    {
        return $this->options['cacheDirectory'] . DIRECTORY_SEPARATOR . 'githubapiclient-' . hash('crc32', $url) . '.json';
    }

    /**
     * Cache response based on API url.
     *
     * @param string $url GitHub API url
     * @param string $data GitHub API response
     *
     * @return self
     */
    protected function setCache($url, $data)
    {
        $cacheFilename = $this->createCacheFilename($url);
        file_put_contents($cacheFilename, $data);

        return $this;
    }

    /**
     * Get cache based on API url.
     *
     * @param string $url GitHub API url
     *
     * @return string
     */
    protected function getCache($url)
    {
        $cacheFilename = $this->createCacheFilename($url);
        if (is_file($cacheFilename)) {
            if (filemtime($cacheFilename) > (time() - $this->options['cacheLifetime'])) {
                return file_get_contents($cacheFilename);
            } else {
                unlink($cacheFilename);
            }
        }

        return '';
    }

    /**
     * Check whether a cache based on API url exists.
     *
     * @param string $url GitHub API url
     *
     * @return bool
     */
    protected function isCached($url)
    {
        return (bool)$this->getCache($url);
    }
}

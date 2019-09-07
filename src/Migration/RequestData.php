<?php
/**
 * This file is part of GrrSf application
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 6/09/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Migration;


use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RequestData
{
    /**
     * @var HttpClientInterface
     */
    private $httpClient;
    /**
     * @var string
     */
    private $base_url;

    private function setBaseUrl(string $url)
    {
        $url = rtrim($url, '/');
        $this->base_url = $url.'/migration/';
    }

    public function connect(string $url, string $user, string $password)
    {
        $this->setBaseUrl($url);
        $options = [
            'auth_basic' => [$user, $password],
            'http_version' => '1.1',
        ];
        $this->httpClient = HttpClient::create($options);
    }

    /**
     * @return \Symfony\Contracts\HttpClient\ResponseInterface
     *
     */
    public function getEntries()
    {
        return $this->request('entry.php');
    }

    /**
     * @param string $file
     * @return false|string|\Symfony\Contracts\HttpClient\ResponseInterface
     */
    private function request(string $file)
    {
        try {
            return $this->httpClient->request('GET', $this->base_url.$file);
        } catch (TransportExceptionInterface $e) {
            return json_encode(['error' => $e->getMessage()]);
        }
    }


}
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
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
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
        $this->base_url = $url.DIRECTORY_SEPARATOR.'migration'.DIRECTORY_SEPARATOR;
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
    public function getEntries(?string $date)
    {
        return $this->request('entry.php');
    }

    public function getAreas()
    {
        return $this->request('area.php');
    }

    public function getRooms()
    {
        return $this->request('room.php');
    }

    public function getUsers()
    {
        return $this->request('user.php');
    }

    public function getTypesEntry() {
        return $this->request('type.php');
    }

    /**
     * @param string $file
     * @return false|string|\Symfony\Contracts\HttpClient\ResponseInterface
     */
    private function request(string $file)
    {
        try {
            $response = $this->httpClient->request('GET', $this->base_url.$file);
            try {
                return $response->getContent();
            } catch (ClientExceptionInterface $e) {
                return json_encode(['error' => $e->getMessage()]);
            } catch (RedirectionExceptionInterface $e) {
                return json_encode(['error' => $e->getMessage()]);
            } catch (ServerExceptionInterface $e) {
                return json_encode(['error' => $e->getMessage()]);
            } catch (TransportExceptionInterface $e) {
                return json_encode(['error' => $e->getMessage()]);
            }
        } catch (TransportExceptionInterface $e) {
            return json_encode(['error' => $e->getMessage()]);
        }

    }


}
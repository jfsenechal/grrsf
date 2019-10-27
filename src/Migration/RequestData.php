<?php
/**
 * This file is part of GrrSf application.
 *
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 6/09/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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

    private function setBaseUrl(string $url): void
    {
        $url = rtrim($url, '/');
        $this->base_url = $url.DIRECTORY_SEPARATOR.'migration'.DIRECTORY_SEPARATOR;
    }

    public function connect(string $url, string $user, string $password): void
    {
        $this->setBaseUrl($url);
        $options = [
            'auth_basic' => [$user, $password],
            'http_version' => '1.1',
        ];
        $this->httpClient = HttpClient::create($options);
    }

    /**
     * @return bool|string
     */
    public function getEntries(array $params = [])
    {
        return $this->request('entry.php', $params);
    }

    /**
     * @return bool|string
     */
    public function getAreas()
    {
        return $this->request('area.php');
    }

    /**
     * @return bool|string
     */
    public function getRooms()
    {
        return $this->request('room.php');
    }

    /**
     * @return bool|string
     */
    public function getUsers()
    {
        return $this->request('user.php');
    }

    /**
     * @return bool|string
     */
    public function getTypesEntry()
    {
        return $this->request('type.php');
    }

    /**
     * @return bool|string
     */
    public function getAreaAdmin()
    {
        return $this->request('user_admin_area.php');
    }

    /**
     * @return bool|string
     */
    public function getRoomAdmin()
    {
        return $this->request('user_room.php');
    }

    public function download(string $url, array $params = []): void
    {
        $jsonfile = str_replace('php', 'json', $url);

        if (is_readable(MigrationUtil::FOLDER_CACHE.$jsonfile)) {
            return;
        }

        $args = [
            'buffer' => false,
        ];

        if (count($params) > 0) {
            $args['query'] = $params;
        }

        $response = $this->httpClient->request('GET', $this->base_url.$url, $args);

        if (200 !== $response->getStatusCode()) {
            throw new \Exception('...');
        }

        $fileHandler = fopen(MigrationUtil::FOLDER_CACHE.$jsonfile, 'w');
        foreach ($this->httpClient->stream($response) as $chunk) {
            fwrite($fileHandler, $chunk->getContent());
        }
        fclose($fileHandler);
    }

    /**
     * @param string $file
     * @param array $params
     * @return false|string
     */
    private function request(string $file, array $params = [])
    {
        // don't want to buffer the response in memory
        $args = [
            'buffer' => false,
        ];

        if (count($params) > 0) {
            $args['query'] = $params;
        }

        try {
            $response = $this->httpClient->request('GET', $this->base_url.$file, $args);
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

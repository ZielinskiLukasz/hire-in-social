<?php

declare(strict_types=1);

/*
 * This file is part of the Hire in Social project.
 *
 * (c) Norbert Orzechowicz <norbert@orzechowicz.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HireInSocial\Infrastructure\Facebook;

use Facebook\Exceptions\FacebookSDKException;
use Facebook\FacebookResponse;
use HireInSocial\Application\Exception\Exception;
use HireInSocial\Application\Facebook\Draft;
use HireInSocial\Application\Facebook\Facebook;
use HireInSocial\Application\Facebook\Group;
use HireInSocial\Application\Facebook\Page;
use Psr\Log\LoggerInterface;

final class FacebookGraphSDK implements Facebook
{
    private $facebook;
    private $logger;

    public function __construct(\Facebook\Facebook $facebook, LoggerInterface $logger)
    {
        $this->facebook = $facebook;
        $this->logger = $logger;
    }

    public function postToGroupAsPage(Draft $post, Group $group, Page $page): string
    {
        try {
            $response = $this->post(sprintf('/%s/feed', $group->fbId()), [
                'message' => (string)$post,
                'link' => $post->link(),
            ], $page->accessToken());

            return $response->getDecodedBody()['id'];
        } catch (FacebookSDKException $e) {
            throw new Exception('Can\'t post facebook job offer', $e);
        }
    }

    private function get(string $url, string $accessToken = null) : FacebookResponse
    {
        $this->logger->debug('Facebook SDK pre GET request', [
            'appId' => $this->facebook->getApp()->getId(),
            'app_secret' => mb_substr($this->facebook->getApp()->getSecret(), 0, 4) . '############',
            'url' => $url,
            'graph_version' => $this->facebook->getDefaultGraphVersion(),
            'access_token' => $accessToken,
        ]);

        try {
            $response = $this->facebook->get($url, $accessToken ?: $this->facebook->getApp()->getAccessToken());

            $this->logger->debug('Facebook SDK post GET request', [
                'appId' => $this->facebook->getApp()->getId(),
                'app_secret' => mb_substr($this->facebook->getApp()->getSecret(), 0, 4) . '############',
                'url' => $url,
                'graph_version' => $this->facebook->getDefaultGraphVersion(),
                'response' => $response->getBody(),
            ]);

            return $response;
        } catch (\Throwable $exception) {
            $this->logException($url, $accessToken ?: (string) $this->facebook->getApp()->getAccessToken(), $exception);

            throw $exception;
        }
    }

    private function post(string $url, array $parameters, string $accessToken = null) : FacebookResponse
    {
        $this->logger->debug('Facebook SDK pre POST request', [
            'appId' => $this->facebook->getApp()->getId(),
            'app_secret' => mb_substr($this->facebook->getApp()->getSecret(), 0, 4) . '############',
            'url' => $url,
            'parameters' => $parameters,
            'graph_version' => $this->facebook->getDefaultGraphVersion(),
            'access_token' => $accessToken,
        ]);

        try {
            $response = $this->facebook->post($url, $parameters, $accessToken ?: $this->facebook->getApp()->getAccessToken());

            $this->logger->debug('Facebook SDK post POST request', [
                'appId' => $this->facebook->getApp()->getId(),
                'app_secret' => mb_substr($this->facebook->getApp()->getSecret(), 0, 4) . '############',
                'url' => $url,
                'parameters' => $parameters,
                'graph_version' => $this->facebook->getDefaultGraphVersion(),
                'response' => $response->getBody(),
            ]);

            return $response;
        } catch (\Throwable $exception) {
            $this->logException($url, $accessToken ?: (string) $this->facebook->getApp()->getAccessToken(), $exception);

            throw $exception;
        }
    }

    private function delete(string $url, array $parameters, string $accessToken = null) : FacebookResponse
    {
        $this->logger->debug('Facebook SDK pre DELETE request', [
            'appId' => $this->facebook->getApp()->getId(),
            'app_secret' => mb_substr($this->facebook->getApp()->getSecret(), 0, 4) . '############',
            'url' => $url,
            'parameters' => $parameters,
            'graph_version' => $this->facebook->getDefaultGraphVersion(),
            'access_token' => $accessToken,
        ]);

        try {
            $response = $this->facebook->delete($url, $parameters, $accessToken ?: $this->facebook->getApp()->getAccessToken());

            $this->logger->debug('Facebook SDK post DELETE request', [
                'appId' => $this->facebook->getApp()->getId(),
                'app_secret' => mb_substr($this->facebook->getApp()->getSecret(), 0, 4) . '############',
                'url' => $url,
                'parameters' => $parameters,
                'graph_version' => $this->facebook->getDefaultGraphVersion(),
                'response' => $response->getBody(),
            ]);

            return $response;
        } catch (\Throwable $exception) {
            $this->logException($url, $accessToken ?: (string) $this->facebook->getApp()->getAccessToken(), $exception);

            throw $exception;
        }
    }

    private function logException(string $url, string $accessToken, \Throwable $exception): void
    {
        $this->logger->error('Facebook SDK exception', [
            'appId' => $this->facebook->getApp()->getId(),
            'app_secret' => mb_substr($this->facebook->getApp()->getSecret(), 0, 4) . '############',
            'url' => $url,
            'graph_version' => $this->facebook->getDefaultGraphVersion(),
            'access_token' => $accessToken,
            'exception' => \get_class($exception),
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
        ]);
    }
}

<?php

namespace Hanwoolderink88\ApiForm\Src;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestService
{
    /**
     * @var Request|null
     */
    private ?Request $request;

    /**
     * @var mixed[]|resource|null
     */
    private $body = null;

    /**
     * UserRepository constructor.
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed|null
     */
    public function get(string $key, $default = null)
    {
        return $this->request->get($key, $default);
    }

    /**
     * @param string $key
     * @param null $default
     * @return mixed|null
     */
    public function getBody(string $key, $default = null)
    {
        if ($this->body === null) {
            $body = $this->request->getContent();
            if (is_string($body)) {
                $this->body = (array)json_decode($body, true);
            } else {
                $this->body = $body;
            }
        }

        return $this->body[$key] ?? $default;
    }

    /**
     * @param string $key
     * @param null $default
     * @return bool|float|int|string|null
     */
    public function getQuery(string $key, $default = null)
    {
        return $this->request->query->get($key, $default);
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return (int)$this->request->query->get('page', '1');
    }

    /**
     * @return int
     * @throws ApiFormBadRequestException
     */
    public function getLimit(): int
    {
        $max = 100;
        $limit = (int)$this->request->query->get('limit', '20');

        if ($max < $limit) {
            throw new ApiFormBadRequestException("Limit exceeds maximum of {$max}");
        }

        if ($limit <= 0) {
            throw new ApiFormBadRequestException("Limit cannot be zero or less");
        }

        return (int)min($max, $limit);
    }
}

<?php

namespace Vendor\HetznerCloud\Managers;

use GuzzleHttp\Promise\PromiseInterface;
use Vendor\HetznerCloud\Http\Client\HetznerClient;

abstract class AbstractManager
{
    protected HetznerClient $client;

    protected array $filters = [];

    protected ?string $sort = null;

    protected ?int $perPage = null;

    protected ?int $page = null;

    public function __construct(HetznerClient $client)
    {
        $this->client = $client;
    }

    /**
     * Set filters for the request.
     *
     * @return $this
     */
    public function filter(array $filters): self
    {
        $this->filters = array_merge($this->filters, $filters);

        return $this;
    }

    /**
     * Set sort criteria.
     *
     * @return $this
     */
    public function sort(string $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Set per page limit.
     *
     * @return $this
     */
    public function perPage(int $perPage): self
    {
        $this->perPage = $perPage;

        return $this;
    }

    /**
     * Set specific page.
     *
     * @return $this
     */
    public function page(int $page): self
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Configure next request to run asynchronously.
     *
     * @return $this
     */
    public function async(bool $async = true): self
    {
        $this->client->setAsync($async);

        return $this;
    }

    /**
     * Build parameters for GET requests.
     */
    protected function buildQueryParams(): array
    {
        $params = $this->filters;

        if ($this->sort !== null) {
            $params['sort'] = $this->sort;
        }

        if ($this->perPage !== null) {
            $params['per_page'] = $this->perPage;
        }

        if ($this->page !== null) {
            $params['page'] = $this->page;
        }

        $this->resetQuery();

        return $params;
    }

    /**
     * Reset the query builder state.
     */
    protected function resetQuery(): void
    {
        $this->filters = [];
        $this->sort = null;
        $this->perPage = null;
        $this->page = null;
    }

    /**
     * Perform a GET request.
     *
     * @return mixed
     */
    protected function getRequest(string $uri, array $query = [])
    {
        return $this->client->request('GET', $uri, ['query' => $query]);
    }

    /**
     * Perform a POST request.
     *
     * @return mixed
     */
    protected function postRequest(string $uri, array $data = [])
    {
        $options = [];
        if (! empty($data)) {
            $options['json'] = $data;
        }

        return $this->client->request('POST', $uri, $options);
    }

    /**
     * Perform a PUT request.
     *
     * @return mixed
     */
    protected function putRequest(string $uri, array $data = [])
    {
        $options = [];
        if (! empty($data)) {
            $options['json'] = $data;
        }

        return $this->client->request('PUT', $uri, $options);
    }

    /**
     * Perform a DELETE request.
     *
     * @return mixed
     */
    protected function deleteRequest(string $uri, array $data = [])
    {
        $options = [];
        if (! empty($data)) {
            $options['json'] = $data;
        }

        return $this->client->request('DELETE', $uri, $options);
    }

    /**
     * Helper to wrap a promise mapping response data to DTOs or collections.
     *
     * @param  mixed  $result  The return value of request() which can be Guzzle Promise or decoded array
     * @return mixed PromiseInterface or hydrated DTO/Collection
     */
    protected function hydrate($result, callable $hydrator)
    {
        if ($result instanceof PromiseInterface) {
            return $result->then($hydrator);
        }

        return $hydrator($result);
    }
}

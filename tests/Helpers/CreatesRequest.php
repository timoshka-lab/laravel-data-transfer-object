<?php

namespace TimoshkaLab\DataTransferObject\Tests\Helpers;

use Illuminate\Http\Request;

trait CreatesRequest
{
    /**
     * @param string $uri
     * @return void
     */
    protected function setUpRequest(string $uri): void
    {
        $request = Request::create($uri);
        $this->instance('request', $request);
    }
}
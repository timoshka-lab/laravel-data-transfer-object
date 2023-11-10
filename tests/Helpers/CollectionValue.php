<?php

namespace TimoshkaLab\DataTransferObject\Tests\Helpers;

use Illuminate\Support\Collection;

final class CollectionValue
{
    /**
     * @var Collection
     */
    private Collection $collection;

    /**
     * @param Collection $collection
     */
    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * @return Collection
     */
    public function getCollection(): Collection
    {
        return $this->collection;
    }
}
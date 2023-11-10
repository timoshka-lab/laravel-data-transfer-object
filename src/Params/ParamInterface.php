<?php

namespace TimoshkaLab\DataTransferObject\Params;

interface ParamInterface
{
    /**
     * @param array $input
     * @return mixed
     */
    public function build(array $input): mixed;
}

<?php

namespace App\Http\Controllers\Api;

use App\Model\Contribution;
use App\Http\Requests\CreateContributionRequest;
use App\Http\Controllers\ApiController;


class ContributionController extends ApiController
{
    public function store(CreateContributionRequest $request)
    {
        $input = $request->all();
        Contribution::create($input);
        return $this->responseJson();
    }
}

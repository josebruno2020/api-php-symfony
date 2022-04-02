<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\Request;

class RequestDataExtract
{
    private function searchRequestData(Request $request): array
    {
        $orderInformation = $request->query->get('sort', []);
        $queryString = $request->query->all();
        unset($queryString['sort']);
        $page = array_key_exists('page', $queryString) ?
            $queryString['page'] :
            1;

        unset($queryString['page']);

        $itemsPerPage = array_key_exists('itemsPerPage', $queryString) ?
            $queryString['itemsPerPage'] :
            2;
        unset($queryString['itemsPerPage']);


        return [$orderInformation, $queryString, $page, $itemsPerPage];
    }

    public function searchOrderData(Request $request): array
    {
       list($orderInformation) = $this->searchRequestData($request);
       return $orderInformation;
    }

    public function searchFilterData(Request $request): array
    {
        [, $queryString] = $this->searchRequestData($request);

        return $queryString;
    }

    public function searchPageData(Request $request): array
    {
        [, , $page, $itemsPerPage] =  $this->searchRequestData($request);

        return [$page, $itemsPerPage];
    }
}
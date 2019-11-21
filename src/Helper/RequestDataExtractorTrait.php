<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\Request;

/**
 * Trait RequestDataExtractor
 * @package App\Helper
 */
trait RequestDataExtractorTrait
{
    /**
     * @param Request $request
     * @return array
     */
    public function getSort(Request $request): array
    {
        $sort = $request->query->get('sort');
        return is_null($sort)? [] : $sort;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getFilter(Request $request): array
    {
        $filter = $request->query->get('filter');
        return is_null($filter) ? [] : $filter;
    }

    /**
     * @param Request $request
     * @return int
     */
    public function getPage(Request $request): int
    {
        $page = $request->query->get('page');
        return is_null($page) ? 1 : intval($page);
    }

    /**
     * @param Request $request
     * @return int
     */
    public function getItensByPage(Request $request): int
    {
        $itens = $request->query->get('itens');
        return is_null($itens) ? 10 : intval($itens);
    }
}

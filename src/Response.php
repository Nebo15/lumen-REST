<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 04.03.16
 * Time: 11:13
 */

namespace Nebo15\REST;

use Illuminate\Http\Response as LumenResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class Response extends LumenResponse
{
    public function json($content = [], $code = self::HTTP_OK, $meta = [], $paginate = [], $sandboxData = [])
    {
        $meta['code'] = $code;
        $respond = [
            'meta' => $meta,
            'data' => $content,
        ];
        if (!empty($paginate)) {
            $respond['paging'] = $paginate;
        }
        if (!empty($sandboxData)) {
            $respond['sandbox'] = $sandboxData;
        }
        return $this->setStatusCode($code)->setContent($respond);
    }

    public function jsonPaginator(LengthAwarePaginator $paginator, array $meta = [], callable $map = null)
    {
        $collection = $paginator->getCollection();
        $content = $map ? $collection->map($map) : $collection->toArray();

        return $this->json($content, 200, $meta, [
            'size' => $paginator->perPage(),
            'total' => $paginator->total(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
        ]);
    }
}

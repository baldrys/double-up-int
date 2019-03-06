<?php

namespace App\Support;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CollectionUtils
{

    /**
     * Получаем пары из $data только элементы с ключями $keys
     *
     * @param  mixed $keys
     * @param  mixed $data
     *
     * @return array
     */
    public static function parseData($data, $keys)
    {
        return collect($data)->map(function ($item) use ($keys) {
            return collect($item)
                ->only($keys)
                ->all();
        })->all();
    }

    /**
     * Замена ключей в массиве
     *
     * @param  array $item
     * @param  mixed $oldKey
     * @param  mixed $newKey
     *
     * @return void
     */
    public static function replaceKey(array &$item, $oldKey, $newKey)
    {
        $item[$newKey] = $item[$oldKey];
        unset($item[$oldKey]);
    }

    /**
     * renameKeyInArray
     *
     * @param  mixed $old
     * @param  mixed $new
     * @param  mixed $array
     *
     * @return void
     */
    public static function renameKeysInData($old, $new, $data)
    {
        return collect($data)->transform(function ($item) use ($old, $new) {
            CollectionUtils::replaceKey($item, $old, $new);
            return $item;
        })->all();
    }

    /**
     * Добавляет элемент $itemToAdd ко всем элементам в $data
     *
     * @param  mixed $itemToAdd
     * @param  mixed $data
     *
     * @return array
     */
    public static function addItemToData($itemToAdd, $data)
    {
        return collect($data)->map(function ($item) use ($itemToAdd) {
            return collect($item)
                ->merge($itemToAdd)
                ->all();
        })->all();
    }

    /**
     * I solved the problem with the "page>2" which add numeric key to the object.
     * Gera a paginação dos itens de um array ou collection.
     * @param array|Collection $items
     * @param int $perPage
     * @param int $page
     * @param array $options
     *
     * @return LengthAwarePaginator
     */
    public static function paginateWithoutKey($items, $perPage = 15, $page = null, $options = [])
    {

        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ? $items : Collection::make($items);

        $lap = new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);

        return [
            'current_page' => $lap->currentPage(),
            'data' => $lap->values(),
            'first_page_url' => $lap->url(1),
            'from' => $lap->firstItem(),
            'last_page' => $lap->lastPage(),
            'last_page_url' => $lap->url($lap->lastPage()),
            'next_page_url' => $lap->nextPageUrl(),
            'per_page' => $lap->perPage(),
            'prev_page_url' => $lap->previousPageUrl(),
            'to' => $lap->lastItem(),
            'total' => $lap->total(),
        ];
    }
}

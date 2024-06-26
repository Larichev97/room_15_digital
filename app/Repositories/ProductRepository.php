<?php

namespace App\Repositories;

use App\Models\Product as Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

final class ProductRepository extends CoreRepository
{
    /**
     *  Список полей, у которых поиск в значениях выполняется по "DATE(field_name) = ..."
     *
     *  [Override]
     *
     * @var array|string[]
     */
    protected array $searchDateFieldsArray = ['created_at', 'updated_at',];

    /**
     *  Список полей, у которых поиск в значениях выполняется по "field_name LIKE %...%"
     *
     *  [Override]
     *
     * @var array|string[]
     */
    protected array $searchLikeFieldsArray = ['title',];

    /**
     * App\Models\Product
     *
     * @return string
     */
    protected function getModelClass(): string
    {
        return Model::class;
    }

    /**
     *  [Override]
     *
     * @param int|null $perPage
     * @param int $page
     * @param string $orderBy
     * @param string $orderWay
     * @param array $filterFieldsData
     * @return LengthAwarePaginator
     */
    public function getAllWithPaginate(int|null $perPage, int $page, string $orderBy = 'id', string $orderWay = 'desc', array $filterFieldsData = []): LengthAwarePaginator
    {
        return parent::getAllWithPaginate($perPage, $page, $orderBy, $orderWay, $filterFieldsData);
    }

    /**
     *  [Override]
     *
     * @param string $fieldId
     * @param string $fieldName
     * @param bool $useCache
     * @return Collection
     */
    public function getForDropdownList(string $fieldId, string $fieldName, bool $useCache = true): Collection
    {
        return parent::getForDropdownList($fieldId, $fieldName, $useCache);
    }

    /**
     *  Список полей с названиями, которые необходимо отобразить в списке (route "{group_name}.index")
     *
     *  [Override]
     *
     * @return array|string[]
     */
    public function getDisplayedFieldsOnIndexPage(): array
    {
        return [
            'id'            => ['field' => 'id', 'field_input_type' => self::INPUT_TYPE_TEXT, 'field_title' => '#'],
            'title'         => ['field' => 'title', 'field_input_type' => self::INPUT_TYPE_TEXT, 'field_title' => 'Назва'],
            'price'         => ['field' => 'price', 'field_input_type' => self::INPUT_TYPE_TEXT, 'field_title' => 'Ціна'],
            'currency_id'   => ['field' => 'currency_id', 'field_input_type' => self::INPUT_TYPE_SELECT, 'field_title' => 'Валюта'],
        ];
    }
}

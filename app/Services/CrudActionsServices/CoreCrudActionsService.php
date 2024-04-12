<?php

namespace App\Services\CrudActionsServices;

use App\DataTransferObjects\FormFieldsDtoInterface;
use App\Repositories\CoreRepository;

abstract class CoreCrudActionsService
{
    abstract public function processStore(FormFieldsDtoInterface $dto): bool;
    abstract public function processUpdate(FormFieldsDtoInterface $dto, CoreRepository $repository): bool;
    abstract public function processDestroy(int $id, CoreRepository $repository): bool;
}

<?php

namespace App\Services\CrudActionsServices;

use App\DataTransferObjects\FormFieldsDtoInterface;
use App\Repositories\CoreRepository;
use Illuminate\Database\Eloquent\Model;

abstract class CoreCrudActionsApiService
{
    abstract public function processStore(FormFieldsDtoInterface $dto): Model|false;
    abstract public function processUpdate(FormFieldsDtoInterface $dto, CoreRepository $repository): Model|false;
    abstract public function processDestroy(int $id, CoreRepository $repository): bool;
}

<?php

namespace Dmpty\LaravelUtilities\Common\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Dmpty\LaravelUtilities\Common\Models\DynamicModel;
use Dmpty\LaravelUtilities\Error\Exceptions\LogTableNotExist;

/**
 * @mixin Builder
 */
class SafeQuery
{
    private $tableService;

    public $model;

    public $query;

    private $force;

    private $endMethod = [
        'get',
        'first',
        'create',
    ];

    public function __construct(DynamicModel $model, bool $force = false)
    {
        $this->model = $model;
        $this->tableService = $model->tableService;
        $this->query = $model->newQuery();
        $this->force = $force;
    }

    /**
     * @throws LogTableNotExist
     */
    private function __execute($method, $arguments): static
    {
        try {
            if (in_array($method, $this->endMethod)) {
                return $this->query->$method(...$arguments);
            }
            $this->query = $this->query->$method(...$arguments);
            return $this;
        } catch (QueryException $exception) {
            if ($exception->getCode() === '42S02') {
                if (!$this->force) {
                    throw new LogTableNotExist();
                }
                $this->tableService::create($this->model->getTable());
                return $this->__execute($method, $arguments);
            }
            throw $exception;
        }
    }

    /**
     * @throws LogTableNotExist
     */
    public function __call($method, $arguments)
    {
        return $this->__execute($method, $arguments);
    }
}

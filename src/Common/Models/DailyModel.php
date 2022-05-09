<?php

namespace Dmpty\LaravelUtilities\Common\Models;

use Carbon\Carbon;

class DailyModel extends DynamicModel
{
    protected $connection = 'logs';

    public function __construct(array $attributes = [], $date = '')
    {
        $date = $date ?: Carbon::now()->format('Y_m_d');
        parent::__construct($attributes, $date);
    }

    public function setUpdatedAt($value): DailyModel|static
    {
        return $this;
    }

    public function getUpdatedAtColumn()
    {
        return null;
    }
}

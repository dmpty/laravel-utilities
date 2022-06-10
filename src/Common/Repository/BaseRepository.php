<?php

namespace Dmpty\LaravelUtilities\Common\Repository;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Dmpty\LaravelUtilities\Common\Models\DynamicModel;
use Dmpty\LaravelUtilities\Common\Services\SafeQuery;
use Dmpty\LaravelUtilities\Error\Exceptions\LogTableNotExist;

abstract class BaseRepository
{
    protected const DATE_RANGE_TYPE_DAY = 0;
    protected const DATE_RANGE_TYPE_MONTH = 1;

    /** @var DynamicModel */
    protected $dynamicModel;

    /**
     * @param Carbon $start
     * @param Carbon $end
     * @param array $where
     * @param int $type
     * @param string $prefix
     * @return Collection
     */
    public function getDataByRange(
        Carbon $start,
        Carbon $end,
        array $where = [],
        int $type = 0,
        string $prefix = ''
    ): Collection {
        $suffixFormat = $this->getSuffixFormat($type);
        $suffixStart = $prefix . $start->format($suffixFormat);
        $suffixEnd = $prefix . $end->format($suffixFormat);
        if ($suffixStart === $suffixEnd) {
            $query = $this->getSafeQuery($suffixStart, $where);
            $data = $this->getCollectionByRange($query, $start, $end);
        } else {
            $data = new Collection();
            $suffixes = $this->getSuffixesByRange($start, $end, $type);
            $query = $this->getSafeQuery($suffixStart, $where);
            $data->push($this->getCollectionByRange($query, $start));
            foreach ($suffixes as $suffix) {
                $suffix = $prefix . $suffix;
                $query = $this->getSafeQuery($suffix, $where);
                $data->push($this->getCollectionByRange($query));
            }
            $query = $this->getSafeQuery($suffixEnd, $where);
            $data->push($this->getCollectionByRange($query, null, $end));
            $data = $data->collapse();
        }
        return $data;
    }

    /**
     * @param string $suffix
     * @param array $where
     * @return SafeQuery
     */
    private function getSafeQuery(string $suffix, array $where = []): SafeQuery
    {
        $query = $this->dynamicModel::safeQuery($suffix, false);
        if ($where) {
            $query->where($where);
        }
        return $query;
    }

    /**
     * @param SafeQuery $query
     * @param Carbon|null $start
     * @param Carbon|null $end
     * @return Collection
     */
    protected function getCollectionByRange(SafeQuery $query, Carbon $start = null, Carbon $end = null): Collection
    {
        try {
            if ($start) {
                $query->where('created_at', '>', $start);
            }
            if ($end) {
                $query->where('created_at', '<', $end);
            }
            return $query->get();
        } /** @noinspection PhpRedundantCatchClauseInspection,PhpUnusedLocalVariableInspection */ catch (LogTableNotExist $e) {
            return new Collection();
        }
    }

    /**
     * @param Carbon $start
     * @param Carbon $end
     * @param int $type
     * @return array
     */
    protected function getSuffixesByRange(Carbon $start, Carbon $end, int $type = 0): array
    {
        $suffixFormat = $this->getSuffixFormat($type);
        $suffixes = [];
        $suffixEnd = $end->format($suffixFormat);
        $date = $this->addPeriod($start->clone(), $type);
        $suffix = $date->format($suffixFormat);
        while ($suffix !== $suffixEnd) {
            $suffixes[] = $suffix;
            $date = $this->addPeriod($date, $type);
            $suffix = $date->format($suffixFormat);
        }
        return $suffixes;
    }

    /**
     * @param int $type
     * @return string
     */
    private function getSuffixFormat(int $type): string
    {
        $formats = [
            self::DATE_RANGE_TYPE_DAY => 'Y_m_d',
            self::DATE_RANGE_TYPE_MONTH => 'Y_m',
        ];
        return $formats[$type];
    }

    /**
     * @param Carbon $date
     * @param int $type
     * @return Carbon
     */
    private function addPeriod(Carbon $date, int $type = 0): Carbon
    {
        switch ($type) {
            case self::DATE_RANGE_TYPE_DAY:
                $date->addDay();
                break;
            case self::DATE_RANGE_TYPE_MONTH:
                $date->addMonth();
                break;
            default:
                break;
        }
        return $date;
    }
}

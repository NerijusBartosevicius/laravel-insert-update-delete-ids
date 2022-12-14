<?php

namespace Neriba\IudIds;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class IudIdsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Builder::macro(
            'insertGetIds',
            function (array $values, $returning = ['id']) {
                $this->isSupportReturning();

                if (empty($values) || empty($returning)) {
                    return [];
                }

                if (!is_array(reset($values))) {
                    $values = [$values];
                } else {
                    foreach ($values as $key => $value) {
                        ksort($value);

                        $values[$key] = $value;
                    }
                }

                $this->applyBeforeQueryCallbacks();

                return $this->connection->select(
                    $this->grammar->compileInsert($this, $values).' returning '.implode(',', $returning),
                    $this->cleanBindings(Arr::flatten($values, 1))
                );
            }
        );

        Builder::macro(
            'updateGetIds',
            function (array $values, $returning = ['id']) {
                $this->isSupportReturning();

                if (empty($values) || empty($returning)) {
                    return [];
                }

                $this->applyBeforeQueryCallbacks();

                return $this->connection->select(
                    $this->grammar->compileUpdate($this, $values).' returning '.implode(',', $returning),
                    $this->cleanBindings($this->grammar->prepareBindingsForUpdate($this->bindings, $values))
                );
            }
        );

        Builder::macro(
            'deleteGetIds',
            function ($id = null, $returning = ['id']) {
                $this->isSupportReturning();

                if (empty($returning)) {
                    return [];
                }

                if (!is_null($id)) {
                    $this->where($this->from.'.id', '=', $id);
                }

                $this->applyBeforeQueryCallbacks();

                return $this->connection->select(
                    $this->grammar->compileDelete($this).' returning '.implode(',', $returning),
                    $this->cleanBindings($this->grammar->prepareBindingsForDelete($this->bindings))
                );
            }
        );

        Builder::macro(
            'isSupportReturning',
            function () {
                $pdoAttributes = $this->connection->getPdo();

                if (!str_contains($pdoAttributes->getAttribute(\PDO::ATTR_SERVER_VERSION), 'MariaDB')
                    && !in_array($pdoAttributes->getAttribute(\PDO::ATTR_DRIVER_NAME), ['pgsql', 'sqlite'])) {
                    throw new \Exception('Your database does not support returning functionality.');
                }
            }
        );
    }

}

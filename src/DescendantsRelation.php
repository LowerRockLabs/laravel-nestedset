<?php

namespace Kalnoy\Nestedset;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;

class DescendantsRelation extends BaseRelation
{

    /**
     * Set the base constraints on the relation query.
     *
     * @return void
     */
    public function addConstraints(): void
    {
        if ( ! static::$constraints) return;

        $this->query->whereDescendantOf($this->parent)
        ->applyNestedSetScope();
    }

    /**
     * @param QueryBuilder $query
     * @param Model $model
     */
    protected function addEagerConstraint($query, $model)
    {
        $query->orWhereDescendantOf($model);
    }

    /**
     * @param Model $model
     * @param $related
     *
     * @return mixed
     */
    protected function matches(Model $model, $related)
    {
        return $related->isDescendantOf($model);
    }

    /**
     * @param mixed $hash
     * @param mixed $table
     * @param int $lft
     * @param int $rgt
     *
     * @return string
     */
    protected function relationExistenceCondition($hash, $table, int $lft, int $rgt): string
    {
        return "{$hash}.{$lft} between {$table}.{$lft} + 1 and {$table}.{$rgt}";
    }
}
<?php

namespace App\Models;

use App\Exceptions\NullBookmarkException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * A general purpose bookmark class since the lib doesn't provide this for whatever reason
 */
abstract class ModelBookmark extends Model
{
    protected $table = 'markable_bookmarks';

    protected $bookmarkModel = null;

    protected static function booted(): void
    {
        parent::booted();

        $model = (new static)->bookmarkModel;
        if (! $model) {
            throw NullBookmarkException::forBookmark((new static()));
        }

        static::addGlobalScope(new class($model) implements Scope
        {
            public function __construct(public $modelName)
            {
            }

            public function apply(Builder $builder, Model $model)
            {
                $builder->where('markable_type', '=', $this->modelName)
                    ->orderBy('id', 'ASC');
            }
        });
    }

    public function scopeWhereUser($builder, User $user)
    {
        $builder->where('user_id', '=', $user->id);
    }

    public function markable()
    {
        return $this->morphTo();
    }
}

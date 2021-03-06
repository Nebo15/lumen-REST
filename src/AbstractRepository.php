<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 04.03.16
 * Time: 11:14
 */

namespace Nebo15\REST;

use \MongoDB\BSON\ObjectID;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Nebo15\REST\Exceptions\RepositoryException;
use Nebo15\LumenApplicationable\ApplicationableHelper;
use Nebo15\LumenApplicationable\Contracts\Applicationable;

abstract class AbstractRepository
{
    protected $modelClassName;

    /** @var Model $model */
    private $model;

    public function __construct()
    {
        if (!$this->modelClassName) {
            throw new RepositoryException("You should set \$modelClassName in " . get_called_class());
        }
        if (!class_exists($this->modelClassName)) {
            throw new RepositoryException("Model " . $this->modelClassName . " not found");
        }
        $this->model = new $this->modelClassName;
        if (!($this->model instanceof Model)) {
            throw new RepositoryException(
                "Model $this->modelClassName should be instance of Illuminate\\Database\\Eloquent\\Model"
            );
        }
    }

    public function getModel()
    {
        return $this->model;
    }

    public function findByIds(array $ids)
    {
        array_walk($ids, function (&$item) {
            if (!($item instanceof ObjectID)) {
                $item = new ObjectId($item);
            }
        });

        $model = $this->getModel();
        $query = $model->query();
        if ($model instanceof Applicationable) {
            $query = $query->where(['applications' => ['$in' => [ApplicationableHelper::getApplicationId()]]]);
        }

        return $query->where($this->getModel()->getKeyName(), ['$in' => $ids])->get();
    }

    /**
     * @param $id
     * @return Model
     */
    public function read($id)
    {
        $model = $this->getModel();
        $query = $model->query();
        if ($model instanceof Applicationable) {
            $query = $query->where(['applications' => ['$in' => [ApplicationableHelper::getApplicationId()]]]);
        }
        return $query->where($this->getModel()->getKeyName(), $id)->firstOrFail();
    }

    /**
     * @param null $size
     * @return \Illuminate\Pagination\LengthAwarePaginator
     * @throws \Exception
     */
    public function readList($size = null)
    {
        $model = $this->getModel();
        $query = $model->query();
        if ($model instanceof Applicationable) {
            $query = $query->where(['applications' => ['$in' => [ApplicationableHelper::getApplicationId()]]]);
        }

        return $query->paginate(intval($size));
    }

    public function createOrUpdate($values, $id = null)
    {
        $model = $id ? $this->read($id) : $this->model->newInstance();
        if ($model instanceof Applicationable) {
            ApplicationableHelper::addApplication($model);
        }
        $model->fill($values)->save();

        return $model;
    }

    public function copy($id)
    {
        $model = $this->read($id);
        $values = $model->getAttributes();
        unset($values[$model->getKeyName()]);

        return $this->createOrUpdate($values);
    }

    public function delete($id)
    {
        return $this->read($id)->delete();
    }

    public function paginateQuery(Builder $query, $size)
    {
        $total = $query->toBase()->getCountForPagination();

        $page = Paginator::resolveCurrentPage('page');
        $perPage = intval($size) ?: $this->getModel()->getPerPage();
        $items = $query->skip($page * $perPage - $perPage)->take($perPage)->get();

        return new LengthAwarePaginator($items, $total, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);
    }
}

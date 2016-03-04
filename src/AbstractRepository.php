<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 04.03.16
 * Time: 11:14
 */

namespace Nebo15\REST;

use Illuminate\Database\Eloquent\Model;
use Nebo15\REST\Exceptions\RepositoryException;

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

    /**
     * @param $id
     * @return Model
     */
    public function read($id)
    {
        return call_user_func_array([$this->modelClassName, 'findById'], [$id]);
    }

    /**
     * @param null $size
     * @return \Illuminate\Pagination\LengthAwarePaginator
     * @throws \Exception
     */
    public function readList($size = null)
    {
        return call_user_func_array([$this->modelClassName, 'paginate'], [intval($size)]);
    }

    public function createOrUpdate($values, $id = null)
    {
        $model = $id ? $this->read($id) : $this->model->newInstance();
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
}

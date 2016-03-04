<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 04.03.16
 * Time: 11:14
 */

namespace REST;

use Illuminate\Database\Eloquent\Model;
use REST\Exceptions\RepositoryException;

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
        $values = $this->read($id)->getAttributes();
        unset($values[call_user_func([$this->modelClassName, 'getKeyName'])]);

        return $this->createOrUpdate($values);
    }

    public function delete($id)
    {
        return $this->read($id)->delete();
    }
}

<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 02.03.16
 * Time: 13:37
 */

namespace Nebo15\REST;

use Illuminate\Http\Request;
use Nebo15\REST\Exceptions\ControllerException;
use Nebo15\REST\Interfaces\ListableInterface;
use Nebo15\REST\Traits\ValidatesRequestsTrait;

abstract class AbstractController extends \Laravel\Lumen\Routing\Controller
{
    use ValidatesRequestsTrait;

    private $repository;

    protected $request;

    protected $response;

    protected $repositoryClassName;

    protected $validationRules = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    protected function getRepository()
    {
        if (!$this->repository) {
            if (!$this->repositoryClassName) {
                throw new ControllerException("You should set \$repositoryClassName");
            }
            $this->repository = new $this->repositoryClassName;
            if (!($this->repository instanceof AbstractRepository)) {
                throw new ControllerException("Repository $this->repositoryClassName should be instance of Nebo15\REST\\Repository");
            }
        }

        return $this->repository;
    }

    public function create()
    {
        $this->validateRoute();

        return $this->response->json(
            $this->getRepository()->createOrUpdate($this->request->all())->toArray(),
            Response::HTTP_CREATED
        );
    }

    public function copy($id)
    {
        return $this->response->json(
            $this->getRepository()->copy($id)->toArray()
        );
    }

    public function read($id)
    {
        return $this->response->json($this->getRepository()->read($id)->toArray());
    }

    public function readList()
    {
        return $this->response->jsonPaginator(
            $this->getRepository()->readList($this->request->input('size')),
            [],
            function (ListableInterface $model) {
                return $model->toListArray();
            }
        );
    }

    public function update($id)
    {
        $this->validateRoute();

        return $this->response->json(
            $this->getRepository()->createOrUpdate($this->request->request->all(), $id)->toArray()
        );
    }

    public function delete($id)
    {
        return $this->response->json(
            $this->getRepository()->delete($id)
        );
    }

    public function validateRoute()
    {
        $action = debug_backtrace()[1]['function'];
        if (isset($this->validationRules[$action])) {
            $this->validate($this->request, $this->validationRules[$action]);
        }
    }
}

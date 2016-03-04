<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 02.03.16
 * Time: 13:37
 */

namespace REST;

use Illuminate\Http\Request;
use REST\Exceptions\ControllerException;
use REST\Interfaces\ListableInterface;
use REST\Traits\ValidatesRequestsTrait;

abstract class AbstractController extends \Laravel\Lumen\Routing\Controller
{
    use ValidatesRequestsTrait;

    private $request;
    private $response;
    private $repository;

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
                throw new ControllerException("Repository $this->repositoryClassName should be instance of REST\\Repository");
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

    public function readList(Request $request)
    {
        return $this->response->jsonPaginator(
            $this->getRepository()->readList($request->input('size')),
            [],
            function (ListableInterface $model) {
                return $model->toListArray();
            }
        );
    }

    public function update(Request $request, $id)
    {
        $this->validateRoute();

        return $this->response->json(
            $this->getRepository()->createOrUpdate($request->request->all(), $id)->toArray()
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

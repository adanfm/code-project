<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Repositories\ClientRepository;
use CodeProject\Services\ClienteService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ClientController extends Controller
{
    /**
     * @var ClientRepository
     */
    private $repository;

    /**
     * @var ClienteService
     */
    private $service;

    /**
     * ClientController constructor.
     * @param ClientRepository $repository
     */
    public function __construct(ClientRepository $repository,ClienteService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->repository->all();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->service->create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return $this->repository->find($id);
        } catch (ModelNotFoundException $e) {
            return [
                'error' => true,
                'message'=> 'Client not Found'
            ];
        }

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return $this->service->update($request->all(),$id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->repository->delete($id);
            return [
                'status' => 'success',
                'message' => 'Client removed'
            ];
        } catch (ModelNotFoundException $e) {
            return [
                'error' => true,
                'message'=> 'Client not Found'
            ];
        } catch (QueryException $e) {
            return  [
                'error'    =>  true,
                'message'   =>  'This client has related projects'
            ];
        }
    }
}

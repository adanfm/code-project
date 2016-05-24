<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Repositories\ProjectRepository;
use CodeProject\Services\ProjectService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use CodeProject\Http\Requests;
use CodeProject\Http\Controllers\Controller;

class ProjectController extends Controller
{
    /**
     * @var ProjectRepository
     */
    private $repository;

    /**
     * @var ProjectService
     */
    private $service;

    public function __construct(ProjectRepository $repository,ProjectService $service)
    {
        $this->repository = $repository;
        $this->service    = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->repository->with([
            'client',
            'owner'
        ])->all();
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
                'message'=> 'Project not Found'
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
                'message' => 'Project removed'
            ];
        } catch (ModelNotFoundException $e) {
            return [
                'error' => true,
                'message'=> 'Project not Found'
            ];
        } catch (QueryException $e) {
            return  [
                'error'    =>  true,
                'message'   =>  'This project has related project notes'
            ];
        }
    }
}

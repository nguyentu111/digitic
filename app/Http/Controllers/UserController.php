<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\ISuccessCollectionResponse;
use App\Repositories\Interfaces\ISuccessEntityResponse;
use App\Repositories\Interfaces\IUserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userRepo;

    protected $successCollectionResponse;

    protected $successEntityResponse;

    public function __construct(IUserRepository $userRepository, ISuccessCollectionResponse $successCollectionResponse, ISuccessEntityResponse $successEntityResponse) {
        $this->userRepo = $userRepository;
        $this->successCollectionResponse = $successCollectionResponse;
        $this->successEntityResponse = $successEntityResponse;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rs = $this->userRepo->paginate(10);
        return $rs;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $request->validate( [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'provider' => 'required',
            'provider_id' => 'required',
            'access_token' => 'required',
            'session_token' => 'required',
        ]);
        $rs = $this->userRepo->create($request->all());
        return $this->successEntityResponse->createResponse($rs);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = $this->userRepo->getById($id);
        if(!$user) throw new ModelNotFoundException('User not found for id='.$id.'.');
        return $this->successEntityResponse->createResponse($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $validator = $request->validate( [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'provider' => 'required',
            'provider_id' => 'required',
            'access_token' => 'required',
            'session_token' => 'required',
        ]);
        $user = $this->userRepo->getById($id);
        if(!$user) throw new ModelNotFoundException('User not found for id='.$id.'.');
        $user->update($request->all());
        return $this->successEntityResponse->createResponse($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = $this->userRepo->getById($id);
        // if (!$user) {
        //     return response()->json(['error' =>'User not found for id='.$id.'.'], 404);
        // }
        if(!$user) return throw new ModelNotFoundException('Color not found for id='.$id.'.');

        $user->delete();
        return response()->json(['result'=>'ok']);
    }
}

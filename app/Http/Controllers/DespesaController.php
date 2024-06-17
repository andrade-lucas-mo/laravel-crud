<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DespesaRequests\StoreDespesaRequest;
use App\Http\Requests\DespesaRequests\UpdateDespesaRequest;
use App\Http\Resources\DespesaResource;
use App\Models\Despesa;
use App\Notifications\DespesaNotification;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;

class DespesaController extends Controller
{
    use HttpResponses;

    public function __construct()
    {
        $this->authorizeResource(Despesa::class, 'despesa');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Despesa = Despesa::where('user_id', Auth::id())->with('user')->get();
        if($Despesa->isEmpty()){
            return $this->error('Not Found', 404, ['despesa' => 'Não foi encontrada nenhuma despesa']);
        }
        return $this->response('Success', 200, DespesaResource::collection($Despesa));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDespesaRequest $request)
    {
        $despesa = new Despesa($request->validated());
        $despesa->user_id = Auth::id();
        $despesa->save();
        $user = Auth::user();
        $user->notify(new DespesaNotification($despesa));
        return $this->response('Despesa criada', 201, new DespesaResource($despesa->load('user')));
    }

    /**
     * Display the specified resource.
     */
    public function show(Despesa $despesa)
    {
        return $this->response('Success', 200, new DespesaResource($despesa));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDespesaRequest $request, Despesa $despesa)
    {
        $despesa->update($request->validated());
        return $this->response('Despesa atualizada', 200, new DespesaResource($despesa->load('user')));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Despesa $despesa)
    {
        $despesa->delete();
        return $this->response('Despesa deleted', 204);
    }
}

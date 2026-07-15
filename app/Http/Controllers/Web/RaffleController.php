<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRaffleRequest;
use App\Http\Requests\UpdateRaffleRequest;
use App\Models\Raffle;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RaffleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $raffles = Raffle::all();

        return view('raffle.index', compact('raffles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('raffle.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRaffleRequest $request)
    {
        try {
            DB::beginTransaction();

            $user_id = auth()->user()->id;
            $request['created_by'] = $user_id;

            $raffle = Raffle::create($request->all());

            DB::commit();

            $Mensaje = 'success__Agregado correctamente';

            if ($request->accion == 'continuar') {
                return redirect()->route('raffle.index')->with('mensaje', $Mensaje);
            }
            return redirect()->route('raffle.create')->with('mensaje', $Mensaje);

        } catch (Exception $e) {
            DB::rollBack();
            $Mensaje = 'error__' . $e->getMessage();
            return redirect()->route('raffle.create')->with('mensaje', $Mensaje)->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Raffle $raffle)
    {
        return view('raffle.edit', compact('raffle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRaffleRequest $request, Raffle $raffle)
    {
        try {
            DB::beginTransaction();
            $raffle->update($request->except('_token', '_method'));
            DB::commit();
            $Mensaje = 'success__Actualizado correctamente';
            return redirect()->route('raffle.index')->with('mensaje', $Mensaje);
        } catch (\Exception $e) {
            DB::rollBack();
            $Mensaje = 'error__' . $e->getMessage();
            return redirect()->route('raffle.edit')->with('mensaje', $Mensaje)->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $raffle = Raffle::find($id);
            $raffle->delete();
            DB::commit();
            $Mensaje = 'success__Eliminado correctamente';
        } catch (\Exception $e) {
            DB::rollBack();
            $Mensaje = 'error__' . $e->getMessage();
        }
        return redirect()->route('raffle.index')->with('mensaje', $Mensaje);
    }
}

<?php

namespace App\Http\Controllers\Web;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Models\Raffle;
use App\Models\Status;
use App\Http\Requests\Raffle\StoreRaffleRequest;
use App\Http\Requests\Raffle\UpdateRaffleRequest;

class RaffleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $raffles = Raffle::with('status')
            ->when($request->search, fn ($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->when($request->status, fn ($q) => $q->whereHas('status', fn ($s) => $s->where('name', $request->status)))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'active'   => Raffle::whereHas('status', fn ($q) => $q->where('name', 'active'))->count(),
            'draft'    => Raffle::whereHas('status', fn ($q) => $q->where('name', 'draft'))->count(),
            'finished' => Raffle::whereHas('status', fn ($q) => $q->where('name', 'finished'))->count(),
        ];

        $statuses = Status::all();

        return view('raffle.index', compact('raffles', 'stats', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $statuses = Status::all();

        return view('raffle.create', compact('statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRaffleRequest $request)
    {
        $validated = $request->validated();

        $validated['created_by'] = auth()->id();

        $raffle = Raffle::create($validated);

        return redirect()
            ->route('raffle.show', $raffle)
            ->with('success', 'Sorteo creado exitosamente.');
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
        $statuses = Status::all();

        return view('raffle.edit', compact('raffle', 'statuses'));
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

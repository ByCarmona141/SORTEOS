<?php

namespace App\Http\Controllers\Web;

use App\Models\Type;
use App\Models\Prize;
use App\Models\Raffle;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Prize\StorePrizeRequest;
use App\Http\Requests\Prize\UpdatePrizeRequest;

use App\Http\Controllers\Controller;


class PrizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Raffle $raffle)
    {
        $prizes = $raffle->prizes()->orderBy('position')->get();

        return view('prize.index', compact('raffle', 'prizes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Raffle $raffle)
    {
        $types = Type::orderBy('name')->get();
        $nextPosition = ($raffle->prizes()->max('position') ?? 0) + 1;

        return view('prize.create', compact('raffle', 'types', 'nextPosition'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePrizeRequest $request, Raffle $raffle)
    {
        $validated = $request->safe()->except('image');

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('prizes', 'public');
        }

        $raffle->prizes()->create($validated);

        return redirect()->route('raffle.prize.index', $raffle)
            ->with('success', 'Premio agregado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Prize $prize)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Raffle $raffle, Prize $prize)
    {
        $types = Type::orderBy('name')->get();

        return view('prize.edit', compact('raffle', 'prize', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePrizeRequest $request, Raffle $raffle, Prize $prize)
    {
        $validated = $request->safe()->except('image');

        if ($request->hasFile('image')) {
            // Borramos la imagen anterior para no dejar basura en el disco
            if ($prize->image_path) {
                Storage::disk('public')->delete($prize->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('prizes', 'public');
        }

        $prize->update($validated);

        return redirect()->route('raffle.prize.index', $raffle)
            ->with('success', 'Premio actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Raffle $raffle, Prize $prize)
    {
        if ($prize->image_path) {
            Storage::disk('public')->delete($prize->image_path);
        }

        $prize->delete();

        return redirect()->route('raffle.prize.index', $raffle)
            ->with('success', 'Premio eliminado.');
    }
}

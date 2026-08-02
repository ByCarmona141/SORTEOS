<?php

namespace App\Http\Controllers\Web;

use App\Models\Payment;
use App\Models\Raffle;
use App\Models\StatusTicket;
use App\Models\StatusPayment;
use App\Models\PaymentMethod;
use App\Http\Traits\Sortable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Payment\StorePaymentRequest;
use App\Http\Requests\Payment\UpdatePaymentRequest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    use Sortable;

    public function index(Request $request)
    {
        $this->authorize('viewAny', Payment::class);

        $user = auth()->user();
        // "Revisor" = admin o gerente (tiene el permiso view-payments)
        $canReview = $user->can('view-payments');

        $query = Payment::with(['user', 'raffle', 'paymentMethod', 'statusPayment']);

        // Si NO es revisor, solo puede ver sus propios pagos,
        // sin importar qué le pongan en la URL.
        if (!$canReview) {
            $query->where('user_id', $user->id);
        }

        $query->when($request->status, fn ($q) => $q->whereHas(
            'statusPayment',
            fn ($s) => $s->where('name', $request->status)
        ))->when($request->search && $canReview, fn ($q) => $q->whereHas(
            'user',
            fn ($u) => $u->where('name', 'like', "%{$request->search}%")
        ));

        $this->applySorting($query, $request, ['total_amount', 'created_at'], 'created_at');

        $payments = $query->paginate($this->resolvePerPage($request))->withQueryString();

        $statusPayments = StatusPayment::withCount('payments')->get();

        return view('payment.index', compact('payments', 'statusPayments', 'canReview'));
    }

    public function create()
    {
        $this->authorize('create', Payment::class);

        $raffles = Raffle::orderBy('name')->get();
        $paymentMethods = PaymentMethod::all();

        return view('payment.create', compact('raffles', 'paymentMethods'));
    }

    public function store(StorePaymentRequest $request)
    {
        $validated = $request->validated();

        $pendiente = StatusPayment::where('name', 'Pendiente')->first();

        $path = $request->file('proof_image')->store('comprobantes', 'public');

        Payment::create([
            ...$validated,
            'proof_image' => $path,
            'status_payment_id' => $pendiente?->id,
        ]);

        return redirect()->route('payment.index')->with('success', 'Pago registrado. Queda pendiente de validación.');
    }

    public function show(Payment $payment)
    {
        $this->authorize('view', $payment);

        $payment->load(['user', 'raffle', 'paymentMethod', 'statusPayment', 'validator', 'tickets']);

        $canReview = auth()->user()->can('update-payments');

        return view('payment.show', compact('payment', 'canReview'));
    }

    public function edit(Payment $payment)
    {
        $this->authorize('update', $payment);

        $paymentMethods = PaymentMethod::all();

        return view('payment.edit', compact('payment', 'paymentMethods'));
    }

    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        $this->authorize('update', $payment);

        $validated = $request->validated();

        if ($request->hasFile('proof_image')) {
            if ($payment->proof_image) {
                Storage::disk('public')->delete($payment->proof_image);
            }
            $validated['proof_image'] = $request->file('proof_image')->store('comprobantes', 'public');
        }

        $payment->update($validated);

        return redirect()->route('payment.show', $payment)->with('success', 'Pago actualizado correctamente.');
    }

    public function destroy(Payment $payment)
    {
        $this->authorize('delete', $payment);

        if ($payment->proof_image) {
            Storage::disk('public')->delete($payment->proof_image);
        }

        $payment->delete();

        return redirect()->route('payment.index')->with('success', 'Pago eliminado.');
    }

    /**
     * Aprobar un pago: lo marca como validado y pasa
     * sus boletos a "Pagado".
     */
    public function approve(Payment $payment)
    {
        $this->authorize('review', $payment);

        DB::transaction(function () use ($payment) {
            $validado = StatusPayment::where('name', 'Validado')->first();
            $pagado = StatusTicket::where('name', 'Pagado')->first();

            $payment->update([
                'status_payment_id' => $validado?->id,
                'validated_by' => auth()->id(),
                'validated_at' => now(),
            ]);

            if ($pagado) {
                $payment->tickets()->update([
                    'status_ticket_id' => $pagado->id,
                    'paid_at' => now(),
                ]);
            }
        });

        return back()->with('success', 'Pago aprobado. Los boletos quedaron marcados como pagados.');
    }

    /**
     * Rechazar un pago: lo marca como rechazado y libera
     * sus boletos para que otro cliente pueda apartarlos.
     */
    public function reject(Payment $payment)
    {
        $this->authorize('review', $payment);

        DB::transaction(function () use ($payment) {
            $rechazado = StatusPayment::where('name', 'Rechazado')->first();
            $disponible = StatusTicket::where('name', 'Disponible')->first();

            $payment->update([
                'status_payment_id' => $rechazado?->id,
                'validated_by' => auth()->id(),
                'validated_at' => now(),
            ]);

            if ($disponible) {
                $payment->tickets()->update([
                    'status_ticket_id' => $disponible->id,
                    'user_id' => null,
                    'payment_id' => null,
                    'reserved_at' => null,
                ]);
            }
        });

        return back()->with('success', 'Pago rechazado. Los boletos quedaron disponibles nuevamente.');
    }

    /**
     * Revertir un pago ya aprobado o rechazado, dejándolo
     * de nuevo como Pendiente para poder revisarlo otra vez.
     */
    public function revertToPending(Payment $payment)
    {
        $this->authorize('review', $payment);

        DB::transaction(function () use ($payment) {
            $pendiente = StatusPayment::where('name', 'Pendiente')->first();
            $apartado = StatusTicket::where('name', 'Apartado')->first();

            $payment->update([
                'status_payment_id' => $pendiente?->id,
                'validated_by' => null,
                'validated_at' => null,
            ]);

            // Los boletos vuelven a "Apartado" (siguen ligados al cliente,
            // solo que otra vez en espera de revisión).
            if ($apartado) {
                $payment->tickets()->update([
                    'status_ticket_id' => $apartado->id,
                    'paid_at' => null,
                ]);
            }
        });

        return back()->with('success', 'El pago volvió a estado Pendiente.');
    }
}

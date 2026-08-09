<?php

namespace App\Http\Requests\Prize;

use App\Models\Prize;
use App\Models\Ticket;

use Illuminate\Foundation\Http\FormRequest;

class AssignWinnerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $raffle = $this->route('raffle');
        $prize = $this->route('prize');
        $maxDrawnNumber = $raffle->ticket_count * $raffle->opportunities;

        return [
            'drawn_number' => [
                'required',
                'numeric',
                'min:1',
                "max:{$maxDrawnNumber}",
                function ($attribute, $value, $fail) use ($raffle, $prize) {
                    // Calculamos a qué boleto físico corresponde el número que salió
                    $physicalNumber = $raffle->physicalTicketNumber((int) $value);

                    $ticket = Ticket::where('raffle_id', $raffle->id)
                        ->where('number', $physicalNumber)
                        ->first();

                    if (!$ticket) {
                        $fail("No se encontró el boleto {$physicalNumber} en este sorteo.");
                        return;
                    }

                    // ¿Ese boleto ya ganó OTRO premio del mismo sorteo?
                    $conflict = Prize::where('raffle_id', $raffle->id)
                        ->where('ticket_id', $ticket->id)
                        ->where('id', '!=', $prize->id)
                        ->first();

                    if ($conflict) {
                        $fail("El boleto {$physicalNumber} ya es el ganador del premio \"{$conflict->title}\" (lugar {$conflict->position}).");
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'drawn_number.max' => 'Ese número es demasiado grande para este sorteo. El máximo posible es :max.',
        ];
    }
}

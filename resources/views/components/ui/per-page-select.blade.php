@props([
    'options' => [10, 15, 25], // valores permitidos
])

<select name="per_page" onchange="this.form.submit()"
        class="px-3 py-sm bg-surface border border-outline-variant rounded text-on-surface-variant text-sm focus:border-primary focus:ring-0">
    @foreach ($options as $option)
        <option value="{{ $option }}" @selected(request('per_page', $options[0]) == $option)>
            {{ $option }} por página
        </option>
    @endforeach
</select>

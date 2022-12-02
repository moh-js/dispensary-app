<li>
    <select class="mr-3" wire:model="station" style="border: none; padding:3px;">
        @foreach ($stations as $station)
            <option value="{{ $station->name }}">{{ title_case($station->name) }}</option>
        @endforeach
    </select>
</li>
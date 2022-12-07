<li>
    <select class="mr-3" wire:model="station_id" style="border: none; padding:3px;">
        @foreach ($stations as $station)
            <option value="{{ $station->id }}">{{ title_case($station->name) }}</option>
        @endforeach
    </select>
</li>
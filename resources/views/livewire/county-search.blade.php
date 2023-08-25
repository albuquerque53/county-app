<div>
    <div>
        <input type="text" wire:model="countyCode" placeholder="Type the UF you want to search"/>

        <button wire:click="searchForCounty">Search</button>
    </div>

    <div>
        {{ $result }}
    </div>
</div>

@php use Illuminate\Support\Facades\Vite; @endphp

<div class="flex flex-col items-center h-screen">

    <h1 class="text-xl mt-20 mb-15">List brazilian states by county code</h1>

    <div class="mt-10 flex items-center justify-center justify-center">
        <img class="w-3/12" src="{{ Vite::asset('resources/images/brazil.png') }}"></img>
    </div>

    <div class="m-5 w-1/2 flex items-center justify-center">
        <input type="text" wire:model="countyCode" placeholder="Type the UF you want to look" class="py-2 px-3 rounded border text-center bg-transparent w-1/3"/>
    </div>

    <button wire:click="searchForCounty" class="rounded py-2 px-3 border-solid border hover:bg-teal-900">
        Search
    </button>

    @if ($result)

        <h2 class="text-lg mt-7 mb-3">States from {{ $countyCode }}:</h2>

        <div class="grid gap-4 grid-cols-3 grid-rows-3 ">
            @foreach ($result as $county)

                <div class="p-10 m-5 bg-gray-900/20 text-white rounded shadow-md">
                    <p><strong>State Name:</strong> {{ $county['name'] }} </p>
                    <p><strong>IBGE Code:</strong> {{ $county['ibge_code'] }}</p>
                </div>

            @endforeach
        </div>

    @endif

</div>

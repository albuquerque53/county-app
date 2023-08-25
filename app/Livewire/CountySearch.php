<?php

namespace App\Livewire;

use App\Enums\CountyCodeEnum;
use App\Repository\CountyRepositoryInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Title;
use Livewire\Component;

class CountySearch extends Component
{
    public ?Collection $result = null;
    public ?string $countyCode = null;

    #[Title('County search')]
    public function render(): View
    {
        return view('livewire.county-search');
    }

    public function searchForCounty(): void
    {
        if (blank($this->countyCode)) {
            return;
        }

        /** @var CountyRepositoryInterface $countyRepository */
        $countyRepository = app(CountyRepositoryInterface::class);

        $this->result = $countyRepository
            ->findByCountyCode(
                countyCode: CountyCodeEnum::from(strtoupper($this->countyCode)),
                pageNumber: 1,
                pageSize: 100,
            );
    }
}

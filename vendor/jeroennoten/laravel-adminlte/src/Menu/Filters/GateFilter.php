<?php

namespace JeroenNoten\LaravelAdminLte\Menu\Filters;

use Illuminate\Contracts\Auth\Access\Gate;
use JeroenNoten\LaravelAdminLte\Menu\Builder;

class GateFilter implements FilterInterface
{
    protected $gate;

    public function __construct(Gate $gate)
    {
        $this->gate = $gate;
    }

    public function transform($item, Builder $builder)
    {
        if (! $this->isVisible($item)) {
            return false;
        }

        return $item;
    }

    protected function isVisible($item)
    {
        if (! isset($item['can']) && ! isset($item['notcan'])) {
            return true;
        }

        if (isset($item['model'])) {
            return $this->gate->allows($item['can'], $item['model']);
        }

        if(isset($item['notcan'])){

            $notcans = explode(',', str_replace(' ', '', $item['notcan']));
            foreach($notcans as $notcan){
                if($this->gate->allows($notcan)){
                    return false;
                }
            }
            return true;

        }

        return $this->gate->allows($item['can']);
    }
}

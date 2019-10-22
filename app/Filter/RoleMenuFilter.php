<?php

namespace App\Filter;

use Illuminate\Support\Facades\Auth;
use JeroenNoten\LaravelAdminLte\Menu\Builder;
use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;

class RoleMenuFilter implements FilterInterface
{
    public function transform($item, Builder $builder)
    {
        if (isset($item['roles']) && !Auth::user()->hasRole($item['roles'])) {
            return false;
        }

        return $item;
    }
}

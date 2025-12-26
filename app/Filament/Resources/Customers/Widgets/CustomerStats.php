<?php

namespace App\Filament\Resources\CustomerResource\Widgets;

use App\Models\Customer;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class CustomerStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Customers', Customer::count())
                ->description('All registered customers')
                ->color('primary'),

            Stat::make('New This Month', $this->newCustomersThisMonth())
                ->description('Joined this month')
                ->color('info'),

            Stat::make('VIP Customers', $this->vipCustomers())
                ->description('High-value customers')
                ->color('warning'),

            Stat::make('Active Customers', $this->activeCustomers())
                ->description('With at least 1 booking')
                ->color('success'),

            Stat::make('Returning Customers', $this->returningCustomers())
                ->description('2+ bookings')
                ->color('success'),

            Stat::make('No Bookings', $this->customersWithoutBookings())
                ->description('Needs engagement')
                ->color('danger'),
        ];
    }

    protected function newCustomersThisMonth(): int
    {
        return Customer::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
    }

    protected function vipCustomers(): int
    {
        return Customer::where('is_vip', true)->count();
    }

    protected function activeCustomers(): int
    {
        return Customer::has('bookings')->count();
    }

    protected function returningCustomers(): int
    {
        return Customer::has('bookings', '>=', 2)->count();
    }

    protected function customersWithoutBookings(): int
    {
        return Customer::doesntHave('bookings')->count();
    }
}

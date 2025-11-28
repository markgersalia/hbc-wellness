<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\Customer;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverview extends StatsOverviewWidget
{

    function formatNumberShort($number)
    {
        if ($number >= 1000000) {
            return number_format($number / 1000000, 1) . 'M';
        } elseif ($number >= 1000) {
            return number_format($number / 1000, 1) . 'K';
        } else {
            return number_format($number, 2);
        }
    }
    protected function getStats(): array
    {
        $startDate = $this->pageFilters['startDate'] ?? null;
        $endDate = $this->pageFilters['endDate'] ?? null;
        $revenue = BookingPayment::query()
            ->paid()
            ->sum('amount');
        $revenueThisMonth = BookingPayment::query()
            ->where('created_at', '>=', now()->startOfMonth())
            ->paid()
            ->sum('amount');
            
        $completedBooking = Booking::query()
            ->completed()
            ->count();
        $overAllRevenue = $this->formatNumberShort($revenue);
        $revenueThisMonth = $this->formatNumberShort($revenueThisMonth);
        return [
            Stat::make(
                'All Time Revenue',
                $overAllRevenue
            )->chart([1, 1000])
                ->description('Total confirmed payments')
                ->descriptionIcon('heroicon-o-check')
                ->color('success'),

            Stat::make(
                'Revenue This Month',
                $revenueThisMonth
            )
                ->description('Total confirmed payments for this month')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('primary'),

            Stat::make(
                'Total Bookings',
                Booking::query()->count()
            )
                ->description($completedBooking.' completed')
                // ->color('primary')
                ->descriptionIcon('heroicon-o-calendar-days'),
            // ->color('primary'),


            Stat::make(
                'Customers',
                Customer::query()
                    ->count()
            )
                ->description(Customer::whereHas('bookings',function($q){
                    $q->confirmed();
                })->count() . ' has active booking')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('success'),
        ];
    }
}

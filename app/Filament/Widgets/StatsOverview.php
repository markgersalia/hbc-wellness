<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\Customer;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        $revenue = BookingPayment::query()
            ->paid()
            ->whereHas('booking', fn($q) => $q->completed())
            ->sum('amount');

        $revenueThisMonth = BookingPayment::query()
            ->where('created_at', '>=', now()->startOfMonth())
            ->whereHas('booking', fn($q) => $q->completed())
            ->paid()
            ->sum('amount');

        $completedBooking = Booking::query()->completed()->count();

        $overAllRevenue = $this->formatNumberShort($revenue);
        $revenueThisMonth = $this->formatNumberShort($revenueThisMonth);

        $revenueChart = $this->generateChartData(
            BookingPayment::query()
                ->paid()
                ->whereHas('booking', fn($q) => $q->completed())
                ->selectRaw('DATE(created_at) as date, SUM(amount) as total')
                ->groupBy('date')
                ->pluck('total', 'date')
                ->toArray()
        );

        $bookingChart = $this->generateChartData(
            Booking::query()
                ->completed()
                ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
                ->groupBy('date')
                ->pluck('total', 'date')
                ->toArray()
        );

        $customerChart = $this->generateChartData(
            Customer::query()
                ->whereHas('bookings', fn($q) => $q->confirmed())
                ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
                ->groupBy('date')
                ->pluck('total', 'date')
                ->toArray()
        );

        return [
            Stat::make('All Time Revenue', $overAllRevenue)
                ->chart($revenueChart)
                ->description('All-time confirmed payments')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success'),

            Stat::make('Revenue This Month', $revenueThisMonth)
                ->chart($revenueChart)
                ->description('Payments this month')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success'),

            Stat::make('Total Bookings', Booking::query()->count())
                ->chart($bookingChart)
                ->description($completedBooking . ' completed')
                ->descriptionIcon('heroicon-o-calendar-days')
                ->color('primary'),

            Stat::make('Customers', Customer::query()->count())
                ->chart($customerChart)
                ->description(Customer::whereHas('bookings', fn($q) => $q->confirmed())->count() . ' has active booking')
                ->descriptionIcon('heroicon-o-users')
                ->color('warning'),
        ];
    }

    private function generateChartData(array $rawData, int $days = 7): array
    {
        $chartData = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $chartData[] = isset($rawData[$date]) ? (float) $rawData[$date] : 0;
        }
        return $chartData;
    }
}

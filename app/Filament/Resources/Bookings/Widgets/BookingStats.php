<?php

namespace App\Filament\Resources\BookingResource\Widgets;

use App\Models\Booking;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class BookingStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Today’s Bookings', $this->todayBookings())
                ->description('All bookings scheduled today')
                ->color('primary'),

            Stat::make('Upcoming Bookings', $this->upcomingBookings())
                ->description('Confirmed & pending')
                ->color('info'),

            Stat::make('Completed', $this->completedBookings())
                ->description('Successfully finished')
                ->color('success'),

            Stat::make('Canceled', $this->canceledBookings())
                ->description('Canceled bookings')
                ->color('danger'),

            Stat::make('Today’s Revenue', '₱ ' . number_format($this->todayRevenue(), 2))
                ->description('Completed & paid today')
                ->color('success'),

            Stat::make('Pending Payments', $this->pendingPayments())
                ->description('Awaiting payment')
                ->color('warning'),
        ];
    }

    protected function todayBookings(): int
    {
        return Booking::whereDate('start_time', Carbon::today())->count();
    }

    protected function upcomingBookings(): int
    {
        return Booking::where('start_time', '>', now())
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();
    }

    protected function completedBookings(): int
    {
        return Booking::where('status', 'completed')->count();
    }

    protected function canceledBookings(): int
    {
        return Booking::where('status', 'canceled')->count();
    }

    protected function todayRevenue(): float
    {
        return Booking::whereDate('start_time', Carbon::today())
            ->where('status', 'completed')
            ->where('payment_status', 'paid')
            ->sum('price');
    }

    protected function pendingPayments(): int
    {
        return Booking::where('payment_status', 'pending')->count();
    }
}

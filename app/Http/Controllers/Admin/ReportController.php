<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Reports Dashboard - Overview of all reports
     */
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        
        // Quick stats for dashboard
        $stats = [
            'today_revenue' => Payment::whereDate('paid_at', $today)->where('status', 'completed')->sum('amount'),
            'month_revenue' => Payment::whereDate('paid_at', '>=', $thisMonth)->where('status', 'completed')->sum('amount'),
            'today_bookings' => Booking::whereDate('created_at', $today)->count(),
            'month_bookings' => Booking::whereDate('created_at', '>=', $thisMonth)->count(),
            'current_occupancy' => $this->calculateCurrentOccupancy(),
            'pending_payments' => Booking::where('payment_status', '!=', 'paid')->sum('total_price') - 
                                  Payment::where('status', 'completed')->sum('amount'),
        ];
        
        return view('admin.reports.index', compact('stats'));
    }

    /**
     * Revenue Report - Daily, Weekly, Monthly revenue breakdown
     */
    public function revenue(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        
        // Get years for dropdown
        $years = Payment::selectRaw('YEAR(paid_at) as year')
            ->whereNotNull('paid_at')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
        
        if ($years->isEmpty()) {
            $years = collect([date('Y')]);
        }

        $revenueData = [];
        $labels = [];
        $totalRevenue = 0;
        $totalPayments = 0;

        switch ($period) {
            case 'daily':
                // Daily revenue for selected month
                $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
                $endDate = $startDate->copy()->endOfMonth();
                
                $payments = Payment::selectRaw('DATE(paid_at) as date, SUM(amount) as total, COUNT(*) as count')
                    ->where('status', 'completed')
                    ->whereBetween('paid_at', [$startDate, $endDate])
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get()
                    ->keyBy('date');
                
                $current = $startDate->copy();
                while ($current <= $endDate) {
                    $dateKey = $current->format('Y-m-d');
                    $labels[] = $current->format('d');
                    $amount = $payments->get($dateKey)->total ?? 0;
                    $count = $payments->get($dateKey)->count ?? 0;
                    $revenueData[] = [
                        'date' => $current->format('M d, Y'),
                        'amount' => $amount,
                        'count' => $count
                    ];
                    $totalRevenue += $amount;
                    $totalPayments += $count;
                    $current->addDay();
                }
                break;

            case 'weekly':
                // Weekly revenue for current year
                $payments = Payment::selectRaw('WEEK(paid_at) as week, SUM(amount) as total, COUNT(*) as count')
                    ->where('status', 'completed')
                    ->whereYear('paid_at', $year)
                    ->groupBy('week')
                    ->orderBy('week')
                    ->get()
                    ->keyBy('week');
                
                for ($week = 1; $week <= 52; $week++) {
                    $labels[] = "W{$week}";
                    $amount = $payments->get($week)->total ?? 0;
                    $count = $payments->get($week)->count ?? 0;
                    $revenueData[] = [
                        'week' => $week,
                        'amount' => $amount,
                        'count' => $count
                    ];
                    $totalRevenue += $amount;
                    $totalPayments += $count;
                }
                break;

            case 'monthly':
            default:
                // Monthly revenue for selected year
                $payments = Payment::selectRaw('MONTH(paid_at) as month, SUM(amount) as total, COUNT(*) as count')
                    ->where('status', 'completed')
                    ->whereYear('paid_at', $year)
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get()
                    ->keyBy('month');
                
                $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                for ($m = 1; $m <= 12; $m++) {
                    $labels[] = $monthNames[$m - 1];
                    $amount = $payments->get($m)->total ?? 0;
                    $count = $payments->get($m)->count ?? 0;
                    $revenueData[] = [
                        'month' => $monthNames[$m - 1],
                        'amount' => $amount,
                        'count' => $count
                    ];
                    $totalRevenue += $amount;
                    $totalPayments += $count;
                }
                break;
        }

        // Payment method breakdown
        $methodBreakdown = Payment::selectRaw('payment_method, SUM(amount) as total, COUNT(*) as count')
            ->where('status', 'completed')
            ->whereYear('paid_at', $year)
            ->when($period === 'daily', function ($q) use ($year, $month) {
                $start = Carbon::createFromDate($year, $month, 1)->startOfMonth();
                $end = $start->copy()->endOfMonth();
                return $q->whereBetween('paid_at', [$start, $end]);
            })
            ->groupBy('payment_method')
            ->get();

        return view('admin.reports.revenue', compact(
            'period', 'year', 'month', 'years', 'revenueData', 'labels',
            'totalRevenue', 'totalPayments', 'methodBreakdown'
        ));
    }

    /**
     * Occupancy Report - Room occupancy statistics
     */
    public function occupancy(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        $daysInMonth = $startDate->daysInMonth;
        
        $totalRooms = Room::where('status', '!=', 'out_of_order')->count();
        $roomTypes = RoomType::withCount('rooms')->get();
        
        // Calculate daily occupancy for the month
        $dailyOccupancy = [];
        $labels = [];
        $totalOccupiedNights = 0;

        $current = $startDate->copy();
        while ($current <= $endDate) {
            $occupiedRooms = Booking::whereHas('rooms')
                ->where(function ($q) use ($current) {
                    $q->whereDate('check_in', '<=', $current)
                      ->whereDate('check_out', '>', $current);
                })
                ->whereIn('status', ['confirmed', 'checked_in'])
                ->withCount('rooms')
                ->get()
                ->sum('rooms_count');

            $occupancyRate = $totalRooms > 0 ? ($occupiedRooms / $totalRooms) * 100 : 0;
            
            $labels[] = $current->format('d');
            $dailyOccupancy[] = [
                'date' => $current->format('M d, Y'),
                'occupied' => $occupiedRooms,
                'available' => $totalRooms - $occupiedRooms,
                'rate' => round($occupancyRate, 1)
            ];
            $totalOccupiedNights += $occupiedRooms;
            $current->addDay();
        }

        // Calculate average occupancy
        $avgOccupancy = $totalRooms > 0 ? ($totalOccupiedNights / ($totalRooms * $daysInMonth)) * 100 : 0;
        
        // Occupancy by room type
        $roomTypeOccupancy = [];
        foreach ($roomTypes as $type) {
            $typeRooms = $type->rooms_count;
            $occupiedNights = 0;
            
            $current = $startDate->copy();
            while ($current <= $endDate) {
                $occupiedNights += Booking::whereHas('rooms', function ($q) use ($type) {
                        $q->where('room_type_id', $type->id);
                    })
                    ->where(function ($q) use ($current) {
                        $q->whereDate('check_in', '<=', $current)
                          ->whereDate('check_out', '>', $current);
                    })
                    ->whereIn('status', ['confirmed', 'checked_in'])
                    ->count();
                $current->addDay();
            }
            
            $typeOccupancy = $typeRooms > 0 ? ($occupiedNights / ($typeRooms * $daysInMonth)) * 100 : 0;
            $roomTypeOccupancy[] = [
                'name' => $type->room_type_name,
                'total_rooms' => $typeRooms,
                'occupied_nights' => $occupiedNights,
                'rate' => round($typeOccupancy, 1)
            ];
        }

        // Get years for dropdown
        $years = Booking::selectRaw('YEAR(check_in) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
        
        if ($years->isEmpty()) {
            $years = collect([date('Y')]);
        }

        return view('admin.reports.occupancy', compact(
            'year', 'month', 'years', 'totalRooms', 'dailyOccupancy', 'labels',
            'avgOccupancy', 'roomTypeOccupancy', 'daysInMonth'
        ));
    }

    /**
     * Booking Report - Booking trends and statistics
     */
    public function bookings(Request $request)
    {
        $startDate = $request->get('start_date') ? Carbon::parse($request->get('start_date')) : Carbon::now()->startOfMonth();
        $endDate = $request->get('end_date') ? Carbon::parse($request->get('end_date')) : Carbon::now()->endOfMonth();
        $status = $request->get('status');

        // Booking statistics
        $query = Booking::whereBetween('created_at', [$startDate, $endDate]);
        if ($status) {
            $query->where('status', $status);
        }

        $totalBookings = (clone $query)->count();
        $totalRevenue = (clone $query)->sum('total_price');
        
        // Status breakdown
        $statusBreakdown = Booking::selectRaw('status, COUNT(*) as count, SUM(total_price) as revenue')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('status')
            ->get();

        // Daily booking trend
        $dailyBookings = Booking::selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(total_price) as revenue')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->when($status, fn($q) => $q->where('status', $status))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Room type popularity
        $roomTypePopularity = DB::table('booking_rooms')
            ->join('rooms', 'booking_rooms.room_id', '=', 'rooms.id')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->join('bookings', 'booking_rooms.booking_id', '=', 'bookings.id')
            ->whereBetween('bookings.created_at', [$startDate, $endDate])
            ->selectRaw('room_types.room_type_name, COUNT(*) as bookings, SUM(booking_rooms.price) as revenue')
            ->groupBy('room_types.id', 'room_types.room_type_name')
            ->orderByDesc('bookings')
            ->get();

        // Average stay duration
        $avgStayDuration = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->when($status, fn($q) => $q->where('status', $status))
            ->selectRaw('AVG(DATEDIFF(check_out, check_in)) as avg_nights')
            ->value('avg_nights') ?? 0;

        // Lead time (days between booking and check-in)
        $avgLeadTime = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->when($status, fn($q) => $q->where('status', $status))
            ->selectRaw('AVG(DATEDIFF(check_in, DATE(created_at))) as avg_lead')
            ->value('avg_lead') ?? 0;

        // Recent bookings list
        $recentBookings = Booking::with(['user', 'rooms.roomType'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->when($status, fn($q) => $q->where('status', $status))
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.reports.bookings', compact(
            'startDate', 'endDate', 'status', 'totalBookings', 'totalRevenue',
            'statusBreakdown', 'dailyBookings', 'roomTypePopularity',
            'avgStayDuration', 'avgLeadTime', 'recentBookings'
        ));
    }

    /**
     * Guest Report - Guest demographics and top guests
     */
    public function guests(Request $request)
    {
        $year = $request->get('year', date('Y'));
        
        // Guest statistics
        $totalGuests = User::role('guest')->count();
        $newGuestsThisYear = User::role('guest')->whereYear('created_at', $year)->count();
        
        // Top guests by revenue
        $topGuestsByRevenue = User::role('guest')
            ->withCount('bookings')
            ->withSum('bookings', 'total_price')
            ->having('bookings_count', '>', 0)
            ->orderByDesc('bookings_sum_total_price')
            ->take(10)
            ->get();

        // Top guests by bookings
        $topGuestsByBookings = User::role('guest')
            ->withCount('bookings')
            ->withSum('bookings', 'total_price')
            ->having('bookings_count', '>', 0)
            ->orderByDesc('bookings_count')
            ->take(10)
            ->get();

        // Repeat vs new guests
        $repeatGuests = User::role('guest')
            ->withCount('bookings')
            ->having('bookings_count', '>', 1)
            ->count();
        
        $singleBookingGuests = User::role('guest')
            ->withCount('bookings')
            ->having('bookings_count', '=', 1)
            ->count();

        // Monthly new guests trend
        $monthlyNewGuests = User::role('guest')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        // Country/nationality distribution (if available)
        // Country/nationality distribution (if available)
        $guestDistribution = User::role('guest')
            ->join('guest_profiles', 'users.id', '=', 'guest_profiles.user_id')
            ->selectRaw('guest_profiles.country, COUNT(*) as count')
            ->whereNotNull('guest_profiles.country')
            ->groupBy('guest_profiles.country')
            ->orderByDesc('count')
            ->take(10)
            ->get();

        // Get years for dropdown
        $years = User::role('guest')
            ->selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
        
        if ($years->isEmpty()) {
            $years = collect([date('Y')]);
        }

        return view('admin.reports.guests', compact(
            'year', 'years', 'totalGuests', 'newGuestsThisYear',
            'topGuestsByRevenue', 'topGuestsByBookings',
            'repeatGuests', 'singleBookingGuests', 'monthlyNewGuests',
            'guestDistribution'
        ));
    }

    /**
     * Room Performance Report
     */
    public function roomPerformance(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month');
        
        $startDate = $month 
            ? Carbon::createFromDate($year, $month, 1)->startOfMonth()
            : Carbon::createFromDate($year, 1, 1)->startOfYear();
        $endDate = $month 
            ? $startDate->copy()->endOfMonth()
            : Carbon::createFromDate($year, 12, 31)->endOfYear();

        $periodDays = $startDate->diffInDays($endDate) + 1;

        // Room type performance
        $roomTypes = RoomType::with(['rooms'])->get();
        $roomTypePerformance = [];

        foreach ($roomTypes as $type) {
            $roomIds = $type->rooms->pluck('id');
            
            // Revenue from this room type
            $revenue = DB::table('booking_rooms')
                ->join('bookings', 'booking_rooms.booking_id', '=', 'bookings.id')
                ->whereIn('booking_rooms.room_id', $roomIds)
                ->whereBetween('bookings.created_at', [$startDate, $endDate])
                ->whereIn('bookings.status', ['confirmed', 'checked_in', 'checked_out'])
                ->sum('booking_rooms.price');

            // Number of bookings
            $bookingCount = DB::table('booking_rooms')
                ->join('bookings', 'booking_rooms.booking_id', '=', 'bookings.id')
                ->whereIn('booking_rooms.room_id', $roomIds)
                ->whereBetween('bookings.created_at', [$startDate, $endDate])
                ->count();

            // Calculate room nights sold
            $roomNightsSold = DB::table('booking_rooms')
                ->join('bookings', 'booking_rooms.booking_id', '=', 'bookings.id')
                ->whereIn('booking_rooms.room_id', $roomIds)
                ->whereBetween('bookings.created_at', [$startDate, $endDate])
                ->whereIn('bookings.status', ['confirmed', 'checked_in', 'checked_out'])
                ->selectRaw('SUM(DATEDIFF(bookings.check_out, bookings.check_in)) as nights')
                ->value('nights') ?? 0;

            $totalRoomNights = $type->rooms->count() * $periodDays;
            $occupancyRate = $totalRoomNights > 0 ? ($roomNightsSold / $totalRoomNights) * 100 : 0;
            $avgDailyRate = $roomNightsSold > 0 ? $revenue / $roomNightsSold : 0;
            $revPAR = $totalRoomNights > 0 ? $revenue / $totalRoomNights : 0; // Revenue per available room

            $roomTypePerformance[] = [
                'name' => $type->room_type_name,
                'total_rooms' => $type->rooms->count(),
                'bookings' => $bookingCount,
                'revenue' => $revenue,
                'nights_sold' => $roomNightsSold,
                'occupancy_rate' => round($occupancyRate, 1),
                'avg_daily_rate' => round($avgDailyRate, 2),
                'revpar' => round($revPAR, 2)
            ];
        }

        // Individual room performance
        $roomPerformance = Room::with('roomType')
            ->get()
            ->map(function ($room) use ($startDate, $endDate, $periodDays) {
                $revenue = DB::table('booking_rooms')
                    ->join('bookings', 'booking_rooms.booking_id', '=', 'bookings.id')
                    ->where('booking_rooms.room_id', $room->id)
                    ->whereBetween('bookings.created_at', [$startDate, $endDate])
                    ->whereIn('bookings.status', ['confirmed', 'checked_in', 'checked_out'])
                    ->sum('booking_rooms.price');

                $nightsSold = DB::table('booking_rooms')
                    ->join('bookings', 'booking_rooms.booking_id', '=', 'bookings.id')
                    ->where('booking_rooms.room_id', $room->id)
                    ->whereBetween('bookings.created_at', [$startDate, $endDate])
                    ->whereIn('bookings.status', ['confirmed', 'checked_in', 'checked_out'])
                    ->selectRaw('SUM(DATEDIFF(bookings.check_out, bookings.check_in)) as nights')
                    ->value('nights') ?? 0;

                return [
                    'room_number' => $room->room_number,
                    'room_type' => $room->roomType->room_type_name ?? 'N/A',
                    'revenue' => $revenue,
                    'nights_sold' => $nightsSold,
                    'occupancy_rate' => round(($nightsSold / $periodDays) * 100, 1)
                ];
            })
            ->sortByDesc('revenue')
            ->values();

        // Get years for dropdown
        $years = Booking::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
        
        if ($years->isEmpty()) {
            $years = collect([date('Y')]);
        }

        return view('admin.reports.room-performance', compact(
            'year', 'month', 'years', 'roomTypePerformance', 'roomPerformance',
            'startDate', 'endDate', 'periodDays'
        ));
    }

    /**
     * Financial Summary Report
     */
    public function financial(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month');
        
        if ($month) {
            $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endDate = $startDate->copy()->endOfMonth();
            $periodLabel = $startDate->format('F Y');
        } else {
            $startDate = Carbon::createFromDate($year, 1, 1)->startOfYear();
            $endDate = Carbon::createFromDate($year, 12, 31)->endOfYear();
            $periodLabel = "Year {$year}";
        }

        // Revenue summary
        $grossRevenue = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', ['confirmed', 'checked_in', 'checked_out'])
            ->sum('total_price');

        $paymentsReceived = Payment::where('status', 'completed')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->sum('amount');

        $refundsIssued = Payment::where('status', 'refunded')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->sum('amount');

        $netRevenue = $paymentsReceived - abs($refundsIssued);

        // Outstanding payments
        $outstandingPayments = $grossRevenue - $paymentsReceived;

        // Monthly breakdown (if viewing year)
        $monthlyData = [];
        if (!$month) {
            for ($m = 1; $m <= 12; $m++) {
                $monthStart = Carbon::createFromDate($year, $m, 1)->startOfMonth();
                $monthEnd = $monthStart->copy()->endOfMonth();

                $monthlyData[] = [
                    'month' => $monthStart->format('M'),
                    'gross_revenue' => Booking::whereBetween('created_at', [$monthStart, $monthEnd])
                        ->whereIn('status', ['confirmed', 'checked_in', 'checked_out'])
                        ->sum('total_price'),
                    'payments_received' => Payment::where('status', 'completed')
                        ->whereBetween('paid_at', [$monthStart, $monthEnd])
                        ->sum('amount'),
                    'bookings' => Booking::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
                ];
            }
        }

        // Invoice statistics
        $invoiceStats = [
            'total' => Invoice::whereBetween('created_at', [$startDate, $endDate])->count(),
            'paid' => Invoice::where('status', 'paid')->whereBetween('created_at', [$startDate, $endDate])->count(),
            'pending' => Invoice::whereIn('status', ['draft', 'issued'])->whereBetween('created_at', [$startDate, $endDate])->count(),
            'overdue' => Invoice::where('status', 'issued')->where('due_date', '<', now())->whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_amount' => Invoice::whereBetween('created_at', [$startDate, $endDate])->sum('total_amount'),
        ];

        // Payment method breakdown
        $paymentMethodBreakdown = Payment::selectRaw('payment_method, SUM(amount) as total, COUNT(*) as count')
            ->where('status', 'completed')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->groupBy('payment_method')
            ->get();

        // Get years for dropdown
        $years = Payment::selectRaw('YEAR(paid_at) as year')
            ->whereNotNull('paid_at')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
        
        if ($years->isEmpty()) {
            $years = collect([date('Y')]);
        }

        return view('admin.reports.financial', compact(
            'year', 'month', 'years', 'periodLabel', 'startDate', 'endDate',
            'grossRevenue', 'paymentsReceived', 'refundsIssued', 'netRevenue',
            'outstandingPayments', 'monthlyData', 'invoiceStats', 'paymentMethodBreakdown'
        ));
    }

    /**
     * Export report to PDF or Excel (placeholder for future implementation)
     */
    public function export(Request $request, $type)
    {
        // This would be implemented with a package like DomPDF or Laravel Excel
        return back()->with('info', 'Export functionality coming soon!');
    }

    /**
     * Calculate current occupancy rate
     */
    private function calculateCurrentOccupancy()
    {
        $today = Carbon::today();
        $totalRooms = Room::where('status', '!=', 'out_of_order')->count();
        
        $occupiedRooms = Booking::whereHas('rooms')
            ->where(function ($q) use ($today) {
                $q->whereDate('check_in', '<=', $today)
                  ->whereDate('check_out', '>', $today);
            })
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->withCount('rooms')
            ->get()
            ->sum('rooms_count');

        return $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0;
    }
}

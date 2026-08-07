<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Invoice;

/**
 * CustomerAnalyticsService
 *
 * Provides comprehensive analytics and performance metrics for customer data.
 * This service encapsulates all customer-related analytics calculations and
 * data aggregation logic, following Laravel's service layer pattern.
 *
 * @author Smart Inventory System
 */
class CustomerAnalyticsService
{
    protected $customer;

    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * Get comprehensive analytics data for the customer
     */
    public function getAnalyticsData(): array
    {
        // Load customer with invoices for better performance
        $this->customer->load(['invoices' => function ($query) {
            $query->with('payments');
        }]);

        return [
            'financial_summary' => $this->getFinancialSummary(),
            'payment_statistics' => $this->getPaymentStatistics(),
            'monthly_trends' => $this->getMonthlyTrends(),
            'recent_invoices' => $this->getRecentInvoices(),
            'performance_metrics' => $this->getPerformanceMetrics(),
        ];
    }

    /**
     * Get financial summary
     */
    public function getFinancialSummary(): array
    {
        return [
            'total_sales' => $this->customer->total_sales,
            'total_paid' => $this->customer->total_paid,
            'total_outstanding' => $this->customer->total_outstanding,
            'current_balance' => $this->customer->balance,
            'invoice_count' => $this->customer->invoice_count,
        ];
    }

    /**
     * Get payment statistics
     */
    public function getPaymentStatistics(): array
    {
        return $this->customer->getPaymentStats();
    }

    /**
     * Get monthly trends for the last 12 months
     *
     * @return \Illuminate\Support\Collection
     */
    public function getMonthlyTrends()
    {
        return $this->customer->getMonthlySalesData();
    }

    /**
     * Get recent invoices
     *
     * @return \Illuminate\Support\Collection
     */
    public function getRecentInvoices(int $limit = 5)
    {
        return $this->customer->getRecentInvoices($limit);
    }

    /**
     * Get performance metrics
     */
    public function getPerformanceMetrics(): array
    {
        $invoices = $this->customer->invoices;

        if ($invoices->isEmpty()) {
            return [
                'average_invoice_value' => 0,
                'payment_efficiency' => 0,
                'average_payment_time' => 0,
            ];
        }

        $averageInvoiceValue = $invoices->avg('total') ?? 0;

        // Calculate payment efficiency with division by zero protection
        $totalSales = $this->customer->total_sales;
        $paymentEfficiency = $totalSales > 0
            ? ($this->customer->total_paid / $totalSales) * 100
            : 0;

        // Calculate average payment time (simplified)
        $averagePaymentTime = $this->calculateAveragePaymentTime($invoices);

        return [
            'average_invoice_value' => round($averageInvoiceValue, 2),
            'payment_efficiency' => round($paymentEfficiency, 2),
            'average_payment_time' => $averagePaymentTime,
        ];
    }

    /**
     * Calculate average payment time in days
     *
     * @param  mixed  $invoices
     */
    private function calculateAveragePaymentTime($invoices): int
    {
        $totalDays = 0;
        $paidInvoicesCount = 0;

        foreach ($invoices as $invoice) {
            if ($invoice->status === Invoice::STATUS_PAID && $invoice->payments->isNotEmpty()) {
                $firstPaymentDate = $invoice->payments->min('payment_date');
                $daysDiff = $invoice->invoice_date->diffInDays($firstPaymentDate);
                $totalDays += $daysDiff;
                $paidInvoicesCount++;
            }
        }

        return $paidInvoicesCount > 0 ? round($totalDays / $paidInvoicesCount) : 0;
    }

    /**
     * Get quick stats for dashboard
     */
    public function getQuickStats(): array
    {
        return [
            'total_sales' => $this->customer->total_sales,
            'total_paid' => $this->customer->total_paid,
            'outstanding' => $this->customer->total_outstanding,
            'balance' => $this->customer->balance,
        ];
    }

    /**
     * Calculate payment efficiency percentage
     */
    public function calculatePaymentEfficiency(?array $financialSummary = null): float
    {
        if ($financialSummary === null) {
            $financialSummary = $this->getFinancialSummary();
        }

        $totalSales = $financialSummary['total_sales'] ?? 0;
        $totalPaid = $financialSummary['total_paid'] ?? 0;

        return $totalSales > 0 ? round(($totalPaid / $totalSales) * 100, 2) : 0;
    }

    /**
     * Calculate month-over-month growth rate
     *
     * @param  mixed|null  $monthlyTrends
     */
    public function calculateGrowthRate($monthlyTrends = null): float
    {
        if ($monthlyTrends === null) {
            $monthlyTrends = $this->getMonthlyTrends();
        }

        if ($monthlyTrends->count() < 2) {
            return 0;
        }

        $latest = $monthlyTrends->last();
        $previous = $monthlyTrends->slice(-2, 1)->first();

        if (! $previous || $previous->total_sales == 0) {
            return 0;
        }

        return round((($latest->total_sales - $previous->total_sales) / $previous->total_sales) * 100, 2);
    }
}

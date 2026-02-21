<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'booking_id',
        'invoice_number',
        'status',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'discount_amount',
        'discount_description',
        'total_amount',
        'issue_date',
        'due_date',
        'notes',
        'terms',
        'line_items',
        'created_by',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'issue_date' => 'date',
        'due_date' => 'date',
        'line_items' => 'array',
    ];

    /**
     * Invoice statuses
     */
    public const STATUSES = [
        'draft' => 'Draft',
        'issued' => 'Issued',
        'paid' => 'Paid',
        'cancelled' => 'Cancelled',
        'overdue' => 'Overdue',
    ];

    /**
     * Get the booking associated with this invoice
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the user who created this invoice
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Generate a unique invoice number
     */
    public static function generateInvoiceNumber(): string
    {
        $prefix = 'INV';
        $date = now()->format('Ymd');
        $count = self::whereDate('created_at', today())->count() + 1;
        
        return sprintf('%s-%s-%04d', $prefix, $date, $count);
    }

    /**
     * Calculate totals
     */
    public function calculateTotals(): void
    {
        $this->tax_amount = $this->subtotal * ($this->tax_rate / 100);
        $this->total_amount = $this->subtotal + $this->tax_amount - $this->discount_amount;
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'draft' => 'secondary',
            'issued' => 'info',
            'paid' => 'success',
            'cancelled' => 'danger',
            'overdue' => 'warning',
            default => 'secondary',
        };
    }

    /**
     * Check if invoice is editable
     */
    public function isEditable(): bool
    {
        return in_array($this->status, ['draft', 'issued']);
    }

    /**
     * Check if invoice is overdue
     */
    public function isOverdue(): bool
    {
        return $this->status === 'issued' && $this->due_date->isPast();
    }

    /**
     * Scope for issued invoices
     */
    public function scopeIssued($query)
    {
        return $query->where('status', 'issued');
    }

    /**
     * Scope for paid invoices
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope for overdue invoices
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'issued')
                     ->where('due_date', '<', now());
    }

    /**
     * Get formatted invoice number with prefix
     */
    public function getFormattedNumberAttribute(): string
    {
        return $this->invoice_number;
    }
}

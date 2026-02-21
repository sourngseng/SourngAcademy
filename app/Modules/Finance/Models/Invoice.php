<?php

namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Modules\Core\Models\Campus;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'campus_id',
        'student_id',
        'invoice_number',
        'total_amount',
        'paid_amount',
        'due_date',
        'status',
        'generated_by',
        'payment_notes',
        'payment_conditions',
    ];

    protected $casts = [
        'due_date'           => 'date',
        'total_amount'       => 'decimal:2',
        'paid_amount'        => 'decimal:2',
    ];

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}

<?php

namespace App\Modules\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampusPaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'campus_payment_methods';

    protected $fillable = [
        'campus_id',
        'type',
        'name',
        'icon',
        'provider_name',
        'account_number',
        'account_name',
        'currency',
        'qr_image',
        'gateway_link',
        'instructions',
        'color',
        'logo_image',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ── Available payment method types ───────────────────────────────────────
    const TYPES = [
        'bank_transfer' => [
            'label' => 'Bank Transfer',
            'icon'  => 'fas fa-university',
            'color' => '#1a6fc4',
        ],
        'qr_khqr' => [
            'label' => 'KHQR / QR Code',
            'icon'  => 'fas fa-qrcode',
            'color' => '#e63946',
        ],
        'aba_pay'   => ['label' => 'ABA Pay',   'icon' => 'fas fa-mobile-alt',  'color' => '#004fa3'],
        'acleda'    => ['label' => 'ACLEDA',    'icon' => 'fas fa-university',  'color' => '#c0392b'],
        'wing'      => ['label' => 'Wing',      'icon' => 'fas fa-dove',        'color' => '#ff6600'],
        'pi_pay'    => ['label' => 'Pi Pay',    'icon' => 'fas fa-circle',      'color' => '#6c3483'],
        'truemoney' => ['label' => 'TrueMoney', 'icon' => 'fas fa-money-check', 'color' => '#f39c12'],
        'paypal'    => ['label' => 'PayPal',    'icon' => 'fab fa-paypal',      'color' => '#003087'],
        'stripe'    => ['label' => 'Stripe',    'icon' => 'fab fa-stripe-s',    'color' => '#6772e5'],
        'wise'      => ['label' => 'Wise',      'icon' => 'fas fa-exchange-alt','color' => '#37517e'],
        'cash'      => ['label' => 'Cash',      'icon' => 'fas fa-money-bill-wave', 'color' => '#27ae60'],
        'other'     => ['label' => 'Other',     'icon' => 'fas fa-wallet',      'color' => '#7f8c8d'],
    ];

    // ── Relationships ─────────────────────────────────────────────────────────
    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────
    public function getTypeLabel(): string
    {
        return self::TYPES[$this->type]['label'] ?? ucfirst($this->type);
    }

    public function getDefaultIcon(): string
    {
        return self::TYPES[$this->type]['icon'] ?? 'fas fa-wallet';
    }

    public function getEffectiveIcon(): string
    {
        return $this->icon ?: $this->getDefaultIcon();
    }

    public function getDefaultColor(): string
    {
        return self::TYPES[$this->type]['color'] ?? '#7f8c8d';
    }

    public function getEffectiveColor(): string
    {
        return $this->color ?: $this->getDefaultColor();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }
}

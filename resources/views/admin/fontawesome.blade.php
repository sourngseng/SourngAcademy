@extends('admin.layouts.master')

@section('title', 'Font Awesome Icons Reference')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="fas fa-icons"></i> Font Awesome Icons</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Font Awesome Icons</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Icon Categories -->
        <div class="row">
            <!-- Solid Icons -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><i class="fas fa-check-circle"></i></h3>
                        <p>Solid Icons</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-icons"></i>
                    </div>
                    <a href="#solid-icons" class="small-box-footer">
                        View Examples <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <!-- Regular Icons -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><i class="far fa-check-circle"></i></h3>
                        <p>Regular Icons</p>
                    </div>
                    <div class="icon">
                        <i class="far fa-circle"></i>
                    </div>
                    <a href="#regular-icons" class="small-box-footer">
                        View Examples <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <!-- Brand Icons -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><i class="fab fa-facebook"></i></h3>
                        <p>Brand Icons</p>
                    </div>
                    <div class="icon">
                        <i class="fab fa-github"></i>
                    </div>
                    <a href="#brand-icons" class="small-box-footer">
                        View Examples <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <!-- Light Icons -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3><i class="fal fa-heart"></i></h3>
                        <p>Light Icons (Pro)</p>
                    </div>
                    <div class="icon">
                        <i class="fal fa-star"></i>
                    </div>
                    <a href="#light-icons" class="small-box-footer">
                        Pro Only <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Common Icons -->
        <div class="card card-primary card-outline" id="solid-icons">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-icons"></i> Common Solid Icons</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-home"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">fa-home</span>
                                <span class="info-box-number"><code>fas</code></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-user"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">fa-user</span>
                                <span class="info-box-number"><code>fas</code></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-cog"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">fa-cog</span>
                                <span class="info-box-number"><code>fas</code></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-danger"><i class="fas fa-heart"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">fa-heart</span>
                                <span class="info-box-number"><code>fas</code></span>
                            </div>
                        </div>
                    </div>
                </div>

                <h5 class="mt-4"><i class="fas fa-hotel"></i> Hotel & Tourism Icons</h5>
                <div class="row">
                    @foreach([
                        ['icon' => 'fa-hotel', 'name' => 'Hotel', 'color' => 'primary'],
                        ['icon' => 'fa-bed', 'name' => 'Bed', 'color' => 'success'],
                        ['icon' => 'fa-door-open', 'name' => 'Door Open', 'color' => 'info'],
                        ['icon' => 'fa-key', 'name' => 'Key', 'color' => 'warning'],
                        ['icon' => 'fa-calendar-check', 'name' => 'Calendar Check', 'color' => 'primary'],
                        ['icon' => 'fa-sign-in-alt', 'name' => 'Check In', 'color' => 'success'],
                        ['icon' => 'fa-sign-out-alt', 'name' => 'Check Out', 'color' => 'danger'],
                        ['icon' => 'fa-wifi', 'name' => 'WiFi', 'color' => 'info'],
                        ['icon' => 'fa-swimming-pool', 'name' => 'Pool', 'color' => 'primary'],
                        ['icon' => 'fa-utensils', 'name' => 'Restaurant', 'color' => 'warning'],
                        ['icon' => 'fa-parking', 'name' => 'Parking', 'color' => 'secondary'],
                        ['icon' => 'fa-dumbbell', 'name' => 'Gym', 'color' => 'danger']
                    ] as $item)
                    <div class="col-md-2 col-sm-4 col-6 mb-3">
                        <div class="text-center p-3 border rounded">
                            <i class="fas {{ $item['icon'] }} fa-3x text-{{ $item['color'] }} mb-2"></i>
                            <p class="mb-0"><small>{{ $item['name'] }}</small></p>
                            <code class="text-xs">{{ $item['icon'] }}</code>
                        </div>
                    </div>
                    @endforeach
                </div>

                <h5 class="mt-4"><i class="fas fa-chart-line"></i> Business & Finance Icons</h5>
                <div class="row">
                    @foreach([
                        ['icon' => 'fa-dollar-sign', 'name' => 'Dollar'],
                        ['icon' => 'fa-credit-card', 'name' => 'Credit Card'],
                        ['icon' => 'fa-chart-pie', 'name' => 'Chart Pie'],
                        ['icon' => 'fa-chart-bar', 'name' => 'Chart Bar'],
                        ['icon' => 'fa-chart-line', 'name' => 'Chart Line'],
                        ['icon' => 'fa-receipt', 'name' => 'Receipt'],
                        ['icon' => 'fa-file-invoice-dollar', 'name' => 'Invoice'],
                        ['icon' => 'fa-wallet', 'name' => 'Wallet'],
                        ['icon' => 'fa-coins', 'name' => 'Coins'],
                        ['icon' => 'fa-money-bill-wave', 'name' => 'Money'],
                        ['icon' => 'fa-piggy-bank', 'name' => 'Piggy Bank'],
                        ['icon' => 'fa-calculator', 'name' => 'Calculator']
                    ] as $item)
                    <div class="col-md-2 col-sm-4 col-6 mb-3">
                        <div class="text-center p-3 border rounded">
                            <i class="fas {{ $item['icon'] }} fa-2x text-success mb-2"></i>
                            <p class="mb-0"><small>{{ $item['name'] }}</small></p>
                            <code class="text-xs">{{ $item['icon'] }}</code>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Brand Icons -->
        <div class="card card-success card-outline" id="brand-icons">
            <div class="card-header">
                <h3 class="card-title"><i class="fab fa-font-awesome"></i> Brand Icons</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach([
                        ['icon' => 'fa-facebook', 'name' => 'Facebook', 'color' => '1877f2'],
                        ['icon' => 'fa-twitter', 'name' => 'Twitter', 'color' => '1da1f2'],
                        ['icon' => 'fa-instagram', 'name' => 'Instagram', 'color' => 'e4405f'],
                        ['icon' => 'fa-youtube', 'name' => 'YouTube', 'color' => 'ff0000'],
                        ['icon' => 'fa-linkedin', 'name' => 'LinkedIn', 'color' => '0077b5'],
                        ['icon' => 'fa-github', 'name' => 'GitHub', 'color' => '333333'],
                        ['icon' => 'fa-google', 'name' => 'Google', 'color' => '4285f4'],
                        ['icon' => 'fa-whatsapp', 'name' => 'WhatsApp', 'color' => '25d366'],
                        ['icon' => 'fa-telegram', 'name' => 'Telegram', 'color' => '0088cc'],
                        ['icon' => 'fa-stripe', 'name' => 'Stripe', 'color' => '635bff'],
                        ['icon' => 'fa-paypal', 'name' => 'PayPal', 'color' => '0070ba'],
                        ['icon' => 'fa-cc-visa', 'name' => 'Visa', 'color' => '1a1f71']
                    ] as $item)
                    <div class="col-md-2 col-sm-4 col-6 mb-3">
                        <div class="text-center p-3 border rounded">
                            <i class="fab {{ $item['icon'] }} fa-3x mb-2" style="color: #{{ $item['color'] }}"></i>
                            <p class="mb-0"><small>{{ $item['name'] }}</small></p>
                            <code class="text-xs">{{ $item['icon'] }}</code>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Usage Instructions -->
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle"></i> How to Use</h3>
            </div>
            <div class="card-body">
                <h5>Icon Types:</h5>
                <ul>
                    <li><code>fas</code> - Solid (default style, most commonly used)</li>
                    <li><code>far</code> - Regular (outlined icons)</li>
                    <li><code>fab</code> - Brands (company/brand logos)</li>
                    <li><code>fal</code> - Light (Pro version only)</li>
                </ul>

                <h5 class="mt-4">Basic Usage:</h5>
                <pre class="bg-light p-3 rounded"><code>&lt;i class="fas fa-home"&gt;&lt;/i&gt;  <!-- Solid home icon -->
&lt;i class="far fa-heart"&gt;&lt;/i&gt;  <!-- Regular heart icon -->
&lt;i class="fab fa-facebook"&gt;&lt;/i&gt;  <!-- Facebook brand icon --></code></pre>

                <h5 class="mt-4">Icon Sizes:</h5>
                <div class="mb-3">
                    <i class="fas fa-home fa-xs"></i> <code>fa-xs</code> (Extra Small)
                    <i class="fas fa-home fa-sm ml-3"></i> <code>fa-sm</code> (Small)
                    <i class="fas fa-home ml-3"></i> <code>(default)</code> (Normal)
                    <i class="fas fa-home fa-lg ml-3"></i> <code>fa-lg</code> (Large)
                    <i class="fas fa-home fa-2x ml-3"></i> <code>fa-2x</code> (2x)
                    <i class="fas fa-home fa-3x ml-3"></i> <code>fa-3x</code> (3x)
                </div>

                <h5 class="mt-4">Icon Colors:</h5>
                <div class="mb-3">
                    <i class="fas fa-heart fa-2x text-danger"></i> <code>text-danger</code>
                    <i class="fas fa-star fa-2x text-warning ml-2"></i> <code>text-warning</code>
                    <i class="fas fa-check fa-2x text-success ml-2"></i> <code>text-success</code>
                    <i class="fas fa-info-circle fa-2x text-info ml-2"></i> <code>text-info</code>
                    <i class="fas fa-cog fa-2x text-primary ml-2"></i> <code>text-primary</code>
                </div>

                <h5 class="mt-4">Spinning Icons:</h5>
                <div class="mb-3">
                    <i class="fas fa-spinner fa-spin fa-2x"></i> <code>fa-spin</code>
                    <i class="fas fa-circle-notch fa-spin fa-2x ml-3"></i> <code>fa-spin</code>
                    <i class="fas fa-cog fa-spin fa-2x ml-3"></i> <code>fa-spin</code>
                </div>

                <h5 class="mt-4">Official Resources:</h5>
                <ul>
                    <li><a href="https://fontawesome.com/icons" target="_blank">Browse All Icons <i class="fas fa-external-link-alt"></i></a></li>
                    <li><a href="https://fontawesome.com/docs" target="_blank">Documentation <i class="fas fa-book"></i></a></li>
                    <li><a href="https://fontawesome.com/search" target="_blank">Icon Search <i class="fas fa-search"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
</section>
@endsection

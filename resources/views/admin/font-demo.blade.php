@extends('admin.layouts.master')

@section('title', 'Font Awesome + Khmer Fonts Demo')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="fas fa-font"></i> Font Awesome + Khmer Fonts Demo</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i> Home</a></li>
                    <li class="breadcrumb-item active">Font Demo</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Font Switcher -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-palette"></i> Switch Font Style</h3>
                    </div>
                    <div class="card-body">
                        <div class="btn-group btn-group-lg" role="group">
                            <button type="button" class="btn btn-outline-primary" onclick="setFont('')">
                                <i class="fas fa-font"></i> System Default
                            </button>
                            <button type="button" class="btn btn-outline-success" onclick="setFont('hanuman')">
                                <i class="fas fa-text-height"></i> Hanuman (ហនុមាន)
                            </button>
                            <button type="button" class="btn btn-outline-info" onclick="setFont('kantumruy')">
                                <i class="fas fa-text-width"></i> Kantumruy Pro (កន្ទុំរុយ)
                            </button>
                        </div>
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-info-circle"></i> Click buttons above to test different fonts. Icons remain unchanged!
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Icons with Khmer Text Examples -->
        <div class="row">
            <!-- Hotel Management -->
            <div class="col-lg-6">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-hotel"></i> គ្រប់គ្រងសណ្ឋាគារ (Hotel Management)</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <i class="fas fa-bed text-primary"></i>
                                <strong>បន្ទប់ (Room):</strong> គ្រប់គ្រងបន្ទប់និងប្រភេទបន្ទប់
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-calendar-check text-success"></i>
                                <strong>ការកក់ (Booking):</strong> ប្រព័ន្ធកក់បន្ទប់ថ្មី
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-users text-info"></i>
                                <strong>ភ្ញៀវ (Guests):</strong> គ្រប់គ្រងព័ត៌មានភ្ញៀវ
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-dollar-sign text-warning"></i>
                                <strong>ការទូទាត់ (Payment):</strong> ប្រព័ន្ធទូទាត់ប្រាក់
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-chart-line text-danger"></i>
                                <strong>របាយការណ៍ (Reports):</strong> តារាងពិន្ទុនិងសង្ខ្យា
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Amenities & Services -->
            <div class="col-lg-6">
                <div class="card card-success card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-concierge-bell"></i> សេវាកម្ម (Services & Amenities)</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <i class="fas fa-wifi text-primary"></i>
                                <strong>WiFi:</strong> អ៊ីនធឺណិតឥតគិតថ្លៃ
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-swimming-pool text-info"></i>
                                <strong>ស្រះហែលទឹក (Pool):</strong> ស្រះហែលទឹកទំនើប
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-utensils text-warning"></i>
                                <strong>ភោជនីយដ្ឋាន (Restaurant):</strong> អាហារឆ្ងាញ់
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-parking text-secondary"></i>
                                <strong>ចំណតរថយន្ត (Parking):</strong> កន្លែងចតសុវត្ថិភាព
                            </li>
                            <li class="list-group-item">
                                <i class="fas fa-dumbbell text-danger"></i>
                                <strong>ហាលកាយសម្ព័ន្ធ (Gym):</strong> ឧបករណ៍ទំនើប
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards with Icons -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>150</h3>
                        <p>ការកក់សរុប (Total Bookings)</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        មើលព័ត៌មានបន្ថែម <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>53</h3>
                        <p>ភ្ញៀវថ្មី (New Guests)</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        មើលព័ត៌មានបន្ថែម <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>$15,420</h3>
                        <p>ប្រាក់ចំណូល (Revenue)</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        មើលព័ត៌មានបន្ថែម <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>65%</h3>
                        <p>អត្រាកក់ (Occupancy Rate)</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <a href="#" class="small-box-footer">
                        មើលព័ត៌មានបន្ថែម <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Icon Grid -->
        <div class="row">
            <div class="col-12">
                <div class="card card-warning card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-icons"></i> Font Awesome Icons + ពុម្ពអក្សរខ្មែរ (Khmer Text)</h3>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            @php
                            $icons = [
                                ['icon' => 'fa-home', 'text' => 'ទំព័រដើម', 'eng' => 'Home'],
                                ['icon' => 'fa-hotel', 'text' => 'សណ្ឋាគារ', 'eng' => 'Hotel'],
                                ['icon' => 'fa-bed', 'text' => 'គ្រែ', 'eng' => 'Bed'],
                                ['icon' => 'fa-key', 'text' => 'សោ', 'eng' => 'Key'],
                                ['icon' => 'fa-door-open', 'text' => 'ទ្វារ', 'eng' => 'Door'],
                                ['icon' => 'fa-calendar', 'text' => 'ប្រតិទិន', 'eng' => 'Calendar'],
                                ['icon' => 'fa-user', 'text' => 'អ្នកប្រើប្រាស់', 'eng' => 'User'],
                                ['icon' => 'fa-users', 'text' => 'អ្នកប្រើប្រាស់', 'eng' => 'Users'],
                                ['icon' => 'fa-cog', 'text' => 'ការកំណត់', 'eng' => 'Settings'],
                                ['icon' => 'fa-chart-bar', 'text' => 'ក្រាហ្វ', 'eng' => 'Chart'],
                                ['icon' => 'fa-dollar-sign', 'text' => 'ប្រាក់', 'eng' => 'Money'],
                                ['icon' => 'fa-credit-card', 'text' => 'កាតឥណទាន', 'eng' => 'Card'],
                                ['icon' => 'fa-bell', 'text' => 'ជូនដំណឹង', 'eng' => 'Notification'],
                                ['icon' => 'fa-envelope', 'text' => 'សំបុត្រ', 'eng' => 'Mail'],
                                ['icon' => 'fa-star', 'text' => 'ផ្កាយ', 'eng' => 'Star'],
                                ['icon' => 'fa-heart', 'text' => 'បេះដូង', 'eng' => 'Heart'],
                                ['icon' => 'fa-check', 'text' => 'ធីក', 'eng' => 'Check'],
                                ['icon' => 'fa-times', 'text' => 'បិទ', 'eng' => 'Close'],
                                ['icon' => 'fa-search', 'text' => 'ស្វែងរក', 'eng' => 'Search'],
                                ['icon' => 'fa-trash', 'text' => 'លុប', 'eng' => 'Delete'],
                                ['icon' => 'fa-edit', 'text' => 'កែប្រែ', 'eng' => 'Edit'],
                                ['icon' => 'fa-plus', 'text' => 'បន្ថែម', 'eng' => 'Add'],
                                ['icon' => 'fa-minus', 'text' => 'ដក', 'eng' => 'Minus'],
                                ['icon' => 'fa-download', 'text' => 'ទាញយក', 'eng' => 'Download'],
                            ];
                            @endphp

                            @foreach($icons as $item)
                            <div class="col-md-2 col-sm-3 col-4 mb-4">
                                <div class="text-center p-3 border rounded hover-shadow">
                                    <i class="fas {{ $item['icon'] }} fa-3x text-primary mb-2"></i>
                                    <p class="mb-0 font-weight-bold">{{ $item['text'] }}</p>
                                    <small class="text-muted">{{ $item['eng'] }}</small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Example -->
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-edit"></i> តំរង់គំរូ (Form Example)</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label><i class="fas fa-user"></i> ឈ្មោះអ្នកប្រើប្រាស់ (Username)</label>
                            <input type="text" class="form-control" placeholder="បញ្ចូលឈ្មោះ...">
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-envelope"></i> អ៊ីមែល (Email)</label>
                            <input type="email" class="form-control" placeholder="example@hotel.com">
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-phone"></i> លេខទូរស័ព្ទ (Phone Number)</label>
                            <input type="tel" class="form-control" placeholder="012 345 678">
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-comment"></i> មតិយោបល់ (Comment)</label>
                            <textarea class="form-control" rows="3" placeholder="សរសេរមតិយោបល់របស់អ្នក..."></textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> រក្សាទុក (Save)
                        </button>
                        <button type="reset" class="btn btn-secondary">
                            <i class="fas fa-undo"></i> កំណត់ឡើងវិញ (Reset)
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Examples -->
        <div class="row">
            <div class="col-12">
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-check"></i> ជោគជ័យ! (Success!)</h5>
                    ប្រតិបត្តិការបានបញ្ចប់ដោយជោគជ័យ។ (Operation completed successfully.)
                </div>
                <div class="alert alert-info alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-info"></i> ព័ត៌មាន! (Information!)</h5>
                    នេះគឺជាសារព័ត៌មានសម្រាប់អ្នក។ (This is an informational message for you.)
                </div>
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-exclamation-triangle"></i> ការព្រមាន! (Warning!)</h5>
                    សូមពិនិត្យព័ត៌មានរបស់អ្នកម្តងទៀត។ (Please check your information again.)
                </div>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-ban"></i> កំហុស! (Error!)</h5>
                    មានបញ្ហាកើតឡើង។ សូមព្យាយាមម្តងទៀត។ (An error occurred. Please try again.)
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .hover-shadow {
        transition: all 0.3s;
    }
    .hover-shadow:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        transform: translateY(-2px);
    }
</style>
@endpush

@push('scripts')
<script>
    function setFont(fontName) {
        const body = document.body;
        if (fontName) {
            body.setAttribute('data-khmer-font', fontName);
            // Show success message
            toastr.success('Changed to ' + (fontName === 'hanuman' ? 'Hanuman (ហនុមាន)' : 'Kantumruy Pro (កន្ទុំរុយ)'));
        } else {
            body.removeAttribute('data-khmer-font');
            toastr.info('Switched to System Default Font');
        }
    }

    // Highlight current font
    $(document).ready(function() {
        const currentFont = $('body').attr('data-khmer-font');
        if (currentFont === 'hanuman') {
            $('.btn-outline-success').addClass('active');
        } else if (currentFont === 'kantumruy') {
            $('.btn-outline-info').addClass('active');
        } else {
            $('.btn-outline-primary').addClass('active');
        }
    });
</script>
@endpush

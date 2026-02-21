@extends('admin.layouts.master')

@section('title', 'Guest Reviews & Ratings')

@push('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('backend')}}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{asset('backend')}}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{asset('backend')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    
    <style>
        .rating-overview {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 30px;
            color: white;
        }
        .big-rating {
            font-size: 64px;
            font-weight: 700;
            line-height: 1;
        }
        .rating-bar {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }
        .rating-bar .stars {
            width: 80px;
            font-size: 13px;
        }
        .rating-bar .progress {
            flex: 1;
            height: 8px;
            margin: 0 10px;
            border-radius: 4px;
            background: rgba(255,255,255,0.2);
        }
        .rating-bar .progress-bar {
            background: #ffc107;
            border-radius: 4px;
        }
        .rating-bar .count {
            width: 50px;
            text-align: right;
            font-size: 13px;
        }
        
        .stat-card {
            border-radius: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }
        .stat-card .icon-box {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        .stat-card .stat-value {
            font-size: 28px;
            font-weight: 700;
        }
        
        .review-detail-rating {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .review-detail-rating:last-child {
            border-bottom: none;
        }
        .review-detail-rating .label {
            color: #666;
        }
        .review-detail-rating .stars {
            color: #ffc107;
        }
    </style>
@endpush

@section('page-title')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-star"></i> Guest Reviews & Ratings</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Reviews</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('content')
    <div class="row">
        <!-- Rating Overview Card -->
        <div class="col-lg-4">
            <div class="rating-overview mb-4">
                <div class="text-center mb-4">
                    <div class="big-rating">{{ number_format($stats['avg_rating'], 1) }}</div>
                    <div class="my-2">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= round($stats['avg_rating']))
                                <i class="fas fa-star text-warning"></i>
                            @else
                                <i class="far fa-star text-white-50"></i>
                            @endif
                        @endfor
                    </div>
                    <p class="mb-0 opacity-75">Based on {{ $stats['published_reviews'] }} reviews</p>
                </div>
                
                <!-- Rating Distribution -->
                @foreach($ratingDistribution as $rating => $data)
                    <div class="rating-bar">
                        <span class="stars">
                            {{ $rating }} <i class="fas fa-star text-warning"></i>
                        </span>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: {{ $data['percentage'] }}%"></div>
                        </div>
                        <span class="count">{{ $data['count'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="col-lg-8">
            <div class="row">
                <div class="col-md-4 col-6">
                    <div class="card stat-card bg-gradient-primary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-1 text-white-50">Total Reviews</p>
                                    <h3 class="stat-value text-white mb-0">{{ $stats['total_reviews'] }}</h3>
                                </div>
                                <div class="icon-box bg-white-10">
                                    <i class="fas fa-comments text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-6">
                    <div class="card stat-card bg-gradient-warning">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-1 text-white-50">Pending</p>
                                    <h3 class="stat-value text-white mb-0">{{ $stats['pending_reviews'] }}</h3>
                                </div>
                                <div class="icon-box bg-white-10">
                                    <i class="fas fa-clock text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-6">
                    <div class="card stat-card bg-gradient-success">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-1 text-white-50">5-Star Reviews</p>
                                    <h3 class="stat-value text-white mb-0">{{ $stats['five_star_count'] }}</h3>
                                </div>
                                <div class="icon-box bg-white-10">
                                    <i class="fas fa-star text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-filter"></i> Filters</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" id="filter_status">
                            <option value="">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="published">Published</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Rating</label>
                        <select class="form-control" id="filter_rating">
                            <option value="">All Ratings</option>
                            <option value="5">5 Stars</option>
                            <option value="4">4 Stars</option>
                            <option value="3">3 Stars</option>
                            <option value="2">2 Stars</option>
                            <option value="1">1 Star</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-info btn-block" id="applyFilters">
                            <i class="fas fa-search"></i> Apply Filters
                        </button>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-secondary btn-block" id="resetFilters">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews List -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">All Reviews</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped data-table">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Guest</th>
                        <th>Booking</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Status</th>
                        <th>Response</th>
                        <th>Date</th>
                        <th width="150">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <!-- View Review Modal -->
    <div class="modal fade" id="viewReviewModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title text-white"><i class="fas fa-star"></i> Review Details</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="reviewDetailContent">
                    <!-- Content loaded via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reply Modal -->
    <div class="modal fade" id="replyModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title text-white"><i class="fas fa-reply"></i> Reply to Review</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="replyForm">
                    <div class="modal-body">
                        <input type="hidden" id="reply_review_id">
                        <div class="form-group">
                            <label>Your Response <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="admin_response" name="admin_response" rows="5" required
                                      placeholder="Write a professional response to this review..."></textarea>
                        </div>
                        <div class="alert alert-light">
                            <strong><i class="fas fa-info-circle"></i> Tips:</strong>
                            <ul class="mb-0 pl-4">
                                <li>Thank the guest for their feedback</li>
                                <li>Address any specific concerns mentioned</li>
                                <li>Keep the response professional and courteous</li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="submitReply">
                            <i class="fas fa-paper-plane"></i> Send Response
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h4 class="modal-title text-white"><i class="fas fa-times"></i> Reject Review</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="rejectForm">
                    <div class="modal-body">
                        <input type="hidden" id="reject_review_id">
                        <div class="form-group">
                            <label>Rejection Reason <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="4" required
                                      placeholder="Explain why this review is being rejected..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger" id="confirmReject">
                            <i class="fas fa-times"></i> Confirm Rejection
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- DataTables & Plugins -->
    <script src="{{asset('backend')}}/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{{asset('backend')}}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{asset('backend')}}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="{{asset('backend')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

    <script type="text/javascript">
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.reviews.index') }}",
                    data: function (d) {
                        d.status = $('#filter_status').val();
                        d.rating = $('#filter_rating').val();
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'guest', name: 'user.name'},
                    {data: 'booking_info', name: 'booking_id'},
                    {data: 'rating_stars', name: 'overall_rating'},
                    {data: 'review_preview', name: 'review_text'},
                    {data: 'status_badge', name: 'status'},
                    {data: 'has_response', name: 'admin_response', orderable: false},
                    {data: 'created', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            // Apply filters
            $('#applyFilters').click(function(){
                table.draw();
            });

            // Reset filters
            $('#resetFilters').click(function(){
                $('#filter_status').val('');
                $('#filter_rating').val('');
                table.draw();
            });

            // View Review
            $('body').on('click', '.viewReview', function () {
                var id = $(this).data('id');
                $.get("{{ route('admin.reviews.index') }}/" + id, function (data) {
                    var html = `
                        <div class="row">
                            <div class="col-md-4">
                                <div class="text-center mb-3">
                                    ${data.guest.profile_image 
                                        ? `<img src="${data.guest.profile_image}" class="rounded-circle mb-2" width="80" height="80" style="object-fit:cover;">` 
                                        : `<div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-2" style="width:80px;height:80px;font-size:32px;">${data.guest.name.charAt(0).toUpperCase()}</div>`
                                    }
                                    <h5 class="mb-1">${data.guest.name}</h5>
                                    <small class="text-muted">${data.guest.email}</small>
                                </div>
                                
                                <div class="card">
                                    <div class="card-header"><strong>Ratings Breakdown</strong></div>
                                    <div class="card-body">
                                        <div class="review-detail-rating">
                                            <span class="label">Overall</span>
                                            <span class="stars">${'★'.repeat(data.ratings.overall)}${'☆'.repeat(5-data.ratings.overall)}</span>
                                        </div>
                                        ${data.ratings.staff ? `<div class="review-detail-rating"><span class="label">Staff</span><span class="stars">${'★'.repeat(data.ratings.staff)}${'☆'.repeat(5-data.ratings.staff)}</span></div>` : ''}
                                        ${data.ratings.cleanliness ? `<div class="review-detail-rating"><span class="label">Cleanliness</span><span class="stars">${'★'.repeat(data.ratings.cleanliness)}${'☆'.repeat(5-data.ratings.cleanliness)}</span></div>` : ''}
                                        ${data.ratings.comfort ? `<div class="review-detail-rating"><span class="label">Comfort</span><span class="stars">${'★'.repeat(data.ratings.comfort)}${'☆'.repeat(5-data.ratings.comfort)}</span></div>` : ''}
                                        ${data.ratings.location ? `<div class="review-detail-rating"><span class="label">Location</span><span class="stars">${'★'.repeat(data.ratings.location)}${'☆'.repeat(5-data.ratings.location)}</span></div>` : ''}
                                        ${data.ratings.value ? `<div class="review-detail-rating"><span class="label">Value</span><span class="stars">${'★'.repeat(data.ratings.value)}${'☆'.repeat(5-data.ratings.value)}</span></div>` : ''}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <strong>Booking Info:</strong><br>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar"></i> ${data.booking.check_in} - ${data.booking.check_out}<br>
                                        <i class="fas fa-bed"></i> Room(s): ${data.booking.rooms}
                                    </small>
                                </div>
                                
                                ${data.title ? `<h5>"${data.title}"</h5>` : ''}
                                <p class="mb-3">${data.review_text}</p>
                                
                                ${data.pros && data.pros.length ? `
                                    <div class="mb-2">
                                        <strong class="text-success"><i class="fas fa-thumbs-up"></i> What they liked:</strong>
                                        <ul class="mb-0">${data.pros.map(p => `<li>${p}</li>`).join('')}</ul>
                                    </div>
                                ` : ''}
                                
                                ${data.cons && data.cons.length ? `
                                    <div class="mb-2">
                                        <strong class="text-danger"><i class="fas fa-thumbs-down"></i> What could improve:</strong>
                                        <ul class="mb-0">${data.cons.map(c => `<li>${c}</li>`).join('')}</ul>
                                    </div>
                                ` : ''}
                                
                                <hr>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge badge-${data.status === 'published' ? 'success' : data.status === 'pending' ? 'warning' : 'danger'}">${data.status.toUpperCase()}</span>
                                    <small class="text-muted">Submitted on ${data.created_at}</small>
                                </div>
                                
                                ${data.admin_response ? `
                                    <div class="alert alert-light mt-3">
                                        <strong><i class="fas fa-reply text-primary"></i> Hotel Response:</strong>
                                        <p class="mb-1">${data.admin_response}</p>
                                        <small class="text-muted">By ${data.responder} on ${data.responded_at}</small>
                                    </div>
                                ` : ''}
                                
                                ${data.rejection_reason ? `
                                    <div class="alert alert-danger mt-3">
                                        <strong><i class="fas fa-times-circle"></i> Rejection Reason:</strong>
                                        <p class="mb-0">${data.rejection_reason}</p>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    `;
                    $('#reviewDetailContent').html(html);
                    $('#viewReviewModal').modal('show');
                });
            });

            // Publish/Reject
            $('body').on('click', '.changeStatus', function () {
                var id = $(this).data('id');
                var action = $(this).data('action');
                
                if (action === 'publish') {
                    Swal.fire({
                        title: 'Publish Review?',
                        text: "This review will be visible to the public.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, publish it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.post("{{ url('admin/reviews') }}/" + id + "/status", { action: 'publish' }, function(data) {
                                table.draw();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Published!',
                                    text: data.success,
                                    timer: 2000,
                                    showConfirmButton: false,
                                    toast: true,
                                    position: 'top-end'
                                });
                            });
                        }
                    });
                } else {
                    $('#reject_review_id').val(id);
                    $('#rejection_reason').val('');
                    $('#rejectModal').modal('show');
                }
            });

            // Confirm rejection
            $('#rejectForm').submit(function(e) {
                e.preventDefault();
                var id = $('#reject_review_id').val();
                var reason = $('#rejection_reason').val();
                
                $.post("{{ url('admin/reviews') }}/" + id + "/status", { 
                    action: 'reject',
                    rejection_reason: reason 
                }, function(data) {
                    $('#rejectModal').modal('hide');
                    table.draw();
                    Swal.fire({
                        icon: 'success',
                        title: 'Rejected!',
                        text: data.success,
                        timer: 2000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                });
            });

            // Reply to review
            $('body').on('click', '.replyReview', function () {
                var id = $(this).data('id');
                $('#reply_review_id').val(id);
                $('#admin_response').val('');
                $('#replyModal').modal('show');
            });

            // Submit reply
            $('#replyForm').submit(function(e) {
                e.preventDefault();
                var id = $('#reply_review_id').val();
                var response = $('#admin_response').val();
                
                $('#submitReply').html('<i class="fas fa-spinner fa-spin"></i> Sending...');
                
                $.post("{{ url('admin/reviews') }}/" + id + "/respond", { 
                    admin_response: response 
                }, function(data) {
                    $('#replyModal').modal('hide');
                    $('#submitReply').html('<i class="fas fa-paper-plane"></i> Send Response');
                    table.draw();
                    Swal.fire({
                        icon: 'success',
                        title: 'Response Sent!',
                        text: data.success,
                        timer: 2000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                }).fail(function() {
                    $('#submitReply').html('<i class="fas fa-paper-plane"></i> Send Response');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong. Please try again.',
                    });
                });
            });
        });
    </script>
@endpush

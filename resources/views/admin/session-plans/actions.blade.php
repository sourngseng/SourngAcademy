<div class="btn-group">
    <a href="{{ route('admin.session-plans.edit', $id) }}" class="btn btn-warning btn-sm shadow-sm"><i class="fas fa-edit"></i></a>
    <button type="button" class="btn btn-danger btn-sm delete-btn shadow-sm" data-id="{{ $id }}" data-url="{{ route('admin.session-plans.destroy', $id) }}">
        <i class="fas fa-trash"></i>
    </button>
</div>

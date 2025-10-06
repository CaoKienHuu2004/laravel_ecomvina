{{-- resources/views/admin/ai/index.blade.php --}}
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">AI Training Management</h3>
                    <a href="{{ route('admin.ai.intents.create') }}" class="btn btn-primary">
                        Add New Intent
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Intent</th>
                                    <th scope="col">Training Samples</th>
                                    <th scope="col">Responses</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($intents as $intent)
                                    <tr>
                                        <td>{{ $intent->name }}</td>
                                        <td>{{ $intent->training_data_count }}</td>
                                        <td>{{ $intent->responses_count }}</td>
                                        <td>
                                            <a href="{{ route('admin.ai.intents.show', $intent->id) }}"
                                               class="btn btn-sm btn-info">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            No intents found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

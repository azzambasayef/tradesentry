@extends('layouts.app')
@section('title', 'Admin Dashboard - TradeSentry')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-secondary pb-3">
    <h2 class="text-warning m-0"><i class="fas fa-shield-alt me-2"></i> Admin Command Center</h2>
</div>

@if(session('success'))
    <div class="alert alert-success bg-success text-white border-0">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger bg-danger text-white border-0">{{ session('error') }}</div>
@endif

<!-- Tabs Nav -->
<ul class="nav nav-tabs border-secondary mb-4" id="adminTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active fw-bold text-light" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab"><i class="fas fa-users me-2"></i>Users ({{ count($users) }})</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold text-light" id="ports-tab" data-bs-toggle="tab" data-bs-target="#ports" type="button" role="tab"><i class="fas fa-ship me-2"></i>Port Datasets ({{ count($ports) }})</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold text-light" id="articles-tab" data-bs-toggle="tab" data-bs-target="#articles" type="button" role="tab"><i class="fas fa-file-alt me-2"></i>Internal Articles ({{ count($articles) }})</button>
    </li>
</ul>

<!-- Tabs Content -->
<div class="tab-content" id="adminTabsContent">
    
    <!-- USERS TAB -->
    <div class="tab-pane fade show active" id="users" role="tabpanel">
        <div class="card shadow">
            <div class="card-body p-0">
                <table class="table table-dark table-hover m-0">
                    <thead class="table-active">
                        <tr>
                            <th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }} {!! $user->id === auth()->id() ? '<span class="badge bg-primary ms-1">You</span>' : '' !!}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <form action="{{ route('admin.users.role', $user->id) }}" method="POST" class="d-inline-flex">
                                    @csrf @method('PUT')
                                    <select name="role" class="form-select form-select-sm bg-dark text-light border-secondary" onchange="this.form.submit()" {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                        <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this user?')"><i class="fas fa-trash"></i></button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- PORTS TAB -->
    <div class="tab-pane fade" id="ports" role="tabpanel">
        <div class="mb-3 text-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPortModal"><i class="fas fa-plus me-2"></i>Add Port</button>
        </div>
        <div class="card shadow">
            <div class="card-body p-0">
                <table class="table table-dark table-hover m-0">
                    <thead class="table-active">
                        <tr>
                            <th>Name</th><th>Country</th><th>Lat</th><th>Lng</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ports as $port)
                        <tr>
                            <td>{{ $port->name }}</td>
                            <td>{{ $port->country_name }}</td>
                            <td>{{ $port->lat }}</td>
                            <td>{{ $port->lng }}</td>
                            <td>
                                <form action="{{ route('admin.ports.delete', $port->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this port?')"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ARTICLES TAB -->
    <div class="tab-pane fade" id="articles" role="tabpanel">
        <div class="mb-3 text-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addArticleModal"><i class="fas fa-plus me-2"></i>Add Article</button>
        </div>
        <div class="card shadow">
            <div class="card-body p-0">
                <table class="table table-dark table-hover m-0">
                    <thead class="table-active">
                        <tr>
                            <th>Title</th><th>Category</th><th>Status</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($articles as $article)
                        <tr>
                            <td>{{ $article->title }}</td>
                            <td>{{ $article->category ?? '-' }}</td>
                            <td>
                                @if($article->is_published) <span class="badge bg-success">Published</span>
                                @else <span class="badge bg-warning text-dark">Draft</span> @endif
                            </td>
                            <td>
                                <form action="{{ route('admin.articles.delete', $article->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete article?')"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Add Port Modal -->
<div class="modal fade" id="addPortModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-light border-secondary">
      <form action="{{ route('admin.ports.store') }}" method="POST">
          @csrf
          <div class="modal-header border-secondary">
            <h5 class="modal-title">Add New Port</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
             <div class="mb-3">
                 <label>Port Name</label>
                 <input type="text" name="name" class="form-control bg-secondary text-light border-0" required>
             </div>
             <div class="mb-3">
                 <label>Country Name</label>
                 <input type="text" name="country_name" class="form-control bg-secondary text-light border-0" required>
             </div>
             <div class="row">
                 <div class="col-6 mb-3">
                     <label>Latitude</label>
                     <input type="text" name="lat" class="form-control bg-secondary text-light border-0" required>
                 </div>
                 <div class="col-6 mb-3">
                     <label>Longitude</label>
                     <input type="text" name="lng" class="form-control bg-secondary text-light border-0" required>
                 </div>
             </div>
          </div>
          <div class="modal-footer border-secondary">
            <button type="submit" class="btn btn-primary">Save Port</button>
          </div>
      </form>
    </div>
  </div>
</div>

<!-- Add Article Modal -->
<div class="modal fade" id="addArticleModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-dark text-light border-secondary">
      <form action="{{ route('admin.articles.store') }}" method="POST">
          @csrf
          <div class="modal-header border-secondary">
            <h5 class="modal-title">Compose Internal Article</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
             <div class="mb-3">
                 <label>Title</label>
                 <input type="text" name="title" class="form-control bg-secondary text-light border-0" required>
             </div>
             <div class="mb-3">
                 <label>Category</label>
                 <input type="text" name="category" class="form-control bg-secondary text-light border-0">
             </div>
             <div class="mb-3">
                 <label>Content</label>
                 <textarea name="content" rows="6" class="form-control bg-secondary text-light border-0" required></textarea>
             </div>
             <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_published" id="isPublished" value="1" checked>
                <label class="form-check-label" for="isPublished">Publish immediately</label>
             </div>
          </div>
          <div class="modal-footer border-secondary">
            <button type="submit" class="btn btn-primary">Save Article</button>
          </div>
      </form>
    </div>
  </div>
</div>

<style>
    .nav-tabs .nav-link { border: none; border-bottom: 2px solid transparent; }
    .nav-tabs .nav-link:hover { border-color: rgba(255,255,255,0.2); }
    .nav-tabs .nav-link.active { background-color: transparent !important; border-bottom: 2px solid var(--primary-blue); color: var(--primary-blue) !important; }
</style>
@endsection

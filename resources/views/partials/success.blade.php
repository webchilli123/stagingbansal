@if(session('success'))
 
  <article class="alert alert-success fade show d-flex align-items-center justify-content-between" id="success">
      <span>{{ session('success') }}</span>
      <button class="btn btn-close" data-bs-dismiss="alert"></button>
  </article>

@endif

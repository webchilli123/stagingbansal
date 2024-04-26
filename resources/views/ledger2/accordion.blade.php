<div class="accordion mb-3" id="ledger-accordion">
    <div class="accordion-item">
      <h2 class="accordion-header">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-1">
          <span>
             <i class="fa fa-search text-primary me-1"></i> Account / Stock Ledger
          </span>
        </button>
      </h2>
      <div id="collapse-1" class="accordion-collapse collapse" data-bs-parent="#ledger-accordion">
        <div class="accordion-body">
          <form id="search">
            <div class="row">
              <div class="col-lg-5">
                <div class="input-group mb-2">
                  <span class="input-group-text">Type</span>
                  <select class="form-control" name="type" required>
                    <option selected value="">Choose...</option>
                    <option value="account">Account</option>
                    <option value="stock">Stock</option>
                 </select>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="input-group mb-2">
                  <span class="input-group-text">Party</span>
                  <select name="party_id" class="form-control" required>
                    <option value="">Choose...</option>
                    @foreach($parties as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-lg-1">
                <button class="btn btn-primary"><i class="fa fa-search"></i></button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  
  </div>
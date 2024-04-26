<div class="accordion mb-5" id="order-accordion">
    <div class="accordion-item">
      <h2 class="accordion-header">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-1">
          <span>
             <i class="fa fa-search text-primary me-1"></i> By Party Name
          </span>
        </button>
      </h2>
      <div id="collapse-1" class="accordion-collapse collapse" data-bs-parent="#order-accordion">
        <div class="accordion-body">
            <div class="row">
              <div class="col-lg-6">
                <div class="input-group mb-2">
                  <span class="input-group-text">Type</span>
                  <select id="order_type" class="form-control">
                    <option value="" selected>Choose...</option>
                    <option value="sale">Sale</option>
                    <option value="purchase">Purchase</option>
                  </select>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="input-group mb-2">
                  <span class="input-group-text">Party</span>
                  <select id="party_id" class="form-control">
                    <option value="">Choose...</option>
                    @foreach($parties as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
        </div>
      </div>
    </div>
  
  </div>
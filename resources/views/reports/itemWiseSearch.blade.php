<div class="accordion mb-3" id="ledger-accordion">
    <div class="accordion-item">
      <h2 class="accordion-header">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-1">
          <span>
             <i class="fa fa-search text-primary me-1"></i> Item Wise Stock
          </span>
        </button>
      </h2>
      <div id="collapse-1" class="accordion-collapse collapse show" data-bs-parent="#ledger-accordion">
        <div class="accordion-body">
          <form id="search">
            <div class="row">
              <div class="col-lg-6">
                <div class="input-group mb-2">
                  <span class="input-group-text">Items</span>
                  <select name="item_id" class="form-control">
                    <option value="" selected>Choose...</option>
                    @foreach ($items as $item)
                    <option value="{{ $item->id }}" {{  request('item_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
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
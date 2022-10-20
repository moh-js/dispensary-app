<div>

    <div class="row">
        <div class="col-sm-3">
            <div class="form-group">
              <select wire:model="action" id="action" class="form-control">
                  <option value="{{ null }}" selected>Choose action...</option>
                  <option value="created" >Created</option>
                  <option value="updated" >Updated</option>
                  <option value="deleted" >Deleted</option>

              </select>
            </div>
        </div>
        <div class="col-sm-1">
            <div class="form-group">
              <select wire:model="collection" id="action" class="form-control">
                  <option value="50" >50</option>
                  <option value="100" >100</option>
                  <option value="300" >300</option>
                  <option value="500" >500</option>
                  <option value="1000" >1000</option>

              </select>
            </div>
        </div>
    </div>


    <div class="table-responsive">
        <table class="table table-striped table-inverse table-bordered table-sm">
            <thead class="thead-inverse">
                <tr>
                    <th>#</th>
                    <th>Action</th>
                    <th>Causer</th>
                    <th>Description</th>
                    <th>When</th>
                    {{-- <th>Action</th> --}}
                </tr>
                </thead>
                <tbody>
                    @foreach ($audits as $key => $audit)
                        @livewire($component, ['audit' => $audit, 'sr' => ($key + $audits->firstItem())], key($audit->id))
                    @endforeach
                </tbody>
        </table>
    </div>

    <div class="float-right">
        {{ $audits->links() }}
    </div>
</div>

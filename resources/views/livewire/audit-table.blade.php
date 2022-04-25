<div>

    <div class="row">
        <div class="col-sm-3">
            <div class="form-group">
              <select wire:model="action" id="action" class="form-control">
                  <option value="{{ null }}" selected>Choose action...</option>
                  <option value="created" {{ 'created' == $action? 'selected':'' }}>Created</option>
                  <option value="updated" {{ 'updated' == $action? 'selected':'' }}>Updated</option>
                  <option value="deleted" {{ 'deleted' == $action? 'selected':'' }}>Deleted</option>

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
                        @livewire('audit-table-row', ['audit' => $audit, 'sr' => ($key + $audits->firstItem())], key($audit->id))
                    @endforeach
                </tbody>
        </table>
    </div>

    <div class="float-right">
        {{ $audits->links() }}
    </div>
</div>

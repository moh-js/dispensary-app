<div>
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

    <div class="float-right">
        {{ $audits->links() }}
    </div>
</div>

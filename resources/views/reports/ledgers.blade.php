@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header"><h5>Ledger Report</h5></div>
    <div class="card-body table-bordered-style">
        <form action="{{ route('inventory-ledger.search') }}" method="post">
            @csrf
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                      <input type="text"
                        class="form-control" name="name" value="{{ old('name')??$name }}" id="name" placeholder="Amoxicillin">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                      <select name="unit" id="unit" class="form-control">
                          <option value="{{ null }}" selected>Choose Unit...</option>
                          @foreach (App\Models\Unit::all() as $u)
                              <option value="{{ $u->slug }}" {{ ($u->slug) == $unit? 'selected':'' }}>{{ $u->name }}</option>
                          @endforeach
                      </select>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                      <select name="when" id="when" class="form-control">
                          <option value="{{ null }}" selected>Choose from when...</option>
                          <option value="today" {{ 'today' == $when? 'selected':'' }}>Today</option>
                          <option value="week" {{ 'week' == $when? 'selected':'' }}>Week</option>
                          <option value="month" {{ 'month' == $when? 'selected':'' }}>Month</option>
                          <option value="six_month" {{ 'six_month' == $when? 'selected':'' }}>6 Months</option>
                          <option value="year" {{ 'year' == $when? 'selected':'' }}>Year</option>

                      </select>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="#" class="btn btn-success float-right">Export</a>
                    </div>
                </div>
            </div>
        </form>

        <hr>

        <div class="table-responsive">
            <table class="table table-striped table-inverse table-sm">
                <thead class="thead-inverse">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th class="text-center">Operation</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Quantity</th>
                        <th>Requested By</th>
                        <th>When</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($ledgers as $key => $ledger)
                            <tr>
                                <td scope="row">{{ ++$key }}</td>
                                <td>{{ $ledger->item->name??'' }}</td>
                                <td class="text-center">
                                    @if ($ledger->type == 'sent')
                                        <span class="badge badge-primary">{{ ($ledger->type??'') }}</span>
                                    @else
                                        <span class="badge badge-warning">{{ ($ledger->type??'') }}</span>
                                    @endif
                                </td>
                                <td>{{ $ledger->from??'' }}</td>
                                <td>{{ $ledger->unit->name??'' }}</td>
                                <td>{{ $ledger->quantity??'' }}</td>
                                <td>{{ $ledger->user->name??'' }}</td>
                                <td>{{ $ledger->created_at->format('d M y - H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
            </table>
        </div>
        <div class="float-right">
            {{ $ledgers->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

@endsection

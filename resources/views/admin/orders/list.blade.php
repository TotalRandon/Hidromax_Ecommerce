@extends('admin.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Pedidos</h1>
            </div>
            <div class="col-sm-6 text-right">
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        @include('admin.message')
        <div class="card">
            <form action="" method="get">
                <div class="card-header">
                    <div class="card-title">
                        <button type="button" onclick="window.location.href='{{ route("orders.index") }}'" class="btn btn-default btn-sm">Limpar busca</button>
                    </div>
                    <div class="card-tools">
                        <div class="input-group input-group" style="width: 250px;">
                            <input value="{{ Request::get('keyword') }}" type="text" name="keyword" class="form-control float-right" placeholder="Search">
        
                            <div class="input-group-append">
                              <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                              </button>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
            
            <div class="card-body table-responsive p-0">								
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th width="60">Codigo#</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Celular/Telefone</th>
                            <th>Estatus</th>
                            <th>Valor</th>
                            <th>Data de compra</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($orders->isNotEmpty())
                    @foreach ( $orders as $order )
                    <tr>
                        <td><a href="{{ route('orders.detail', $order->id) }}">{{ $order->id }}</a></td>
                        <td>{{ $order->name }}</td>
                        <td>{{ $order->email }}</td>
                        <td>{{ $order->mobile }}</td>
                        <td>
                            @if ($order->status == 'pending')
                                <span class="badge bg-danger">Pagamento pendente</span>
                            @elseif ($order->status == 'shipped')
                                <span class="badge bg-info">Enviado</span>
                            @elseif ($order->status == 'cancelled')
                                <span class="badge bg-danger">Cancelado</span>
                            @else
                                <span class="badge bg-success">Entrege</span>
                            @endif
                        </td>
                        <td>R$ {{ number_format($order->grand_total, 2, ',', '.') }}</td>
                        <td>{{ \Carbon\Carbon::parse($order->created_at)->locale('pt-BR')->translatedFormat('d M, Y') }}</td>
                    </tr>
                    @endforeach
                    @else
                        <tr>
                            <td colspan="5">Registros não encontrados</td>
                        </tr>
                    @endif
                    </tbody>
                </table>										
            </div>
            <div class="card-footer clearfix">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection

@section('customJs')

@endsection
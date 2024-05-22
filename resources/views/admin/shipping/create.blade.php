@extends('admin.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Gerenciamento de fretes</h1>
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
        <form action="" method="post" id="shippingForm" name="shippingForm">
            <div class="card">
                <div class="card-body">								
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <select name="states" id="states" class="form-control">
                                <option value="">Selecione um Estado</option>
                                @if ($states->isNotEmpty())
                                    @foreach ($states as $state)
                                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <p></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <input type="text" name="amount" id="amount" class="form-control" placeholder="Quantia">
                            <p></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Criar</button>
                        </div>
                    </div>
                </div>	
                </div>						
            </div>
            
        </form>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped">
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Quantia</th>
                                <th>Ação</th>
                            </tr>
                            @if ($shippingCharges->isNotEmpty())
                                @foreach ($shippingCharges as $shippingCharge)
                                <tr>
                                    <th>{{ $shippingCharge->id }}</th>
                                    <th>{{ $shippingCharge->name }}</th>
                                    <th>R$ {{ $shippingCharge->amount = number_format($shippingCharge->amount, 2, ',', '.') }}</th>
                                    <th>
                                        <a href="{{ route('shipping.edit', $shippingCharge->id) }}" class="btn btn-primary">Editar</a>
                                        <a href="javascript:void(0);" onclick="deleteRecord({{ $shippingCharge->id }})" class="btn btn-danger">Deletar</a>
                                    </th>
                                </tr>
                                @endforeach
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection

@section('customJs')
<script>
$("#shippingForm").submit(function(event){
    event.preventDefault();
    var element = $(this);
    $("button[type=submit]").prop('disabled', true);
    
    $.ajax({
        url: '{{ route("shipping.store") }}',
        type: 'post',
        data: element.serializeArray(),
        dataType: 'json',
        success: function(response){
            $("button[type=submit]").prop('disabled', false);

            if(response["status"] == true){ 

                window.location.href="{{ route('shipping.create') }}";

            } else {
                var errors = response['errors'];
                if(errors['states']) {
                    $("#states").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedbacks').html(errors['states']);
                } else {
                    $("#states").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedbacks').html("");
                }

                if(errors['amount']) {
                    $("#amount").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedbacks').html(errors['amount']);
                } else {
                    $("#amount").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedbacks').html("");
                }
            }
        }, error: function(jqXHR, exception) {
            console.log("Alguma coisa está errada!");
        }
    });
});

function deleteRecord(id){

        var url = '{{ route("shipping.delete", "ID") }}';
        var newUrl = url.replace("ID", id)

        if(confirm("Você tem certeza que deseja excluir?")) {
            $.ajax({
                url: newUrl,    
                type: 'delete',
                data: {},
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {

                    if(response["status"]){ 
                        window.location.href="{{ route('shipping.create') }}";
                    } 
                }
            });
        }
    }
</script>
@endsection

@extends('admin.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Gerenciamento de entregas</h1>
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
                                            <option {{ ($shippingCharge->states_id == $state->id) ? 'selected' : '' }} value="{{ $state->id }}">{{ $state->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <p></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <input value="{{ $shippingCharge->amount }}" type="text" name="amount" id="amount" class="form-control" placeholder="Quantia">
                            <p></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Atualizar</button>
                        </div>
                    </div>
                </div>	
                </div>						
            </div> 
        </form>
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
        url: '{{ route("shipping.update", $shippingCharge->id) }}',
        type: 'put',
        data: element.serializeArray(),
        dataType: 'json',
        success: function(response){
            $("button[type=submit]").prop('disabled', false);

            if(response["status"] == true){ 
                window.location.href = "{{ route('shipping.create') }}";
            } else {
                var errors = response['errors'];
                if(errors['states']) {
                    $("#states").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback').html(errors['states']); // Corrigir classe de feedback
                } else {
                    $("#states").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");
                }

                if(errors['amount']) {
                    $("#amount").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback').html(errors['amount']);
                } else {
                    $("#amount").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");
                }
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Erro ao atualizar: " + textStatus, errorThrown);
            $("button[type=submit]").prop('disabled', false);
        }
    });
});

</script>
@endsection

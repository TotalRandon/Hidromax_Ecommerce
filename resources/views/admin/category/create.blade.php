@extends('admin.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Criar categoria</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('categories.index') }}" class="btn btn-primary">Voltar</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="" method="post" id="categoryForm" name="categoryForm">
            <div class="card">
                <div class="card-body">								
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name">Nome</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Nome">
                            <p></p>	
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="slug">Slug</label>
                            <input type="text" readonly name="slug" id="slug" class="form-control" placeholder="Slug">
                            <p></p>	
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="1">Ativado</option>    
                                <option value="0">Bloqueado</option>    
                            </select>	
                        </div>
                    </div>									
                </div>
                </div>							
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Criar</button>
                <a href="{{ route('categories.index') }}" class="btn btn-outline-dark ml-3">Cancelar</a>
            </div>
        </form>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection

@section('customJs')
<script>
$("#categoryForm").submit(function(event){
    event.preventDefault();
    var element = $(this);
    $("button[type=submit]").prop('desable', true);
    $.ajax({
        url: '{{ route("categories.store") }}',
        type: 'post',
        data: element.serializeArray(),
        dataType: 'json',
        success: function(response){
            $("button[type=submit]").prop('desable', false);

            if(response["status"] == true){ 

                window.location.href="{{ route('categories.index') }}";

                $("#name").removeClass('is-invalid')
                .siblings('p')
                .addClass('invalid-feedbacks').html("");

                $("#slug").removeClass('is-invalid')
                .siblings('p')
                .addClass('invalid-feedbacks').html("");

            } else {
                var errors = response['erros'];
                if(errors['name']) {
                    $("#name").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedbacks').html(errors['name']);
                } else {
                    $("#name").removeClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedbacks').html("");
                }

                if(errors['slug']) {
                    $("#slug").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedbacks').html(errors['slug']);
                } else {
                    $("#slug").removeClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedbacks').html("");
                }
            }

            

        }, error: function(jqXHR, exception) {
            console.log("Alguma coisa está errada!");
        }
    })
});
    $("#name").change(function(){ 
        var element = $(this);
        $("button[type=submit]").prop('desable', true);

        $.ajax({
            url: '{{ route("getSlug") }}',
            type: 'get',
            data: {title: element.val()},
            dataType: 'json',
            success: function(response){
                $("button[type=submit]").prop('desable', false);
                if(response["status"] == true) {
                    $("#slug").val(response["slug"]);
                }
            }
        });
    });
</script>
@endsection
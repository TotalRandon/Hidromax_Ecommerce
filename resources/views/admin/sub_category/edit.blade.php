@extends('admin.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Editar Sub Categoria</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('sub-categories.index') }}" class="btn btn-primary">Voltar</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="" method="put" name="subCategoryForm" id="subCategoryForm">
        <div class="card">
            <div class="card-body">								
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="name">Categoria</label>
                            <select name="category" id="category" class="form-control">
                                <option value="">selecione uma categoria</option>
                                @if ($categories->isNotEmpty())
                                @foreach ($categories as $category)
                                    <option {{ ($subCategory->category_id == $category->id) ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                                @endif
                            </select>
                            <p></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name">Nome</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Name" value="{{ $subCategory->name }}">
                            <p></p>	
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="slug">Slug</label>
                            <input type="text" readonly name="slug" id="slug" class="form-control" placeholder="Slug" value="{{ $subCategory->slug }}">
                            <p></p>	
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control" >	
                                <option {{ ($subCategory->status == 1) ? 'selected' : '' }} value="1">Ativado</option>
                                <option {{ ($subCategory->status == 0) ? 'selected' : '' }} value="0">Desativado</option>
                            </select>
                            <p></p>
                        </div> 
                    </div>	
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="showHome">Mostrar na pagina do cliente</label>
                            <select name="showHome" id="showHome" class="form-control">
                                <option {{ ($category->showHome == 'Yes') ? 'selected' : '' }} value="Yes">Sim</option>    
                                <option {{ ($category->showHome == 'No') ? 'selected' : '' }} value="No">Não</option>    
                            </select>	
                        </div>
                    </div>	
                </div>
            </div>							
        </div>
        <div class="pb-5 pt-3">
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="{{ route('sub-categories.index') }}" class="btn btn-outline-dark ml-3">Cancelar</a>
        </div>
    </form>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection

@section('customJs')
<script>
$("#subCategoryForm").submit(function(event){
    event.preventDefault();
    var element = $(this);
    $("button[type=submit]").prop('disabled', true);
    $.ajax({
        url: '{{ route("sub-categories.update", $subCategory->id) }}',
        type: 'put',
        data: element.serializeArray(),
        dataType: 'json',
        success: function(response){
            $("button[type=submit]").prop('disabled', false);

            if(response["status"] == true){ 

                window.location.href="{{ route('sub-categories.index') }}";

                $("#name").removeClass('is-invalid')
                .siblings('p')
                .addClass('invalid-feedbacks').html("");

                $("#slug").removeClass('is-invalid')
                .siblings('p')
                .addClass('invalid-feedbacks').html("");

                $("#category").removeClass('is-invalid')
                .siblings('p')
                .addClass('invalid-feedbacks').html("");

            } else {

                if(response['notFound'] == true) {
                    window.location.href="{{ route('sub-categories.index') }}";
                    return false;
                }

                var errors = response['errors'];
                if(errors['name']) {
                    $("#name").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedbacks').html(errors['name']);
                } else {
                    $("#name").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedbacks').html("");
                }

                if(errors['slug']) {
                    $("#slug").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedbacks').html(errors['slug']);
                } else {
                    $("#slug").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedbacks').html("");
                }

                if(errors['category']) {
                    $("#category").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedbacks').html(errors['category']);
                } else {
                    $("#category").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedbacks').html("");
                }

            }
        }, error: function(jqXHR, exception) {
            console.log("Alguma coisa está errada!");
        }
    });
});

$("#name").change(function(){ 
    var element = $(this);
    $("button[type=submit]").prop('disabled', true);

    $.ajax({
        url: '{{ route("getSlug") }}',
        type: 'get',
        data: {title: element.val()},
        dataType: 'json',
        success: function(response){
            $("button[type=submit]").prop('disabled', false);
            if(response["status"] == true) {
                $("#slug").val(response["slug"]);
            }
        }
    });
});
</script>
@endsection

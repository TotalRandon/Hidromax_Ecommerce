@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">					
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Criar Produto</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('products.index') }}" class="btn btn-primary">Voltar</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <form action="" method="post" name="productForm" id="productForm">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-body">								
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="title">Título</label>
                                        <input type="text" name="title" id="title" class="form-control" placeholder="Título">	
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="slug">Slug</label>
                                        <input type="text" readonly name="slug" id="slug" class="form-control" placeholder="Slug">	
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="short_description">Descrição Curta</label>
                                        <textarea name="short_description" id="short_description" cols="30" rows="10" class="summernote" placeholder="Descrição curta"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="description">Descrição</label>
                                        <textarea name="description" id="description" cols="30" rows="10" class="summernote" placeholder="Descrição"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="shipping_returns">Envios e Devoluções</label>
                                        <textarea name="shipping_returns" id="shipping_returns" cols="30" rows="10" class="summernote" placeholder="Envios e devoluções"></textarea>
                                    </div>
                                </div>                                            
                            </div>
                        </div>	                                                                      
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Imagem do produto</h2>								
                            <div id="image" class="dropzone dz-clickable">
                                <div class="dz-message needsclick">    
                                    <br>Clique ou arraste as imagens aqui.<br><br>                                            
                                </div>
                            </div>
                        </div>	                                                                      
                    </div>

                    <div class="row" id="product-gallery">

                    </div>

                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Preços</h2>								
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="price">Preço</label>
                                        <input type="text" name="price" id="price" class="form-control" placeholder="0.00">	
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="compare_price">Comparar preço</label>
                                        <input type="text" name="compare_price" id="compare_price" class="form-control" placeholder="0.00">
                                        <p class="text-muted mt-3">
                                            Para mostrar um preço reduzido, mova o preço original do produto para Comparar pelo preço. Insira um valor mais baixo em Preço.                                        
                                        </p>	
                                    </div>
                                </div>                                            
                            </div>
                        </div>	                                                                      
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Inventario</h2>								
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sku">SKU (Stock Keeping Unit)</label>
                                        <input type="text" name="sku" id="sku" class="form-control" placeholder="sku">
                                        <p class="error"></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="barcode">Código de barras</label>
                                        <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Código de barras">	
                                    </div>
                                </div>   
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <div class="custom-control custom-checkbox">
                                            <input type="hidden" name="track_qty" value="No">
                                            <input class="custom-control-input" type="checkbox" id="track_qty" name="track_qty" value="Yes" checked>
                                            <label for="track_qty" class="custom-control-label">Rastrear Quantidade</label>
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <input type="number" min="0" name="qty" id="qty" class="form-control" placeholder="Quantidade">	
                                        <p class="error"></p>
                                    </div>
                                </div>                                         
                            </div>
                        </div>	                                                                      
                    </div>

                <div class="card mb-3">
                    <div class="card-body">	
                        <h2 class="h4 mb-3">Produtos Relacionados</h2>
                        <div class="mb-3">
                            <select multiple class="related-product w-100" name="related_products[]" id="related_products">
                                
                            </select>
                            <p class="error"></p>
                        </div>
                    </div>
                </div> 
                </div>

                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-body">	
                            <h2 class="h4 mb-3">Estatus do produto</h2>
                            <div class="mb-3">
                                <select name="status" id="status" class="form-control">
                                    <option value="1">Ativado</option>
                                    <option value="0">desativado</option>
                                </select>
                            </div>
                        </div>
                    </div> 
                    <div class="card">
                        <div class="card-body">	
                            <h2 class="h4  mb-3">Categoria do produto</h2>
                            <div class="mb-3">
                                <label for="category">Categoria</label>
                                <select name="category" id="category" class="form-control">
                                        <option value="">Selecione uma categoria</option>

                                    @if ($categories->isNotEmpty())
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach                          
                                    @endif
                                </select>
                                <p class="error"></p>
                            </div>
                            <div class="mb-3">
                                <label for="category">Subcategoria</label>
                                <select name="sub_category" id="sub_category" class="form-control">
                                    <option value="">Selecione uma subcategoria</option>
                                </select>
                            </div>
                        </div>
                    </div> 
                    <div class="card mb-3">
                        <div class="card-body">	
                            <h2 class="h4 mb-3">Marca do produto</h2>
                            <div class="mb-3">
                                <select name="brand" id="brand" class="form-control">
                                    <option value="">Selecione uma marca</option>

                                    @if ($brands->isNotEmpty())
                                        @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach                          
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div> 
                    <div class="card mb-3">
                        <div class="card-body">	
                            <h2 class="h4 mb-3">Produto em Destaque</h2>
                            <div class="mb-3">
                                <select name="is_featured" id="is_featured" class="form-control">
                                    <option value="no">Não</option>
                                    <option value="yes">Sim</option>                                                
                                </select>
                                <p class="error"></p>
                            </div>
                        </div>
                    </div>                                 
                </div>
            </div>
            
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Criar</button>
                <a href="{{ route('products.index') }}" class="btn btn-outline-dark ml-3">Cancelar</a>
            </div>
        </div>
        </form>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('customJs')
<script>
$('.related-product').select2({
    ajax: {
        url: '{{ route("products.getProducts") }}',
        dataType: 'json',
        tags: true,
        multiple: true,
        minimumInputLenght: 3,
        processResults: function (data) {
            return {
                results: data.tags
            };
        }
    }
});


$("#title").change(function () {
    var element = $(this);
    $("button[type=submit]").prop('disabled', true);
    $.ajax({
        url: '{{ route("getSlug") }}',
        type: 'get',
        data: { title: element.val() },
        dataType: 'json',
        success: function (response) {
            $("button[type=submit]").prop('disabled', false);
            if (response["status"] == true) {
                $("#slug").val(response["slug"]);
            }
        }
    });
});

$("#productForm").submit(function(event){
    event.preventDefault();
    var element = $(this);
    $("button[type=submit]").prop('disabled', true);
    $.ajax({
        url: '{{ route("products.store") }}',
        type: 'post',
        data: element.serializeArray(),
        dataType: 'json',
        success: function(response) {
            $("button[type=submit]").prop('disabled', false);

            if (response["status"] == true) {

                $(".error").removeClass('invalid-feedback').html('');
                $("input[type='text'], select, input[type='number']").removeClass('is-invalid');

                window.location.href="{{ route('products.index') }}"

            } else {
                var errors = response['errors'];

                $(".error").removeClass('invalid-feedback').html('');
                $("input[type='text'], select, input[type='number']").removeClass('is-invalid');

                $.each(errors, function(key, value) {
                    $(`#${key}`).addClass('is-invalid')
                        .siblings('p')
                        .addClass('invalid-feedback')
                        .html(value);
                });
            }
        },error: function(jqXHR, exception) {
            console.log("Algo de errado não esta certo em #productForm");
        }
    });
});

$("#category").change(function () {
    var category_id = $(this).val();
    $.ajax({
        url: '{{ route("product-subcategories.index") }}',
        type: 'get',
        data: { category_id: category_id },
        dataType: 'json',
        success: function (response) {
            $("#sub_category").find("option").not(":first").remove();
            $.each(response["subCategories"], function (key, item) {
                $("#sub_category").append(`<option value='${item.id}'>${item.name}</option>`);
            });
        },
        error: function () {
            console.log("Algo de errado não esta certo em #category");
        }
    });
});

Dropzone.autoDiscover = false;
const dropzone = $('#image').dropzone({
    init: function() {
        this.on('addedfile', function(file){
            if(this.files.length > 1) {
                this.removeFile(this.files[0]);
            }
        });
    },
    url: "{{ route('temp-images.create') }}",
    maxFiles: 10,
    paramName: 'image',
    addRemoveLinks: true,
    acceptedFiles: "image/jpeg,image/png,image/gif",
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }, success: function(file, response){
        //$("#image_id").val(response.image_id);
        //console.log('olha aqui !!!', response)

        var html = `<div class="col-md-3" id="image-row-${response.image_id}"><div class="card">
            <input type="hidden" name="image_array[]" value="${response.image_id}">
            <img src="${response.ImagePath}" class="card-img-top" alt="">
            <div class="card-body">
                <a href="javascript:void(0)" onclick="deleteImage(${response.image_id})" class="btn btn-danger">Delete</a>
            </div>
        </div></div>`;

        $("#product-gallery").append(html);
    },
    complete: function(file){
        this.removeFile(file);
    }
});

function deleteImage(id) {
    $("#image-row"+id).remove();
}
</script>
@endsection
@extends('front.layouts.app')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.shop') }}">Home</a></li>
                <li class="breadcrumb-item active">Loja</li>
            </ol>
        </div>
    </div>
</section>

<section class="section-6 pt-5">
    <div class="container">
        <div class="row">            
            <div class="col-md-3 sidebar">
                <div class="sub-title">
                    <h2>Categorias</h3>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        {{-- <div class="accordion accordion-flush" id="accordionExample">
                            @if ($categories->isNotEmpty())
                            @foreach ($categories as $key => $category)
                            <div class="accordion-item">
                                @if ($category->sub_category->isNotEmpty())                                    
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne-{{ $key }}" aria-expanded="false" aria-controls="collapseOne">
                                        {{ $category->name }}
                                    </button>
                                </h2>
                                @else 
                                <a href="{{ route('front.shop',$category->slug) }}" class="nav-item nav-link {{ ($categorySelected == $category->id) ? 'text-primary' : '' }}">{{ $category->name }}</a>
                                @endif
                                @if ($category->sub_category->isNotEmpty())                                    
                                <div id="collapseOne-{{ $key }}" class="accordion-collapse collapse {{ ($categorySelected == $category->id) ? 'show' : '' }}" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                                    <div class="accordion-body">
                                        <div class="navbar-nav">
                                            @foreach ($category->sub_category as $subCategory)
                                                <a href="{{ route('front.shop',[$category->slug,$subCategory->slug]) }}" class="nav-item nav-link {{ ($subCategorySelected == $subCategory->id) ? 'text-primary' : '' }}">  {{ $subCategory->name }}</a>
                                            @endforeach                                          
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>  
                            @endforeach
                            @endif                
                        </div> --}}
                        <div class="accordion accordion-flush" id="accordionExample">
                            @if ($categories->isNotEmpty())
                            @foreach ($categories as $key => $category)
                            <div class="accordion-item">
                                <div class="d-flex justify-content-between align-items-center accordion-header" id="heading{{ $key }}">
                                    <!-- Link direto para a categoria -->
                                    <a href="{{ route('front.shop', $category->slug) }}" class="flex-grow-1 pe-3 nav-link {{ ($categorySelected == $category->id) ? 'text-primary' : '' }}" style="margin-right: auto;">
                                        {{ $category->name }}
                                    </a>
                                    <!-- Botão apenas para acordeão, sem link -->
                                    @if ($category->sub_category->isNotEmpty())
                                    <button class="btn btn-link text-decoration-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $key }}" aria-expanded="false" aria-controls="collapse{{ $key }}">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                    @endif
                                </div>
                                @if ($category->sub_category->isNotEmpty())
                                <div id="collapse{{ $key }}" class="accordion-collapse collapse {{ ($categorySelected == $category->id) ? 'show' : '' }}" aria-labelledby="heading{{ $key }}" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="navbar-nav">
                                            @foreach ($category->sub_category as $subCategory)
                                            <a href="{{ route('front.shop', [$category->slug, $subCategory->slug]) }}" class="nav-item nav-link {{ ($subCategorySelected == $subCategory->id) ? 'text-primary' : '' }}">
                                                {{ $subCategory->name }}
                                            </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>  
                            @endforeach
                            @endif                
                        </div>
                        
                    </div>
                </div>

                <div class="sub-title mt-5">
                    <h2>Marcas</h3>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        @if ($brands->isNotEmpty())
                        @foreach ($brands as $brand)
                        <div class="form-check mb-2">
                            <input {{ (in_array($brand->id, $brandArray)) ? 'checked' : '' }} class="form-check-input brand-label" type="checkbox" name="brand[]" value="{{ $brand->id }}" id="brand-{{ $brand->id }}">
                            <label class="form-check-label" for="brand-{{ $brand->id }}">
                                {{ $brand->name }}
                            </label>
                        </div>          
                        @endforeach
                        @endif       
                    </div>
                </div>

                <div class="sub-title mt-5">
                    <h2>Preço</h3>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <input type="text" class="js-range-slider" name="my_range" value="" />
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="row pb-3">
                    <div class="col-12 pb-1">
                        <div class="d-flex align-items-center justify-content-end mb-4">
                            <div class="ml-2">
                                <select name="sort" id="sort" class="form-control">
                                    <option value="">Ordenar por</option>
                                    <option value="latest {{ ($sort == 'latest') ? 'selected' : '' }}">Mais recentes</option>
                                    <option value="price_desc {{ ($sort == 'price_desc') ? 'selected' : '' }}">Maior preço</option>
                                    <option value="price_asc {{ ($sort == 'price_asc') ? 'selected' : '' }}">Menor preço</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    @if ($products->isNotEmpty())
                    @foreach ($products as $product)
                    @php
                        $productImage = $product->product_images->first();
                    @endphp

                    <div class="col-md-4">
                        <div class="card product-card">
                            <div class="product-image position-relative">

                                <a href="{{ route('front.product', $product->slug) }}" class="product-img">
                                @if (!empty($productImage->image))
                                <img class="card-img-top" src="{{ asset('uploads/product/small/'.$productImage->image) }}">
                                @else
                                <img class="card-img-top" src="{{ asset('admin-assets/img/default-150x150.png') }}">
                                @endif
                                </a>

                                <a class="whishlist" href="222"><i class="far fa-heart"></i></a>                            

                                <div class="product-action">
                                    <a class="btn btn-lg btn-success" href="javascript:void(0);" onclick="addToCart({{ $product->id }});">
                                        <i class="fa fa-shopping-cart"></i> COMPRAR
                                    </a>                            
                                </div>
                            </div>                        
                            <div class="card-body text-right">
                                <a class="h6 link" href="product.php">{{ Str::limitText($product->title, 45) }}</a>
                                <div class="price mt-2">
                                    @if ($product->compare_price > 0)
                                        <span class="h6 text-secondary"><del>R$ {{ $product->compare_price = number_format($product->compare_price, 2, ',', '.')}}</del></span>
                                    @endif
                                </div>
                                <div class="price">
                                    <span class="h4"><strong>R$ {{ $product->price = number_format($product->price, 2, ',', '.')}}</strong></span>
                                </div>
                            </div>                        
                        </div>                                               
                    </div>  

                    @endforeach
                    @endif

                    <div class="col-md-12 pt-5">
                        {{ $products->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
<script>
    rangeSlider = $(".js-range-slider").ionRangeSlider({
        type: "double",
        min: 0,
        max: 1000,
        from: {{ ($priceMin) }},
        step: 10,
        to: {{ ($priceMax)}},
        skin: "round",
        max_postfix: "+",
        prefix: "R$",
        onFinish: function() {
            apply_filters()
        }
    });

    var slider = $(".js-range-slider").data("ionRangeSlider");

    $(".brand-label").change(function() {
        apply_filters();
    });

    $("#sort").change(function() {
        apply_filters();
    });

    function apply_filters() {
        var brands = [];

        $(".brand-label").each(function(){
            if ($(this).is(":checked") == true) {
                brands.push($(this).val());
            }
        });

        var url = '{{ url()->current() }}?';

        // filtro de preço
        url += '&price_min=' + slider.result.from + '&price_max=' + slider.result.to; 

        // filtro de marca
        if(brands.length > 0){
            url += '&brand='+brands.toString();
        }
    
        // ordenação
        url += '&sort='+$("#sort").val()

        window.location.href = url;
    }
</script>
@endsection

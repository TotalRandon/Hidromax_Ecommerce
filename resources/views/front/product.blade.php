@extends('front.layouts.app')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.shop') }}">Loja</a></li>
                <li class="breadcrumb-item">{{ $product->title }}</li>
            </ol>
        </div>
    </div>
</section>

<section class="section-7 pt-3 mb-3">
    <div class="container">
        <div class="row ">
            <div class="col-md-5">
                <div id="product-carousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner bg-light">

                        @if ($product->product_images)
                            @foreach ($product->product_images as $key => $productImage)
                            <div class="carousel-item {{ ($key == 0) ? 'active' : '' }}">
                                <img class="w-100 h-100" src="{{ asset('uploads/product/large/'.$productImage->image) }}" alt="Image">
                            </div>
                            @endforeach
                        @endif
                    </div>
                    <a class="carousel-control-prev" href="#product-carousel" data-bs-slide="prev">
                        <i class="fa fa-2x fa-angle-left text-dark"></i>
                    </a>
                    <a class="carousel-control-next" href="#product-carousel" data-bs-slide="next">
                        <i class="fa fa-2x fa-angle-right text-dark"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-7">
                <div class="bg-light right">
                    <h1>{{ $product->title }}</h1>
                    @if ($product->barcode > 0)
                        <p>Cod. {{ $product->barcode }}</p>
                    @endif
                    @if ($product->qty <= 20 && $product->qty > 0) 
                        <p>Restam apenas {{ $product->qty }} Unidades!</p>
                    @endif
                    <div class="d-flex mb-3">
                        <div class="text-primary mr-2">
                            <small class="fas fa-star"></small>
                            <small class="fas fa-star"></small>
                            <small class="fas fa-star"></small>
                            <small class="fas fa-star-half-alt"></small>
                            <small class="far fa-star"></small>
                        </div>
                        <small class="pt-1">(99 Reviews)</small>
                    </div>
                    @if ($product->compare_price > 0)
                        <p class="text-secondary"><del>R${{ $product->compare_price = number_format($product->compare_price, 2, ',', '.')}}</del></p>
                    @endif

                    <h1 class="fs-1">R${{ $product->price = number_format($product->price, 2, ',', '.')}}</h1>

                    {!! $product->short_description !!}

                    {{-- <a href="javascript:void(0);" onclick="addToCart({{ $product->id }});" class="btn btn-lg btn-success"><i class="fas fa-shopping-cart"></i> &nbsp;COMPRAR</a> --}}
                    <div class="product-action">
                        @if ($product->track_qty == 'yes')
                            @if ($product->qty > 0)
                            <a class="btn btn-success" href="javascript:void(0);" onclick="addToCart({{ $product->id }});">
                                <i class="fa fa-shopping-cart"></i> &nbsp;COMPRAR
                            </a>
                            @else 
                            <a class="btn btn-danger" href="javascript:void(0);">
                                ESGOTADO
                            </a>
                            @endif  
                        
                        @else
                        <a class="btn btn-success" href="javascript:void(0);" onclick="addToCart({{ $product->id }});">
                            <i class="fa fa-shopping-cart"></i> &nbsp;COMPRAR
                        </a>
                        @endif                         
                    </div>
                </div>
            </div>

            <div class="col-md-12 mt-5">
                <div class="bg-light">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">Descrição</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping" type="button" role="tab" aria-controls="shipping" aria-selected="false">Envios e devoluções</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">Avaliações</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                            {!! $product->description !!}
                        </div>
                        <div class="tab-pane fade" id="shipping" role="tabpanel" aria-labelledby="shipping-tab">
                            {!! $product->shipping_returns !!}
                        </div>
                        <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                        
                        </div>
                    </div>
                </div>
            </div> 
        </div>           
    </div>
</section>

<section class="section-8 pt-5">
    <div class="container">
        <div class="section-title">
            <h2>Produtos Relacionados</h2>
        </div> 
        <div class="row pb-3">
            <div id="related-products" class="carousel">
                @if(!empty($relatedProducts))
                @foreach ($relatedProducts as $relProduct)
                @php
                    $productImage = $relProduct->product_images->first();
                    $cardOpacity = $relProduct->qty == 0 && $relProduct->track_qty == 'yes' ? 'opacity-50' : '';
                @endphp
                <div class="card product-card {{ $cardOpacity }}">
                    <div class="product-image position-relative">
                        <a href="{{ route('front.product', $relProduct->slug) }}" class="product-img">

                            @if (!empty($productImage->image))
                                <img class="card-img-top" src="{{ asset('uploads/product/small/'.$productImage->image) }}">
                                @else
                                <img src="{{ asset('admin-assets/img/default-150x150.png') }}">
                            @endif
                        </a>

                        <a class="whishlist" href="222"><i class="far fa-heart"></i></a>                            

                        <div class="product-action">
                            
                            @if ($relProduct->track_qty == 'yes')
                            @if ($relProduct->qty > 0)
                            <a class="btn btn-lg btn-success" href="javascript:void(0);" onclick="addToCart({{ $relProduct->id }});">
                                <i class="fa fa-shopping-cart"></i> COMPRAR
                            </a>
                            @else 
                            <a class="btn btn-lg btn-danger" href="javascript:void(0);">
                                ESGOTADO
                            </a>
                            @endif  
                        
                        @else
                        <a class="btn btn-lg btn-success" href="javascript:void(0);" onclick="addToCart({{ $relProduct->id }});">
                            <i class="fa fa-shopping-cart"></i> COMPRAR
                        </a>
                        @endif 
                        </div>
                        
                    </div>                        
                    <div class="card-body text-right">
                        <a class="h6 link" href="">{{ Str::limitText($relProduct->title, 45) }}</a>
                        <div class="price">
                            @if ($relProduct->compare_price > 0)
                                <span class="h6 text-secondary">R$<del>{{ $relProduct->compare_price = number_format($relProduct->compare_price, 2, ',', '.')}}</del></span>
                            @endif
                        </div>
                        <div class="price">
                            <span class="h4">R$<strong>{{ $relProduct->price = number_format($relProduct->price, 2, ',', '.')}}</strong></span>
                        </div>
                    </div>                        
                </div> 
                @endforeach
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

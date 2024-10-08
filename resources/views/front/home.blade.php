@extends('front.layouts.app')

@section('content')
<section class="section-1">
    <div class="container">
        <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="false">
            <div class="carousel-inner">
    
                <div class="carousel-item active">
                    <picture>
                        <source media="(max-width: 799px)" srcset="{{ asset('front-assets/images/carroceu - M.png') }}" />
                        <source media="(min-width: 800px)" srcset="{{ asset('front-assets/images/carroceu - G.png') }}" />
                        <img src="{{ asset('front-assets/images/carroceu - G.png') }}" alt="" />
                    </picture>
    
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3">
                            <h1 class="display-4 text-white mb-3">Ofertas do Dia</h1>
                            <p class="mx-md-5 px-5">Lorem rebum magna amet lorem magna erat diam stet. Sadips duo stet amet amet ndiam elitr ipsum diam</p>
                            <a class="btn btn-outline-light py-2 px-4 mt-3" href="{{ route('front.shop') }}">Comprar agora</a>
                        </div>
                    </div>
                </div>
    
                <div class="carousel-item">
                    <picture>
                        <source media="(max-width: 799px)" srcset="{{ asset('front-assets/images/Carroceu2 - M.png') }}" />
                        <source media="(min-width: 800px)" srcset="{{ asset('front-assets/images/Carroceu2 - G.png') }}" />
                        <img src="{{ asset('front-assets/images/Carroceu2 - G.png') }}" alt="" />
                    </picture>
    
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3">
                            <h1 class="display-4 text-white mb-3">Confira as promoções!</h1>
                            <p class="mx-md-5 px-5">Lorem rebum magna amet lorem magna erat diam stet. Sadips duo stet amet amet ndiam elitr ipsum diam</p>
                            <a class="btn btn-outline-light py-2 px-4 mt-3" href="#">Shop Now</a>
                        </div>
                    </div>
                </div>
    
                <div class="carousel-item">
                    <picture>
                        <source media="(max-width: 799px)" srcset="{{ asset('front-assets/images/Carroceu3 - M.png') }}" />
                        <source media="(min-width: 800px)" srcset="{{ asset('front-assets/images/Carroceu3 - G.png') }}" />
                        <img src="{{ asset('front-assets/images/Carroceu3 - G.png') }}" alt="" />
                    </picture>
    
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3">
                            <h1 class="display-4 text-white mb-3">Loja com muitas variedades!</h1>
                            <p class="mx-md-5 px-5">Lorem rebum magna amet lorem magna erat diam stet. Sadips duo stet amet amet ndiam elitr ipsum diam</p>
                            <a class="btn btn-outline-light py-2 px-4 mt-3" href="#">Shop Now</a>
                        </div>
                    </div>
                </div>

                <div class="carousel-item">
                    <picture>
                        <source media="(max-width: 799px)" srcset="{{ asset('front-assets/images/Carroceu4 - M.png') }}" />
                        <source media="(min-width: 800px)" srcset="{{ asset('front-assets/images/Carroceu4 - G.png') }}" />
                        <img src="{{ asset('front-assets/images/Carroceu4 - G.png') }}" alt="" />
                    </picture>
    
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3">
                            <h1 class="display-4 text-white mb-3">Confira nossas churrasqueiras</h1>
                            <p class="mx-md-5 px-5">Lorem rebum magna amet lorem magna erat diam stet. Sadips duo stet amet amet ndiam elitr ipsum diam</p>
                            <a class="btn btn-outline-light py-2 px-4 mt-3" href="#">Shop Now</a>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div> 
</section>

<section class="section-2">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="box shadow-lg">
                    <div class="fa icon fa-check text-primary m-0 mr-3"></div>
                    <h2 class="font-weight-semi-bold m-0">Produtos de Qualidade</h5>
                </div>                    
            </div>
            <div class="col-lg-3 ">
                <div class="box shadow-lg">
                    <div class="fa icon fa-shipping-fast text-primary m-0 mr-3"></div>
                    <h2 class="font-weight-semi-bold m-0">Frete gratis para toda região</h2>
                </div>                    
            </div>
            <div class="col-lg-3">
                <div class="box shadow-lg">
                    <div class="fa icon fa-exchange-alt text-primary m-0 mr-3"></div>
                    <h2 class="font-weight-semi-bold m-0">Possibilidade de troca</h2>
                </div>                    
            </div>
            <div class="col-lg-3 ">
                <div class="box shadow-lg">
                    <div class="fa icon fa-phone-volume text-primary m-0 mr-3"></div>
                    <h2 class="font-weight-semi-bold m-0">Solicite seu orçamento</h5>
                </div>                    
            </div>
        </div>
    </div>
</section>

<section class="section-3">
    <div class="container">
        <div class="section-title">
            <h2>Categorias</h2>
        </div>           
        <div class="row pb-3">
        @if (getCategories()->isNotEmpty())
            @foreach (getCategories() as $category)
                @if ($category->status == 1)  {{-- Verifica se o status é 1 --}}
                    <div class="col-lg-3">
                        <div class="cat-card">
                            <div class="left">
                                <a href="{{ route('front.shop', $category->slug)}}">
                                    @if ($category->image != "")
                                        <img src="{{ asset('uploads/category/thumb/'.$category->image) }}" alt="" class="img-fluid">
                                    @endif
                                </a>  
                            </div>
                            <div class="right">
                                <a class="text-body" href="{{ route('front.shop', $category->slug) }}">
                                    <div class="cat-data">
                                        <h2>{{ $category->name }}</h2>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif {{-- Finaliza a verificação do status --}}
            @endforeach
        @endif

        </div>
    </div>
</section>

<section class="section-4 pt-5">
    <div class="container">
        <div class="section-title">
            <h2>Ofertas</h2>
        </div>    
        <div class="row pb-3">
            @if ($featuredProducts->isNotEmpty())
                @foreach ($featuredProducts as $product)
                @php
                    $productImage = $product->product_images->first();
                    $cardOpacity = $product->qty == 0 && $product->track_qty == 'yes' ? 'opacity-50' : '';
                @endphp
                <div class="col-6 col-md-3">
                    <div class="card product-card {{ $cardOpacity }}">
                        <div class="product-image position-relative">
                            <a href="{{ route('front.product', $product->slug) }}" class="product-img">
                                @if (!empty($productImage->image))
                                    <img class="card-img-top" src="{{ asset('uploads/product/small/'.$productImage->image) }}">
                                @else
                                    <img src="{{ asset('admin-assets/img/default-150x150.png') }}">
                                @endif
                            </a>
                            <a class="whishlist" href="222"><i class="far fa-heart"></i></a>                            
                            <div class="product-action">
                                @if ($product->track_qty == 'yes')
                                    @if ($product->qty > 0)
                                    <a class="btn btn-success" href="javascript:void(0);" onclick="addToCart({{ $product->id }});">
                                        <i class="fa fa-shopping-cart"></i> COMPRAR
                                    </a>
                                    @else 
                                    <a class="btn btn-danger" href="javascript:void(0);">
                                        ESGOTADO
                                    </a>
                                    @endif  
                                
                                @else
                                <a class="btn btn-success" href="javascript:void(0);" onclick="addToCart({{ $product->id }});">
                                    <i class="fa fa-shopping-cart"></i> COMPRAR
                                </a>
                                @endif                         
                            </div>
                        </div>                        
                        <div class="card-body text-right">
                            <a class="h6 link" href="{{ route('front.product', $product->slug) }}">{{ Str::limitText($product->title, 50) }}</a>
                            <div class="price mt-2">
                                @if ($product->compare_price > 0)
                                    <span class="h6 text-secondary"><del>R${{ $product->compare_price = number_format($product->compare_price, 2, ',', '.') }}</del></span>
                                @endif
                            </div>
                            <div class="price">
                                <span class="h4"><strong>R${{ $product->price = number_format($product->price, 2, ',', '.') }}</strong></span>
                            </div>
                        </div>                        
                    </div>                                               
                </div>  
                @endforeach
            @endif
        </div>
        
        
    </div>
</section>

<section class="section-4 pt-5">
    <div class="container">
        <div class="section-title">
            <h2>Lançamentos</h2>
        </div>    
        <div class="row pb-3">
            @if ($latestProducts->isNotEmpty())
                @foreach ($latestProducts as $product)
                @php
                    $productImage = $product->product_images->first();
                    $cardOpacity = $product->qty == 0 && $product->track_qty == 'yes' ? 'opacity-50' : '';
                @endphp
                <div class="col-6 col-md-3">
                    <div class="card product-card {{ $cardOpacity }}">
                        <div class="product-image position-relative">
                            <a href="{{ route('front.product', $product->slug) }}" class="product-img">
                                @if (!empty($productImage->image))
                                    <img class="card-img-top" src="{{ asset('uploads/product/small/'.$productImage->image) }}">
                                @else
                                    <img src="{{ asset('admin-assets/img/default-150x150.png') }}">
                                @endif
                            </a>
                            <a class="whishlist" href="222"><i class="far fa-heart"></i></a>  

                            <div class="product-action">
                                @if ($product->track_qty == 'yes')
                                    @if ($product->qty > 0)
                                    <a class="btn btn-success" href="javascript:void(0);" onclick="addToCart({{ $product->id }});">
                                        <i class="fa fa-shopping-cart"></i> COMPRAR
                                    </a>
                                    @else 
                                    <a class="btn btn-danger" href="javascript:void(0);">
                                        ESGOTADO
                                    </a>
                                    @endif  
                                
                                @else
                                <a class="btn btn-success" href="javascript:void(0);" onclick="addToCart({{ $product->id }});">
                                    <i class="fa fa-shopping-cart"></i> COMPRAR
                                </a>
                                @endif                         
                            </div>

                        </div>                      
                        <div class="card-body text-right">
                            <a class="h6 link" href="{{ route('front.product', $product->slug) }}">{{ Str::limitText($product->title, 45) }}</a>
                            <div class="price mt-2">
                                @if ($product->compare_price > 0)
                                    <span class="text text-secondary"><del>R${{ $product->compare_price = number_format($product->compare_price, 2, ',', '.') }}</del></span>
                                @endif
                            </div>
                            <div class="price">
                                <span class="h4"><strong>R${{ $product->price = number_format($product->price, 2, ',', '.') }}</strong></span>
                            </div>
                        </div>                        
                    </div>                                               
                </div>  
                @endforeach
            @endif          
        </div>
    </div>
</section>

@endsection

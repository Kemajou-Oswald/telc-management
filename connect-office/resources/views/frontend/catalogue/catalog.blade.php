@extends('frontend.layout')

@section('content')

<div style="max-width:1200px;margin:auto;padding:60px 20px;">

    <h1 style="
        font-size:32px;
        margin-bottom:40px;
        font-weight:600;
    ">
        Catalogue Produits
    </h1>

    <!-- FILTRES -->
    <div style="margin-bottom:40px;display:flex;gap:15px;flex-wrap:wrap;">

        <a href="{{ route('catalogue') }}"
           style="
           padding:8px 16px;
           border-radius:20px;
           background:#f1f1f1;
           text-decoration:none;
           font-size:14px;
           ">
            Tous
        </a>

        @foreach($categories as $category)
            <a href="{{ route('catalogue', ['category' => $category->slug]) }}"
               style="
               padding:8px 16px;
               border-radius:20px;
               background:#f1f1f1;
               text-decoration:none;
               font-size:14px;
               ">
                {{ $category->name }}
            </a>
        @endforeach

    </div>

    <!-- GRID -->
    <div style="
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(300px,1fr));
        gap:30px;
    ">

        @foreach($products as $product)

        <div style="
            background:white;
            border-radius:12px;
            overflow:hidden;
            box-shadow:0 8px 25px rgba(0,0,0,0.06);
            transition:0.3s;
        ">

            <!-- IMAGE -->
            <div style="height:220px;overflow:hidden;">
                @if($product->image)
                    <img src="{{ asset('storage/'.$product->image) }}"
                         style="
                         width:100%;
                         height:100%;
                         object-fit:cover;
                         transition:0.4s;
                         ">
                @endif
            </div>

            <!-- CONTENT -->
            <div style="padding:20px;">

                <p style="font-size:13px;color:#777;margin-bottom:5px;">
                    {{ $product->category->name ?? '' }}
                </p>

                <h3 style="
                    font-size:20px;
                    margin-bottom:10px;
                    font-weight:600;
                ">
                    {{ $product->name }}
                </h3>

                <p style="
                    font-size:14px;
                    color:#555;
                    margin-bottom:20px;
                ">
                    {{ Str::limit($product->short_description ?? $product->description, 100) }}
                </p>

                <a href="{{ route('product.show', $product->slug) }}"
                   style="
                   display:inline-block;
                   padding:10px 18px;
                   background:#0d6efd;
                   color:white;
                   text-decoration:none;
                   border-radius:6px;
                   font-size:14px;
                   ">
                    Voir le produit
                </a>

            </div>

        </div>

        @endforeach

    </div>

    <!-- PAGINATION -->
    <div style="margin-top:50px;">
        {{ $products->withQueryString()->links() }}
    </div>

</div>

@endsection

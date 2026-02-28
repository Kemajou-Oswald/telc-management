@extends('frontend.layout')

@section('content')
    <div class="text-center py-10">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-4">Équipez vos bureaux avec style</h1>
        <p class="text-xl text-gray-600">Découvrez notre sélection de mobilier ergonomique pour professionnels.</p>
    </div>

    <div class="mt-10">
        <h2 class="text-2xl font-bold mb-6">Produits à la Une</h2>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
            @foreach($featuredProducts as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 100%; height: 12rem; object-fit: cover;">
                    @else
                        <div class="h-48 bg-gray-200 flex items-center justify-center text-gray-400">Pas d'image</div>
                    @endif
                    <div class="p-4">
                        <h3 class="font-bold text-lg">{{ $product->name }}</h3>
                        <p class="text-gray-500 text-sm mb-4">{{ $product->short_description }}</p>
                        <a href="{{ route('product.show', $product->slug) }}" class="text-blue-600 font-semibold">Voir le produit &rarr;</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
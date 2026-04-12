<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <style>
        body { font-family: sans-serif; padding: 40px; background: #f4f7f6; }
        .card { background: white; padding: 20px; border-radius: 8px; max-width: 500px; margin: auto; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        input, select { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { background: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; width: 100%; font-weight: bold; }
        .back-link { display: block; margin-top: 15px; text-align: center; color: #666; text-decoration: none; }
    </style>
</head>
<body>

<div class="card">
    <h2>Edit Product</h2>
    
    <form action="{{ url('/products/'.$product->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Product Name</label>
        <input type="text" name="name" value="{{ $product->name }}" required>

        <label>Category</label>
        <input type="text" name="category" value="{{ $product->category }}">

        <label>Buying Price (ETB)</label>
        <input type="number" step="0.01" name="buying_price" value="{{ $product->buying_price }}" required>

        <label>Selling Price (ETB)</label>
        <input type="number" step="0.01" name="selling_price" value="{{ $product->selling_price }}" required>

        <label>Stock Quantity</label>
        <input type="number" name="stock" value="{{ $product->stock }}" required>

        <button type="submit">Update Product Info</button>
    </form>

    <a href="{{ url('/products') }}" class="back-link">← Back to Shop</a>
</div>

</body>
</html>
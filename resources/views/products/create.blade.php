<h1>Add Product</h1>

<form method="POST" action="/products">
    @csrf

    <input type="text" name="name" placeholder="Product Name"><br><br>
    <input type="number" name="buying_price" placeholder="Buying Price"><br><br>
    <input type="number" name="selling_price" placeholder="Selling Price"><br><br>
    <input type="number" name="stock" placeholder="Stock"><br><br>

    <button type="submit">Save</button>
</form>
<!DOCTYPE html>
<html>
<head>
    <title>Shop Inventory & Sales</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; padding: 20px; background-color: #f4f7f6; }
        .container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; border-bottom: 1px solid #eee; text-align: left; }
        th { background-color: #007bff; color: white; }
        .search-box { width: 100%; padding: 12px; margin-bottom: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .btn-add { background: #007bff; color: white; text-decoration: none; padding: 10px 15px; border-radius: 5px; font-weight: bold; }
        .category-badge { background: #e9ecef; padding: 2px 8px; border-radius: 12px; font-size: 0.75em; color: #495057; }
        .price-tag { font-size: 0.85em; color: #666; }
        .profit-text { color: #28a745; font-weight: bold; }
        .btn-process { background: #28a745; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 3px; }
        .manage-links a { color: #6c757d; text-decoration: none; font-size: 0.85em; margin-right: 5px; }
        .manage-links a:hover { color: #dc3545; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2>ABA</h2> 
        <a href="{{ url('/products/create') }}" class="btn-add">+ New Item</a>
    </div>

    @php
        $totalCost = 0;
        $potentialIncome = 0;
        $potentialProfit = 0;
        $actualProfitEarned = 0;
        $totalCashCollected = 0;
        $totalSoldItems = 0;

        foreach($products as $product) {
            $totalCost += ($product->buying_price * $product->stock);
            $potentialIncome += ($product->selling_price * $product->stock);
            $potentialProfit += ($product->selling_price - $product->buying_price) * $product->stock;
            
            // Actual results from sales already made:
            $actualProfitEarned += ($product->selling_price - $product->buying_price) * $product->total_sold;
            $totalCashCollected += ($product->selling_price * $product->total_sold);
            $totalSoldItems += $product->total_sold;
        }
    @endphp

   <div style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 10px; margin-bottom: 25px;">
        <div style="background: #f8f9fa; padding: 12px; border-radius: 8px; border-left: 5px solid #6c757d;">
            <small style="color: #666;">Inventory Cost</small>
            <div style="font-size: 1.1em; font-weight: bold;">{{ number_format($totalCost, 2) }} ETB</div>
        </div>

        <div style="background: #f8f9fa; padding: 12px; border-radius: 8px; border-left: 5px solid #007bff;">
            <small style="color: #666;">Potential Income</small>
            <div style="font-size: 1.1em; font-weight: bold; color: #007bff;">{{ number_format($potentialIncome, 2) }} ETB</div>
        </div>

        <div style="background: #fff4e5; padding: 12px; border-radius: 8px; border-left: 5px solid #fd7e14;">
            <small style="color: #666;">Total Cash Collected</small>
            <div style="font-size: 1.1em; font-weight: bold; color: #fd7e14;">{{ number_format($totalCashCollected, 2) }} ETB</div>
        </div>
        <div style="background: #fff7f0; padding: 12px; border-radius: 8px; border-left: 5px solid #ff6b6b;">
            <small style="color: #666;">Units Sold</small>
            <div style="font-size: 1.1em; font-weight: bold; color: #ff6b6b;">{{ number_format($totalSoldItems) }}</div>
        </div>
        <div style="background: #e6f9ec; padding: 12px; border-radius: 8px; border-left: 5px solid #28a745;">
            <small style="color: #666;">Actual Profit</small>
            <div style="font-size: 1.1em; font-weight: bold; color: #28a745;">{{ number_format($actualProfitEarned, 2) }} ETB</div>
        </div>

        <div style="background: #f0f7ff; padding: 12px; border-radius: 8px; border-left: 5px solid #17a2b8;">
            <small style="color: #666;">Remaining Profit</small>
            <div style="font-size: 1.1em; font-weight: bold; color: #17a2b8;">{{ number_format($potentialProfit, 2) }} ETB</div>
        </div>
    </div>

    @if(session('success'))
        <div style="color: green; margin-bottom: 15px; font-weight: bold;">{{ session('success') }}</div>
    @endif

    <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Search item or category..." class="search-box">

    <table id="productTable">
        <thead>
            <tr>
                <th>Item Details</th>
                <th>Price (Buy/Sell)</th>
                <th>In Stock</th>
                <th>Sold</th>
                <th>Quick Sale</th>
                <th>Total Profit</th>
                <th>Manage</th>
            </tr>
        </thead>
        <tbody>
            @php $totalDailyProfit = 0; @endphp
            @foreach($products as $product)
                @php 
                    $unitProfit = $product->selling_price - $product->buying_price;
                    $earnedProfit = $unitProfit * $product->total_sold;
                    $totalDailyProfit += $earnedProfit;
                @endphp
                <tr>
                    <td>
                        <strong>{{ $product->name }}</strong><br>
                        <span class="category-badge">{{ $product->category ?? 'General' }}</span>
                    </td>
                    <td>
                        <div class="price-tag">B: {{ number_format($product->buying_price, 2) }}</div>
                        <div>S: <strong>{{ number_format($product->selling_price, 2) }}</strong></div>
                    </td>
                    <td style="{{ $product->stock <= 5 ? 'color: red; font-weight: bold;' : '' }}">
                        {{ $product->stock }}
                    </td>
                    <td style="color: #007bff; font-weight: bold;">{{ $product->total_sold }}</td>
                    <td>
                        <form action="{{ url('/products/'.$product->id.'/sell') }}" method="POST" style="display: flex; gap: 5px;">
                            @csrf @method('PUT')
                            <input type="number" name="amount" value="1" min="1" max="{{ $product->stock }}" style="width: 45px;">
                            <button type="submit" class="btn-process">Sell</button>
                        </form>
                    </td>
                    <td class="profit-text">{{ number_format($earnedProfit, 2) }} ETB</td>
                    <td class="manage-links">
                        <a href="{{ url('/products/'.$product->id.'/edit') }}">Edit</a>
                        <form action="{{ url('/products/'.$product->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this product?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="background:none; border:none; color: #dc3545; cursor:pointer; font-size: inherit; padding:0;">Del</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background: #f8f9fa; font-weight: bold;">
                <td colspan="5" style="text-align: right;">Daily Profit (Actual):</td>
                <td colspan="2" class="profit-text" style="font-size: 1.2em;">{{ number_format($totalDailyProfit, 2) }} ETB</td>
            </tr>
        </tfoot>
    </table>
</div>

<script>
function filterTable() {
    let input = document.getElementById("searchInput");
    let filter = input.value.toUpperCase();
    let rows = document.getElementById("productTable").getElementsByTagName("tr");
    for (let i = 1; i < rows.length; i++) {
        let text = rows[i].textContent || rows[i].innerText;
        rows[i].style.display = text.toUpperCase().indexOf(filter) > -1 ? "" : "none";
    }
}
</script>
</body>
</html>
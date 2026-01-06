<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <!-- Product Selection (Left) -->
    <div class="col-md-7">
        <div class="card mb-4">
            <div class="card-header">
                Productos
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="btn-group w-100" role="group" id="category-filters">
                        <button type="button" class="btn btn-outline-primary active" data-cat="all">Todos</button>
                        <!-- Categories loaded via JS or server-side if passed -->
                    </div>
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text" id="search-icon">🔍</span>
                    <input type="text" class="form-control" id="product-search" placeholder="Buscar producto por nombre o código...">
                </div>
                
                <div class="list-group" id="product-list" style="max-height: 400px; overflow-y: auto;">
                    <?php foreach ($products as $product): ?>
                        <a href="#" class="list-group-item list-group-item-action product-item" 
                           data-id="<?= $product['id'] ?>"
                           data-cat-id="<?= $product['category_id'] ?>"
                           data-name="<?= esc($product['name']) ?>"
                           data-price="<?= $product['retail_price'] ?>"
                           data-stock="<?= $product['stock_quantity'] ?>">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1"><?= esc($product['name']) ?></h5>
                                <small>Stock: <?= $product['stock_quantity'] ?></small>
                            </div>
                            <p class="mb-1"><?= esc($product['code']) ?></p>
                            <small class="text-primary fw-bold">$<?= number_format($product['retail_price'], 2) ?></small>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Cart / Sale Details (Right) -->
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                Detalle de Venta
            </div>
            <div class="card-body">
                <form id="sale-form">
                    <div class="mb-3">
                        <label for="client_id" class="form-label">Cliente</label>
                        <select class="form-select" id="client_id" required>
                            <option value="">Consumidor Final (Sin registrar)</option>
                            <?php foreach ($clients as $client): ?>
                                <option value="<?= $client['id'] ?>"><?= esc($client['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                         <label for="sale_type" class="form-label">Tipo de Precios</label>
                         <select class="form-select" id="sale_type">
                             <option value="retail">Minorista</option>
                             <option value="wholesale">Mayorista</option>
                         </select>
                    </div>

                    <hr>

                    <h5>Carrito</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Prod</th>
                                    <th width="70">Cant</th>
                                    <th>Total</th>
                                    <th width="30"></th>
                                </tr>
                            </thead>
                            <tbody id="cart-body">
                                <!-- Cart items via JS -->
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <h3>Total:</h3>
                        <h3 class="text-success" id="total-amount">$0.00</h3>
                    </div>

                    <button type="submit" class="btn btn-success btn-lg btn-block mt-3 w-100" id="btn-checkout">Finalizar Venta</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    let cart = [];
    const products = <?= json_encode($products) ?>; // Full products data for price switching

    // DOM Elements
    const productList = document.getElementById('product-list');
    const productSearch = document.getElementById('product-search');
    const cartBody = document.getElementById('cart-body');
    const totalAmount = document.getElementById('total-amount');
    const saleForm = document.getElementById('sale-form');
    const saleTypeSelect = document.getElementById('sale_type');
    const categoryFilters = document.getElementById('category-filters');

    // Category Filtering
    const categoriesData = <?= json_encode($categories) ?>;
    
    // Render Category Buttons
    categoriesData.forEach(cat => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn btn-outline-primary';
        btn.dataset.cat = cat.id;
        btn.textContent = cat.name;
        categoryFilters.appendChild(btn);
    });

    categoryFilters.addEventListener('click', function(e) {
        if (e.target.tagName === 'BUTTON') {
            // Update active state
            Array.from(categoryFilters.children).forEach(b => b.classList.remove('active'));
            e.target.classList.add('active');

            const catId = e.target.dataset.cat;
            filterProducts(productSearch.value, catId);
        }
    });

    // Unified Filter Function
    function filterProducts(searchTerm, categoryId) {
        const term = searchTerm.toLowerCase();
        const items = productList.getElementsByClassName('product-item');

        Array.from(items).forEach(item => {
            const name = item.dataset.name.toLowerCase();
            const itemCatId = item.dataset.catId; // Note: Ensure dataset uses 'cat-id' -> dataset.catId
            
            const matchesSearch = name.includes(term);
            const matchesCategory = categoryId === 'all' || itemCatId === categoryId;

            if (matchesSearch && matchesCategory) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }

    productSearch.addEventListener('input', function(e) {
        const activeCatBtn = categoryFilters.querySelector('.active');
        const activeCat = activeCatBtn ? activeCatBtn.dataset.cat : 'all';
        filterProducts(e.target.value, activeCat);
    });
        Array.from(items).forEach(item => {
            const name = item.dataset.name.toLowerCase();
            const code = item.querySelector('p').textContent.toLowerCase();
            if (name.includes(term) || code.includes(term)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Add to Cart
    productList.addEventListener('click', function(e) {
        e.preventDefault();
        const item = e.target.closest('.product-item');
        if (!item) return;

        const id = parseInt(item.dataset.id);
        const name = item.dataset.name;
        const stock = parseInt(item.dataset.stock);
        
        // Get price based on type
        const type = saleTypeSelect.value;
        const productData = products.find(p => p.id == id);
        const price = type === 'retail' ? parseFloat(productData.retail_price) : parseFloat(productData.wholesale_price);

        if (stock <= 0) {
            alert('Producto sin stock');
            return;
        }

        const existing = cart.find(c => c.product_id === id);
        if (existing) {
            if (existing.quantity < stock) {
                existing.quantity++;
            } else {
                alert('No hay más stock disponible');
            }
        } else {
            cart.push({
                product_id: id,
                name: name,
                price: price,
                quantity: 1,
                max_stock: stock
            });
        }
        renderCart();
    });

    // Change Price Type
    saleTypeSelect.addEventListener('change', function() {
        const type = this.value;
        cart.forEach(item => {
            const productData = products.find(p => p.id == item.product_id);
             item.price = type === 'retail' ? parseFloat(productData.retail_price) : parseFloat(productData.wholesale_price);
        });
        renderCart();
        
        // Update UI prices in list
        const items = productList.getElementsByClassName('product-item');
        Array.from(items).forEach(item => {
             const id = parseInt(item.dataset.id);
             const productData = products.find(p => p.id == id);
             const price = type === 'retail' ? parseFloat(productData.retail_price) : parseFloat(productData.wholesale_price);
             item.querySelector('small.text-primary').textContent = '$' + price.toFixed(2);
        });
    });

    // Render Cart
    function renderCart() {
        cartBody.innerHTML = '';
        let total = 0;

        cart.forEach((item, index) => {
            const row = document.createElement('tr');
            const itemTotal = item.price * item.quantity;
            total += itemTotal;

            row.innerHTML = `
                <td>${item.name}</td>
                <td>
                    <input type="number" class="form-control form-control-sm qty-input" min="1" max="${item.max_stock}" value="${item.quantity}" data-index="${index}">
                </td>
                <td>$${itemTotal.toFixed(2)}</td>
                <td><button type="button" class="btn btn-sm btn-danger remove-btn" data-index="${index}">×</button></td>
            `;
            cartBody.appendChild(row);
        });

        totalAmount.textContent = '$' + total.toFixed(2);
    }

    // Handle Cart Events (Qty Change, Remove)
    cartBody.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-btn')) {
            const index = e.target.dataset.index;
            cart.splice(index, 1);
            renderCart();
        }
    });

    cartBody.addEventListener('change', function(e) {
        if (e.target.classList.contains('qty-input')) {
            const index = e.target.dataset.index;
            const newQty = parseInt(e.target.value);
            const item = cart[index];

            if (newQty > 0 && newQty <= item.max_stock) {
                item.quantity = newQty;
            } else {
                alert('Cantidad inválida o excede stock');
                e.target.value = item.quantity;
            }
            renderCart();
        }
    });

    // Checkout
    saleForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (cart.length === 0) {
            alert('El carrito está vacío');
            return;
        }

        const clientId = document.getElementById('client_id').value;
        const saleType = saleTypeSelect.value;
        const btn = document.getElementById('btn-checkout');

        if (!confirm('¿Confirmar venta?')) return;

        btn.disabled = true;
        btn.textContent = 'Procesando...';

        try {
            const response = await fetch('<?= site_url('sales/store') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    client_id: clientId ? clientId : null,
                    type: saleType,
                    items: cart
                })
            });

            const result = await response.json();

            if (result.status === 'success') {
                alert('Venta realizada con éxito! ID: ' + result.sale_id);
                window.location.reload();
            } else {
                alert('Error: ' + result.message);
                btn.disabled = false;
                btn.textContent = 'Finalizar Venta';
            }
        } catch (error) {
            console.error(error);
            alert('Error de conexión');
            btn.disabled = false;
            btn.textContent = 'Finalizar Venta';
        }
    });
</script>
<?= $this->endSection() ?>

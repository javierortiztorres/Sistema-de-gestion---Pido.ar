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
                    <label for="category-select" class="form-label">Filtrar por Categoría</label>
                    <select class="form-select" id="category-select">
                        <option value="all">Todas las Categorías</option>
                        <!-- Categories loaded via JS -->
                    </select>
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
                        <select class="form-select" id="client_id">
                            <?php foreach ($clients as $client): ?>
                                <option value="<?= $client['id'] ?>" <?= $client['name'] === 'Consumidor Final' ? 'selected' : '' ?>>
                                    <?= esc($client['name']) ?>
                                </option>
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

                    <div class="mb-3">
                         <label for="payment_method" class="form-label">Método de Pago</label>
                         <select class="form-select" id="payment_method">
                             <option value="cash">Efectivo</option>
                             <option value="credit_card">Tarjeta Crédito</option>
                             <option value="debit_card">Tarjeta Débito</option>
                             <option value="transfer">Transferencia</option>
                             <option value="current_account">Cuenta Corriente (Fiado)</option>
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
                                    <th width="100">Precio</th>
                                    <th width="80">Desc.</th>
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
                        <h5>Subtotal:</h5>
                        <h5 id="subtotal-amount">$0.00</h5>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5>Descuento General:</h5>
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="number" class="form-control text-end" id="global-discount" value="0" min="0">
                            <select class="form-select" id="global-discount-type" style="width: 50px;">
                                <option value="fixed">$</option>
                                <option value="percent">%</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2 border-top pt-2">
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
    const saleTypeSelect = document.getElementById('sale_type'); // Fixed duplicate
    const categorySelect = document.getElementById('category-select'); 

    // Category Filtering
    const categoriesData = <?= json_encode($categories) ?>;
    
    // Render Category Options (if not already rendered by server, but emptying first just in case)
    // categorySelect.innerHTML = '<option value="all">Todas las Categorías</option>'; 
    categoriesData.forEach(cat => {
        const option = document.createElement('option');
        option.value = cat.id;
        option.textContent = cat.name;
        categorySelect.appendChild(option);
    });

    categorySelect.addEventListener('change', function(e) {
        const catId = e.target.value;
        filterProducts(productSearch.value, catId);
    });

    // Unified Filter Function
    function filterProducts(searchTerm, categoryId) {
        const term = searchTerm.toLowerCase();
        const items = productList.getElementsByClassName('product-item');

        Array.from(items).forEach(item => {
            const name = item.dataset.name.toLowerCase();
            const code = item.querySelector('p').textContent.toLowerCase();
            const itemCatId = item.dataset.catId; 
            
            const matchesSearch = name.includes(term) || code.includes(term);
            const matchesCategory = categoryId === 'all' || itemCatId === categoryId;

            if (matchesSearch && matchesCategory) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }

    productSearch.addEventListener('input', function(e) {
        const activeCat = categorySelect.value;
        filterProducts(e.target.value, activeCat);
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
                discount: 0,
                discount_type: 'fixed', // default
                max_stock: stock
            });
        }
        renderCart();
    });

    // Change Price Type (Now just updates the default based on list)
    saleTypeSelect.addEventListener('change', function() {
        // Only update prices if user explicitly agrees? Or just update default for new items?
        // Current behavior: Updates UI list price display only.
        const type = this.value;

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
        let subtotal = 0;

        cart.forEach((item, index) => {
            const row = document.createElement('tr');
            
            // Calculate Item Discount
            let discountAmount = 0;
            if (item.discount_type === 'percent') {
                discountAmount = (item.price * item.quantity) * (item.discount / 100);
            } else {
                discountAmount = item.discount;
            }
            
            // Validate discount doesn't exceed total
            if (discountAmount > (item.price * item.quantity)) {
               discountAmount = item.price * item.quantity;
            }

            const itemTotal = (item.price * item.quantity) - discountAmount;
            subtotal += itemTotal;

            row.innerHTML = `
                <td>${item.name}</td>
                <td>
                    <input type="number" class="form-control form-control-sm qty-input" min="1" max="${item.max_stock}" value="${item.quantity}" data-index="${index}">
                </td>
                <td style="width: 100px;">
                    <input type="number" class="form-control form-control-sm price-input" step="0.01" min="0" value="${item.price}" data-index="${index}">
                </td>
                <td style="width: 140px;">
                    <div class="input-group input-group-sm">
                        <input type="number" class="form-control discount-input" min="0" value="${item.discount}" data-index="${index}">
                        <select class="form-select discount-type-input" data-index="${index}" style="width: 45px;">
                            <option value="fixed" ${item.discount_type === 'fixed' ? 'selected' : ''}>$</option>
                            <option value="percent" ${item.discount_type === 'percent' ? 'selected' : ''}>%</option>
                        </select>
                    </div>
                </td>
                <td>$${itemTotal.toFixed(2)}</td>
                <td><button type="button" class="btn btn-sm btn-danger remove-btn" data-index="${index}">×</button></td>
            `;
            cartBody.appendChild(row);
        });

        // Global Discount Logic
        const globalDiscountVal = parseFloat(document.getElementById('global-discount').value) || 0;
        const globalDiscountType = document.getElementById('global-discount-type').value;
        
        let globalDiscountAmount = 0;
        if (globalDiscountType === 'percent') {
            globalDiscountAmount = subtotal * (globalDiscountVal / 100);
        } else {
            globalDiscountAmount = globalDiscountVal;
        }

        const total = subtotal - globalDiscountAmount;

        document.getElementById('subtotal-amount').textContent = '$' + subtotal.toFixed(2);
        totalAmount.textContent = '$' + (total > 0 ? total : 0).toFixed(2);
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
        
        if (e.target.classList.contains('discount-input')) {
            const index = e.target.dataset.index;
            const newDiscount = parseFloat(e.target.value);
            
            if (newDiscount >= 0) {
                cart[index].discount = newDiscount;
            } else {
                e.target.value = 0;
            }
            renderCart();
        }

        if (e.target.classList.contains('discount-type-input')) {
            const index = e.target.dataset.index;
            cart[index].discount_type = e.target.value;
            renderCart();
        }

        if (e.target.classList.contains('price-input')) {
            const index = e.target.dataset.index;
            const newPrice = parseFloat(e.target.value);
            
            if (newPrice >= 0) {
                cart[index].price = newPrice;
            } else {
                e.target.value = cart[index].price;
            }
            renderCart();
        }
    });

    document.getElementById('global-discount').addEventListener('input', renderCart);
    document.getElementById('global-discount-type').addEventListener('change', renderCart);

    // Checkout
    saleForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (cart.length === 0) {
            alert('El carrito está vacío');
            return;
        }

        const clientId = document.getElementById('client_id').value;
        const saleType = saleTypeSelect.value;
        const paymentMethod = document.getElementById('payment_method').value;
        const btn = document.getElementById('btn-checkout');

        // Calculate Final Global Discount Amount for Backend
        const subtotalText = document.getElementById('subtotal-amount').textContent.replace('$', '');
        const subtotal = parseFloat(subtotalText);
        const globalDiscountVal = parseFloat(document.getElementById('global-discount').value) || 0;
        const globalDiscountType = document.getElementById('global-discount-type').value;
        
        let finalGlobalDiscount = 0;
        if (globalDiscountType === 'percent') {
            finalGlobalDiscount = subtotal * (globalDiscountVal / 100);
        } else {
            finalGlobalDiscount = globalDiscountVal;
        }

        // Prepare items with calculated discount amounts
        const processedItems = cart.map(item => {
            let discountAmount = 0;
            if (item.discount_type === 'percent') {
                discountAmount = (item.price * item.quantity) * (item.discount / 100);
            } else {
                discountAmount = item.discount;
            }
            // Clone item and override discount with calculated amount
            return {
                ...item,
                discount: discountAmount
            };
        });

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
                    payment_method: paymentMethod,
                    discount: finalGlobalDiscount,
                    items: processedItems
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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Venta #<?= $sale['id'] ?></title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 14px; color: #333; }
        .header { width: 100%; border-bottom: 2px solid #ddd; margin-bottom: 20px; padding-bottom: 10px; }
        .company-info { text-align: left; }
        .invoice-details { text-align: right; float: right; }
        .client-info { margin-bottom: 20px; background: #f9f9f9; padding: 10px; border: 1px solid #eee; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .totals { float: right; width: 40%; }
        .totals table { border: none; }
        .totals td { border: none; padding: 5px; }
        .grand-total { font-weight: bold; font-size: 1.2em; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 12px; color: #777; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <div style="float:left; width: 50%;">
            <h2>Mi Empresa S.A.</h2>
            <p>Calle Falsa 123, Ciudad<br>Tel: (555) 123-4567</p>
        </div>
        <div style="float:right; width: 40%; text-align: right;">
            <h3>COMPROBANTE</h3>
            <p><strong>Nro:</strong> #<?= str_pad($sale['id'], 6, '0', STR_PAD_LEFT) ?><br>
            <strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($sale['created_at'])) ?></p>
        </div>
        <div style="clear:both;"></div>
    </div>

    <div class="client-info">
        <strong>Cliente:</strong> <?= $client ? esc($client['name']) : 'Consumidor Final' ?><br>
        <?php if ($client): ?>
            <strong>Email:</strong> <?= esc($client['email']) ?><br>
            <strong>Teléfono:</strong> <?= esc($client['phone']) ?>
        <?php endif; ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Producto</th>
                <th class="text-right">Precio Unit.</th>
                <th class="text-right">Cant.</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= esc($item['product_code']) ?></td>
                    <td><?= esc($item['product_name']) ?></td>
                    <td class="text-right">$<?= number_format($item['price'], 2) ?></td>
                    <td class="text-right"><?= $item['quantity'] ?></td>
                    <td class="text-right">$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="totals">
        <table style="width: 100%;">
            <tr>
                <td class="text-right"><strong>Subtotal:</strong></td>
                <td class="text-right">$<?= number_format($sale['total'], 2) ?></td>
            </tr>
            <!-- Tax calculation could go here -->
            <tr>
                <td class="text-right grand-total">TOTAL:</td>
                <td class="text-right grand-total">$<?= number_format($sale['total'], 2) ?></td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Gracias por su compra.
    </div>

</body>
</html>

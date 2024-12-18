<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart = json_decode(file_get_contents('php://input'), true);
    $total = 0;

    echo "<h1>Cart Summary</h1>";
    echo "<ul>";
    foreach ($cart as $item) {
        echo "<li>{$item['name']} - \${$item['price']}</li>";
        $total += $item['price'];
    }
    echo "</ul>";
    echo "<h3>Total: \$$total</h3>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Confirmation</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
  <style>
    .order-success {
      text-align: center;
      margin-top: 20px;
    }
    .order-success img {
      width: 150px;
    }
    .barcode {
      margin-top: 20px;
    }
    .order-summary {
      margin-top: 20px;
    }
    .thank-you {
      text-align: center;
      margin-top: 30px;
      font-size: 1.2em;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="order-success">
      <h3>Order has been confirmed!</h3>
      <svg id="barcode" class="barcode"></svg>
    </div>

    <div class="order-summary">
      <div class="card mt-4">
        <div class="card-body">
          <h4 class="card-title">Order ID: <span id="orderId"></span></h4>
          <h5 class="card-subtitle mb-2 text-muted">Order Details:</h5>
          <ul id="orderItems" class="list-group"></ul>
          <h5 class="mt-3">Total: ৳<span id="totalPrice"></span></h5>
          <button class="btn btn-primary mt-3" onclick="downloadInvoice()">Download Invoice (PDF)</button>
        </div>
      </div>
    </div>

    <div class="thank-you">
      Thank you for shopping with us!
    </div>
  </div>

  <script>
    // Function to display order details
    function displayOrderDetails() {
      const cart = JSON.parse(localStorage.getItem('cart'));
      const orderId = localStorage.getItem('orderId');
      const totalPriceElement = document.getElementById('totalPrice');
      const orderItems = document.getElementById('orderItems');
      const orderIdElement = document.getElementById('orderId');

      // Validate if cart and orderId exist
      if (!cart || !orderId) {
        document.querySelector('.order-summary').innerHTML = `
          <p class="text-danger">No order details found. Please try again later.</p>
        `;
        return;
      }

      // Display Order ID
      orderIdElement.textContent = orderId;

      // Generate barcode
      JsBarcode("#barcode", orderId, {
        format: "CODE128",
        lineColor: "#000",
        width: 2,
        height: 50,
        displayValue: true
      });

      // Populate items and calculate total price
      let totalPrice = 0;
      cart.forEach(item => {
        totalPrice += item.price * item.quantity;
        orderItems.innerHTML += `
          <li class="list-group-item d-flex justify-content-between align-items-center">
            ${item.name} (${item.quantity} ${item.unit})
            <span>৳${(item.price * item.quantity).toFixed(2)}</span>
          </li>
        `;
      });

      // Display total price
      totalPriceElement.textContent = totalPrice.toFixed(2);
    }

    // Function to generate and download invoice as a PDF
    async function downloadInvoice() {
      const { jsPDF } = window.jspdf;

      const cart = JSON.parse(localStorage.getItem('cart')) || [];
      const orderId = localStorage.getItem('orderId') || "N/A";
      const totalPrice = document.getElementById('totalPrice')?.textContent || "0";

      // Validate if cart is empty
      if (cart.length === 0) {
        alert("No items in the cart to generate an invoice.");
        return;
      }

      // Create a new jsPDF instance
      const doc = new jsPDF();

      // Add Header
      doc.setFontSize(16);
      doc.text("Order Invoice", 105, 20, { align: "center" });
      doc.setFontSize(12);

      // Generate barcode as a data URL
      const barcodeSvg = document.getElementById("barcode");
      const barcodeDataUrl = await new Promise(resolve => {
        const svg = new XMLSerializer().serializeToString(barcodeSvg);
        const canvas = document.createElement("canvas");
        const ctx = canvas.getContext("2d");
        const img = new Image();
        img.onload = () => {
          canvas.width = img.width;
          canvas.height = img.height;
          ctx.drawImage(img, 0, 0);
          resolve(canvas.toDataURL("image/png"));
        };
        img.src = "data:image/svg+xml;base64," + btoa(svg);
      });

      // Add barcode to the PDF
      doc.addImage(barcodeDataUrl, "PNG", 100, 30, 80, 20);


      doc.text(`Order ID: ${orderId}`, 20, 40);
      doc.text("Date: " + new Date().toLocaleDateString(), 20, 46);

      // Table Headers and Rows
      const headers = [["Item", "Quantity", "Unit", "Unit Price (BDT)", "Subtotal (BDT)"]];
      const rows = cart.map(item => [
        item.name,
        item.quantity,
        item.unit,
        `${item.price.toFixed(2)}`,
        `${(item.price * item.quantity).toFixed(2)}`
      ]);

      // Add Total Row
      rows.push(["", "", "", "Total", `${totalPrice}`]);

      // Draw Table
      doc.autoTable({
        startY: 60,
        head: headers,
        body: rows,
        theme: 'grid',
        headStyles: { fillColor: [41, 128, 185] },
        bodyStyles: { textColor: [0, 0, 0] },
        alternateRowStyles: { fillColor: [245, 245, 245] },
      });


      // Add Footer
      doc.setFontSize(10);
      doc.text("Thank you for shopping with us!", 105, doc.lastAutoTable.finalY + 10, { align: "center" });

      // Save PDF
      doc.save(`invoice_${orderId}.pdf`);
    }

    // Load order details when the page loads
    window.onload = displayOrderDetails;
  </script>
</body>
</html>

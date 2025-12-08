<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    require_once __DIR__ . '/../../controllers/GameController.php';
    $gameController = new GameController();
    $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 1;
    $cartItems = $gameController->getCartByUserId($userId);
    $promoCode = isset($_SESSION['promo_code']) ? $_SESSION['promo_code'] : null;
    $summary = $gameController->getCartSummary($userId, $promoCode);

} catch (Exception $e) {
    die('<div style="background:#16213e; color:#fff; padding:40px; font-family:Arial; border-radius:10px; margin:50px auto; max-width:800px;">\n        <h2 style="color:#ec6090;">❌ Erreur lors du chargement du checkout</h2>\n        <p><strong>Message:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>\n        <p><strong>Fichier:</strong> ' . htmlspecialchars($e->getFile()) . '</p>\n        <p><strong>Ligne:</strong> ' . $e->getLine() . '</p>\n        <hr>\n        <p><a href="shop.php" style="color:#ec6090;">← Retour au Shop</a></p>\n    </div>');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>GameAct - Checkout</title>
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/fontawesome.css">
  <link rel="stylesheet" href="../assets/css/templatemo-cyborg-gaming.css">
  <style>
    body { background: #0f0f1e; font-family: 'Poppins', sans-serif; }
    .checkout-container { background:#16213e; border-radius:12px; padding:30px; margin-top:30px; color:#fff; }
    .checkout-title { color:#ec6090; font-size:2rem; font-weight:700; margin-bottom:20px; }
    .payment-method { background:#1a1a2e; padding:15px; border-radius:10px; margin-bottom:15px; }
    .pay-btn { background:#28a745; color:#fff; border:none; padding:12px 20px; border-radius:8px; font-weight:700; }
    .pay-btn:hover { background:#218838; }
    .form-section { margin-bottom:20px; }
    .cart-list { background:#1a1a2e; padding:15px; border-radius:10px; }
  </style>
</head>
<body>
  <header class="header-area header-sticky">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <nav class="main-nav">
            <a href="../../index.php" class="logo">
              <img src="../assets/images/logo.png" alt="GameAct">
            </a>
            <ul class="nav">
              <li><a href="../../index.php">Home</a></li>
              <li><a href="shop.php">Shop</a></li>
              <li><a href="all-games.php">Browse</a></li>
              <li><a href="cart.php">My Cart</a></li>
            </ul>
            <a class='menu-trigger'><span>Menu</span></a>
          </nav>
        </div>
      </div>
    </div>
  </header>

  <div class="container">
    <div class="checkout-container">
      <h2 class="checkout-title"><i class="fa fa-credit-card"></i> Checkout</h2>
      <div id="checkoutApp"></div>
    </div>
  </div>

  <footer>
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <p>Copyright © 2025 <a href="#">GameAct</a>. All rights reserved.</p>
        </div>
      </div>
    </div>
  </footer>

  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script>
    // Pass PHP data to JavaScript
    const cartData = <?= json_encode($cartItems, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) ?> || [];
    const summary = <?= json_encode($summary, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) ?> || {};

    function createEl(tag, attrs = {}, text) {
      const el = document.createElement(tag);
      for (const k in attrs) {
        if (k === 'class') el.className = attrs[k];
        else if (k === 'html') el.innerHTML = attrs[k];
        else el.setAttribute(k, attrs[k]);
      }
      if (text) el.appendChild(document.createTextNode(text));
      return el;
    }

    // Simple country -> phone regex and example placeholders
    const countries = [
      { code: 'TN', name: 'Tunisia', dial: '+216', placeholder: '+216 99 999 999', regex: /^(?:\+216)?\s?\d{2}\s?\d{3}\s?\d{3}$/ },
      { code: 'FR', name: 'France', dial: '+33', placeholder: '+33 6 12 34 56 78', regex: /^(?:\+33)?\s?\d{1}\s?\d{2}\s?\d{2}\s?\d{2}\s?\d{2}$/ },
      { code: 'US', name: 'United States', dial: '+1', placeholder: '+1 (555) 555-5555', regex: /^(?:\+1)?\s?\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}$/ },
      { code: 'GB', name: 'United Kingdom', dial: '+44', placeholder: '+44 7123 456789', regex: /^(?:\+44)?\s?7\d{3}\s?\d{6}$/ }
    ];

    function luhnCheck(val) {
      const s = val.replace(/\D/g, '');
      let sum = 0;
      let alt = false;
      for (let i = s.length - 1; i >= 0; i--) {
        let n = parseInt(s.charAt(i), 10);
        if (alt) { n *= 2; if (n > 9) n -= 9; }
        sum += n;
        alt = !alt;
      }
      return (sum % 10) === 0;
    }

    function validateName(value) {
      return /^[A-Za-z\u00C0-\u017F' -]+$/.test(value.trim());
    }

    function validateEmail(value) {
      return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value.trim());
    }

    function getCountryData(code) {
      return countries.find(c => c.code === code) || countries[0];
    }

    function validatePhone(value, countryCode) {
      const c = getCountryData(countryCode);
      return c.regex.test(value.trim());
    }

    function validateCardNumber(value) {
      const digits = value.replace(/\D/g, '');
      if (digits.length < 12 || digits.length > 19) return false;
      return luhnCheck(digits);
    }

    function buildCheckoutApp() {
      const app = document.getElementById('checkoutApp');

      // Left: Billing & Payment
      const left = createEl('div', { class: 'col-md-7' });
      const right = createEl('div', { class: 'col-md-5' });
      const row = createEl('div', { class: 'row' });

      // Billing form (generated with JS)
      const billingCard = createEl('div', { class: 'payment-method form-section' });
      billingCard.appendChild(createEl('h4', {}, 'Billing Information'));
      const form = createEl('form', { id: 'paymentForm', novalidate: 'novalidate' });

      // Name
      const nameGroup = createEl('div', { class: 'mb-3' });
      nameGroup.appendChild(createEl('label', { for: 'billingName' }, 'Full name'));
      const nameInput = createEl('input', { type: 'text', id: 'billingName', class: 'form-control', placeholder: 'Ex: Sami Ben Ali (letters and spaces only)', required: 'required' });
      nameGroup.appendChild(nameInput);
      nameGroup.appendChild(createEl('div', { id: 'errBillingName', class: 'form-text', style: 'color:#ff6b6b; display:none;' }));
      form.appendChild(nameGroup);

      // Email
      const emailGroup = createEl('div', { class: 'mb-3' });
      emailGroup.appendChild(createEl('label', { for: 'billingEmail' }, 'Email'));
      const emailInput = createEl('input', { type: 'email', id: 'billingEmail', class: 'form-control', placeholder: 'Ex: example@example.com', required: 'required' });
      emailGroup.appendChild(emailInput);
      emailGroup.appendChild(createEl('div', { id: 'errBillingEmail', class: 'form-text', style: 'color:#ff6b6b; display:none;' }));
      form.appendChild(emailGroup);

      // Country
      const countryGroup = createEl('div', { class: 'mb-3' });
      countryGroup.appendChild(createEl('label', { for: 'billingCountry' }, 'Country'));
      const countrySelect = createEl('select', { id: 'billingCountry', class: 'form-control' });
      countries.forEach(c => {
        const opt = createEl('option', { value: c.code }, `${c.name} (${c.dial})`);
        countrySelect.appendChild(opt);
      });
      countryGroup.appendChild(countrySelect);
      countryGroup.appendChild(createEl('div', { class: 'form-text', style: 'color:#aaa;' }, 'Select your country to validate phone number format.'));
      form.appendChild(countryGroup);

      // Phone
      const phoneGroup = createEl('div', { class: 'mb-3' });
      phoneGroup.appendChild(createEl('label', { for: 'billingPhone' }, 'Phone number'));
      const phoneInput = createEl('input', { type: 'tel', id: 'billingPhone', class: 'form-control', placeholder: countries[0].placeholder });
      phoneGroup.appendChild(phoneInput);
      phoneGroup.appendChild(createEl('div', { id: 'errBillingPhone', class: 'form-text', style: 'color:#ff6b6b; display:none;' }));
      phoneGroup.appendChild(createEl('div', { id: 'hintBillingPhone', class: 'form-text', style: 'color:#aaa;' }, 'Example: ' + countries[0].placeholder));
      form.appendChild(phoneGroup);

      // Address
      const addressGroup = createEl('div', { class: 'mb-3' });
      addressGroup.appendChild(createEl('label', { for: 'billingAddress' }, 'Billing Address'));
      const addressInput = createEl('input', { type: 'text', id: 'billingAddress', class: 'form-control', placeholder: 'Street, City, Postal Code' });
      addressGroup.appendChild(addressInput);
      form.appendChild(addressGroup);

      billingCard.appendChild(form);

      // Payment methods
      const payCard = createEl('div', { class: 'payment-method' });
      payCard.appendChild(createEl('h4', {}, 'Payment Method'));

      const methods = [
        { id: 'pm_card', label: 'Credit / Debit Card' },
        { id: 'pm_paypal', label: 'PayPal' },
        { id: 'pm_bank', label: 'Bank Transfer' }
      ];

      methods.forEach(m => {
        const wrapper = createEl('div', { class: 'form-check' });
        const input = createEl('input', { class: 'form-check-input', type: 'radio', name: 'paymentMethod', id: m.id, value: m.id });
        if (m.id === 'pm_card') input.checked = true;
        wrapper.appendChild(input);
        const label = createEl('label', { class: 'form-check-label', for: m.id }, m.label);
        wrapper.appendChild(label);
        payCard.appendChild(wrapper);
      });

      // Card fields container - only shown when card selected
      const cardFields = createEl('div', { id: 'cardFields', class: 'mt-3' });
      cardFields.appendChild(createEl('label', { for: 'cardNumber' }, 'Card Number'));
      const cardNumberInput = createEl('input', { type: 'text', id: 'cardNumber', class: 'form-control mb-2', placeholder: '4242 4242 4242 4242' });
      cardFields.appendChild(cardNumberInput);
      cardFields.appendChild(createEl('div', { id: 'errCardNumber', class: 'form-text', style: 'color:#ff6b6b; display:none;' }));
      const row2 = createEl('div', { class: 'd-flex gap-2' });
      const cardExpInput = createEl('input', { type: 'text', id: 'cardExp', class: 'form-control', placeholder: 'MM/YY' });
      const cardCvcInput = createEl('input', { type: 'text', id: 'cardCvc', class: 'form-control', placeholder: 'CVC' });
      row2.appendChild(cardExpInput);
      row2.appendChild(cardCvcInput);
      cardFields.appendChild(row2);
      payCard.appendChild(cardFields);

      // Submit button
      const btnWrap = createEl('div', { class: 'form-section mt-3' });
      const payBtn = createEl('button', { type: 'button', class: 'pay-btn' }, 'Pay ' + (summary.total ? ('$' + parseFloat(summary.total).toFixed(2)) : ''));
      btnWrap.appendChild(payBtn);

      // Append to left column
      left.appendChild(billingCard);
      left.appendChild(payCard);
      left.appendChild(btnWrap);

      // Right: Cart summary
      const cartCard = createEl('div', { class: 'cart-list' });
      cartCard.appendChild(createEl('h4', {}, 'Order Summary'));
      if (cartData.length === 0) {
        cartCard.appendChild(createEl('p', {}, 'Your cart is empty.'));
      } else {
        cartData.forEach(item => {
          const itemRow = createEl('div', { class: 'd-flex justify-content-between mb-2' });
          itemRow.appendChild(createEl('div', {}, item.title + ' x' + item.quantity));
          itemRow.appendChild(createEl('div', {}, '$' + (parseFloat(item.subtotal).toFixed(2))));
          cartCard.appendChild(itemRow);
        });
      }
      const hr = createEl('hr');
      cartCard.appendChild(hr);
      const subtotalRow = createEl('div', { class: 'd-flex justify-content-between mb-2' });
      subtotalRow.appendChild(createEl('div', {}, 'Subtotal'));
      subtotalRow.appendChild(createEl('div', {}, '$' + (summary.subtotal ? parseFloat(summary.subtotal).toFixed(2) : '0.00')));
      cartCard.appendChild(subtotalRow);
      if (summary.discount && summary.discount > 0) {
        const disc = createEl('div', { class: 'd-flex justify-content-between mb-2 text-success' });
        disc.appendChild(createEl('div', {}, 'Discount'));
        disc.appendChild(createEl('div', {}, '-$' + parseFloat(summary.discount).toFixed(2)));
        cartCard.appendChild(disc);
      }
      const totalRow = createEl('div', { class: 'd-flex justify-content-between mt-3', style: 'font-weight:700; color:#ec6090;' });
      totalRow.appendChild(createEl('div', {}, 'Total'));
      totalRow.appendChild(createEl('div', {}, '$' + (summary.total ? parseFloat(summary.total).toFixed(2) : '0.00')));
      cartCard.appendChild(totalRow);

      right.appendChild(cartCard);

      row.appendChild(left);
      row.appendChild(right);
      app.appendChild(row);

      // Interactivity: payment method toggle
      document.querySelectorAll('input[name="paymentMethod"]').forEach(r => r.addEventListener('change', function() {
        const showCard = (this.value === 'pm_card');
        document.getElementById('cardFields').style.display = showCard ? 'block' : 'none';
      }));

      // Initialize visibility and country hints
      document.getElementById('cardFields').style.display = document.getElementById('pm_card').checked ? 'block' : 'none';
      const countrySelectEl = document.getElementById('billingCountry');
      function updatePhoneHint() {
        const country = getCountryData(countrySelectEl.value);
        document.getElementById('billingPhone').placeholder = country.placeholder;
        document.getElementById('hintBillingPhone').textContent = 'Example: ' + country.placeholder;
      }
      countrySelectEl.addEventListener('change', updatePhoneHint);
      updatePhoneHint();

      // Real-time simple validation clearers
      function clearError(elId) { const e = document.getElementById(elId); if (e) e.style.display = 'none'; }
      nameInput.addEventListener('input', () => clearError('errBillingName'));
      emailInput.addEventListener('input', () => clearError('errBillingEmail'));
      phoneInput.addEventListener('input', () => clearError('errBillingPhone'));
      cardNumberInput.addEventListener('input', () => clearError('errCardNumber'));

      payBtn.addEventListener('click', function() {
        // Validate fields
        let valid = true;
        const name = document.getElementById('billingName').value.trim();
        const email = document.getElementById('billingEmail').value.trim();
        const countryCode = document.getElementById('billingCountry').value;
        const phone = document.getElementById('billingPhone').value.trim();
        const method = document.querySelector('input[name="paymentMethod"]:checked').value;

        // Name
        if (!name || !validateName(name)) {
          const el = document.getElementById('errBillingName'); el.textContent = 'Please enter a valid name (letters, spaces, apostrophes allowed).'; el.style.display = 'block'; valid = false;
        }

        // Email
        if (!email || !validateEmail(email)) {
          const el = document.getElementById('errBillingEmail'); el.textContent = 'Please enter a valid email (example@example.com).'; el.style.display = 'block'; valid = false;
        }

        // Phone
        if (phone) {
          if (!validatePhone(phone, countryCode)) {
            const el = document.getElementById('errBillingPhone'); el.textContent = 'Phone number does not match the selected country format.'; el.style.display = 'block'; valid = false;
          }
        } else {
          const el = document.getElementById('errBillingPhone'); el.textContent = 'Please enter your phone number.'; el.style.display = 'block'; valid = false;
        }

        // Card validation only when card method selected
        if (method === 'pm_card') {
          const cardVal = document.getElementById('cardNumber').value.trim();
          if (!cardVal || !validateCardNumber(cardVal)) {
            const el = document.getElementById('errCardNumber'); el.textContent = 'Please enter a valid card number.'; el.style.display = 'block'; valid = false;
          }
        }

        if (!valid) return;

        // Build payload for server
        const payload = {
          name, email, address: document.getElementById('billingAddress').value.trim(), country: countryCode, phone, method
        };

        payBtn.disabled = true;
        payBtn.textContent = 'Processing...';

        // Send to server endpoint
        fetch('process_checkout.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        })
        .then(response => {
          return response.json().then(data => ({
            status: response.status,
            data: data
          }));
        })
        .then(result => {
          if (result.data.success) {
            alert('Payment successful! Your order has been created and cart cleared. Thank you for your purchase!');
            window.location.href = 'shop.php';
          } else {
            const errorMsg = result.data.message || 'Unknown error occurred';
            alert('Payment failed: ' + errorMsg);
            console.error('Payment error:', result);
            payBtn.disabled = false;
            payBtn.textContent = 'Pay ' + (summary.total ? ('$' + parseFloat(summary.total).toFixed(2)) : '');
          }
        })
        .catch(error => {
          alert('Error processing payment: ' + error.message);
          console.error('Fetch error:', error);
          payBtn.disabled = false;
          payBtn.textContent = 'Pay ' + (summary.total ? ('$' + parseFloat(summary.total).toFixed(2)) : '');
        });
      });
    }

    document.addEventListener('DOMContentLoaded', function() {
      buildCheckoutApp();
    });
  </script>
</body>
</html>

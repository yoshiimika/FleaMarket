document.addEventListener('DOMContentLoaded', () => {
    const paymentMethodSelect = document.getElementById('payment_method_select');
    const selectedPaymentMethodDisplay = document.getElementById('selected_payment_method');

    if (paymentMethodSelect) {
        paymentMethodSelect.addEventListener('change', (event) => {
            const selectedPaymentMethod = event.target.value;

            if (selectedPaymentMethod === 'card') {
                selectedPaymentMethodDisplay.textContent = 'クレジットカード';
            } else if (selectedPaymentMethod === 'convenience') {
                selectedPaymentMethodDisplay.textContent = 'コンビニ払い';
            } else {
                selectedPaymentMethodDisplay.textContent = '選択してください';
            }
        });
    }
});

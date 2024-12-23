/******/ (() => { // webpackBootstrap
/*!**********************************!*\
  !*** ./resources/js/purchase.js ***!
  \**********************************/
document.addEventListener('DOMContentLoaded', function () {
  var paymentMethodSelect = document.getElementById('payment_method_select');
  var selectedPaymentMethodDisplay = document.getElementById('selected_payment_method');
  if (paymentMethodSelect) {
    paymentMethodSelect.addEventListener('change', function (event) {
      var selectedPaymentMethod = event.target.value;
      if (selectedPaymentMethod === 'card') {
        selectedPaymentMethodDisplay.textContent = 'カード支払い';
      } else if (selectedPaymentMethod === 'convenience') {
        selectedPaymentMethodDisplay.textContent = 'コンビニ払い';
      } else {
        selectedPaymentMethodDisplay.textContent = '選択してください';
      }
    });
  }
});
/******/ })()
;
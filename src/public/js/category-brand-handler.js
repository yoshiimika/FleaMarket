/******/ (() => { // webpackBootstrap
/*!************************************************!*\
  !*** ./resources/js/category-brand-handler.js ***!
  \************************************************/
document.querySelectorAll('.sell-form__category-input').forEach(function (checkbox) {
  checkbox.addEventListener('change', function () {
    var parentLabel = this.closest('.sell-form__category');
    var brandSelect = document.getElementById('brand-select');
    if (this.checked) {
      parentLabel.classList.add('sell-form__category--active');
    } else {
      parentLabel.classList.remove('sell-form__category--active');
    }
    var selectedCategories = Array.from(document.querySelectorAll('.sell-form__category-input:checked')).map(function (input) {
      return input.value;
    });
    if (selectedCategories.length === 0) {
      brandSelect.innerHTML = '<option value="" disabled selected>カテゴリを選択してください</option>';
      brandSelect.disabled = true;
      return;
    }
    fetch("/api/categories/".concat(selectedCategories.join(','), "/brands")).then(function (response) {
      return response.json();
    }).then(function (data) {
      brandSelect.innerHTML = '<option value="" disabled selected>選択してください</option>';
      if (data.length === 0) {
        brandSelect.innerHTML += '<option value="" disabled>該当するブランドがありません</option>';
        brandSelect.disabled = true;
        return;
      }
      var uniqueBrands = Array.from(new Set(data.map(function (brand) {
        return JSON.stringify(brand);
      }))).map(function (str) {
        return JSON.parse(str);
      });
      uniqueBrands.forEach(function (brand) {
        var option = document.createElement('option');
        option.value = brand.id;
        option.textContent = brand.name;
        brandSelect.appendChild(option);
      });
      brandSelect.disabled = false;
    })["catch"](function (error) {
      console.error('エラー:', error);
      brandSelect.innerHTML = '<option value="" disabled>ブランドを取得できませんでした</option>';
      brandSelect.disabled = true;
    });
  });
});
/******/ })()
;
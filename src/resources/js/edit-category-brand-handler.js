document.querySelectorAll('.edit-form__category-input').forEach((checkbox) => {
    checkbox.addEventListener('change', function () {
        const parentLabel = this.closest('.edit-form__category');
        const brandSelect = document.getElementById('brand-select');

        if (this.checked) {
            parentLabel.classList.add('edit-form__category--active');
        } else {
            parentLabel.classList.remove('edit-form__category--active');
        }

        const selectedCategories = Array.from(
            document.querySelectorAll('.edit-form__category-input:checked')
        ).map(input => input.value);

        if (selectedCategories.length === 0) {
            brandSelect.innerHTML = '<option value="" disabled selected>カテゴリーを選択してください</option>';
            brandSelect.disabled = true;
            return;
        }

        fetch(`/api/categories/${selectedCategories.join(',')}/brands`)
            .then(response => response.json())
            .then(data => {
                brandSelect.innerHTML = '<option value="" disabled selected>選択してください</option>';

                if (data.length === 0) {
                    brandSelect.innerHTML += '<option value="" disabled>該当するブランドがありません</option>';
                    brandSelect.disabled = true;
                    return;
                }

                const uniqueBrands = Array.from(new Set(data.map(brand => JSON.stringify(brand)))).map(str => JSON.parse(str));
                uniqueBrands.forEach(brand => {
                    const option = document.createElement('option');
                    option.value = brand.id;
                    option.textContent = brand.name;
                    brandSelect.appendChild(option);
                });

                brandSelect.disabled = false;
            })
            .catch(error => {
                console.error('エラー:', error);
                brandSelect.innerHTML = '<option value="" disabled>ブランドを取得できませんでした</option>';
                brandSelect.disabled = true;
            });
    });
});

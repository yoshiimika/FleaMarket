    document.getElementById('image').addEventListener('change', function (event) {
        const file = event.target.files[0];
        const previewContainer = document.querySelector('.edit-form__image-preview');
        const previewImage = document.getElementById('image-preview');
        const uploadButton = document.querySelector('.edit-form__image-button');
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                previewImage.src = e.target.result;
                previewContainer.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            previewContainer.style.display = 'none';
        }
    });
    window.addEventListener('DOMContentLoaded', function () {
        const initialImage = document.getElementById('image-preview');
        const previewContainer = document.querySelector('.edit-form__image-preview');
        if (initialImage && initialImage.src) {
            previewContainer.style.display = 'block';
        }
    });

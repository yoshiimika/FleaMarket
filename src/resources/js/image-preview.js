document.getElementById('image').addEventListener('change', function (event) {
    const file = event.target.files[0];
    const previewContainer = document.querySelector('.sell-form__image-preview');
    const previewImage = document.getElementById('image-preview');
    const uploadButton = document.querySelector('.sell-form__image-button');

    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            previewImage.src = e.target.result;
            previewContainer.style.display = 'block';
            uploadButton.style.display = 'none';
        };
        reader.readAsDataURL(file);
    } else {
        previewContainer.style.display = 'none';
        uploadButton.style.display = 'inline-block';
    }
});

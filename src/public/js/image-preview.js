/******/ (() => { // webpackBootstrap
/*!***************************************!*\
  !*** ./resources/js/image-preview.js ***!
  \***************************************/
document.getElementById('image').addEventListener('change', function (event) {
  var file = event.target.files[0];
  var previewContainer = document.querySelector('.sell-form__image-preview');
  var previewImage = document.getElementById('image-preview');
  var uploadButton = document.querySelector('.sell-form__image-button');
  if (file) {
    var reader = new FileReader();
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
/******/ })()
;
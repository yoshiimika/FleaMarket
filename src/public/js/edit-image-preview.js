/******/ (() => { // webpackBootstrap
/*!********************************************!*\
  !*** ./resources/js/edit-image-preview.js ***!
  \********************************************/
document.getElementById('image').addEventListener('change', function (event) {
  var file = event.target.files[0];
  var previewContainer = document.querySelector('.edit-form__image-preview');
  var previewImage = document.getElementById('image-preview');
  var uploadButton = document.querySelector('.edit-form__image-button');
  if (file) {
    var reader = new FileReader();
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
  var initialImage = document.getElementById('image-preview');
  var previewContainer = document.querySelector('.edit-form__image-preview');
  if (initialImage && initialImage.src) {
    previewContainer.style.display = 'block';
  }
});
/******/ })()
;
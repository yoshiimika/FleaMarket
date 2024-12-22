/******/ (() => { // webpackBootstrap
/*!****************************************!*\
  !*** ./resources/js/avatar-preview.js ***!
  \****************************************/
document.addEventListener('DOMContentLoaded', function () {
  var avatarInput = document.getElementById('avatar');
  var avatarPreview = document.getElementById('avatar-preview');
  if (avatarInput) {
    avatarInput.addEventListener('change', function () {
      var file = this.files[0];
      if (file) {
        var reader = new FileReader();
        reader.onload = function (e) {
          if (avatarPreview) {
            avatarPreview.src = e.target.result;
            avatarPreview.style.display = 'block';
          }
        };
        reader.readAsDataURL(file);
      }
    });
  }
});
/******/ })()
;
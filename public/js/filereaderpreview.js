const fileInput = document.getElementById("file-input");
const imagePreview = document.getElementById("image-preview");

fileInput.addEventListener("change", function() {
  const reader = new FileReader();
  reader.onload = function() {
    imagePreview.src = reader.result;
  }
  reader.readAsDataURL(fileInput.files[0]);
});
// ---------------------------Image preview
document.addEventListener('DOMContentLoaded', () => {
  const imageInput = document.getElementById('imageInput');
  const previewImage = document.getElementById('previewImage');
  
  imageInput.onchange = e => {
    previewImage.src = URL.createObjectURL(e.target.files[0]);
    previewImage.classList.remove('d-none');
  };
});
// function addToCart(){
// var cart = document.getElementById("cart");
// cart.innerHTML += '<div class="cart">1 Nom Banh Juk 0.75c <i class="fa-solid fa-xmark px-5" ></i></div>';
// }



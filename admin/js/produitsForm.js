document.addEventListener("DOMContentLoaded", () => {
  // Votre code JavaScript ici

  // Global variables
  const imgInputHelper = document.getElementById("image_produit");
  const imgContainer = document.querySelector(".custom__image-container");
  let imgFile = null;

  const addImgHandler = () => {
    const file = imgInputHelper.files[0];
    if (!file) return;

    // Generate img preview
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = () => {
      const newImg = document.createElement("img");
      newImg.src = reader.result;
      imgContainer.innerHTML = ""; // Remove previous image
      imgContainer.appendChild(newImg);
    };

    // Store img file
    imgFile = file;

    // Reset image input
    imgInputHelper.value = "";
    return;
  };

  const customFormSubmitHandler = (ev) => {
    ev.preventDefault();
    const formData = new FormData();
    formData.append("image_produit", imgFile);

    const container = document.getElementById("custom__print-files");
    container.innerHTML = `File: ${imgFile.name}, size: ${imgFile.size}`;
    const form = document.querySelector(".custom__form");
    form.submit();
  };

  imgInputHelper.addEventListener("change", addImgHandler);
  document
    .querySelector(".custom__form")
    .addEventListener("submit", customFormSubmitHandler);
});

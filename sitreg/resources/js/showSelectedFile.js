let selectedFiles = []; // Placeholder for selected files

const setSelectedFiles = (newFiles) => {
  selectedFiles = newFiles;
  renderSelectedFiles();
};

const renderSelectedFiles = () => {
  const selectedFilesDiv = document.getElementById("selected-files");
  selectedFilesDiv.innerHTML = "";

  selectedFiles.forEach((file, index) => {
    const image = document.createElement("img");
    image.src = URL.createObjectURL(file);
    image.alt = `Selected File ${index}`;
    image.className = "w-32 h-40 object-cover rounded-lg my-1";

    const deleteButton = document.createElement("i");
    deleteButton.className =
      "fa fa-times rounded-full bg-opacity-50 bg-black text-lg text-white border border-white px-2";
    deleteButton.setAttribute("aria-hidden", "true");
    deleteButton.onclick = () => removeSelectedImage(index);

    const buttonContainer = document.createElement("div");
    buttonContainer.className =
      "text-center cursor-pointer grid place-content-center relative right-[2.5rem] bottom-[10rem] pt-1";
    buttonContainer.appendChild(deleteButton);

    const container = document.createElement("div");
    container.appendChild(image);
    container.appendChild(buttonContainer);

    selectedFilesDiv.appendChild(container);
  });
};

const removeSelectedImage = (index) => {
  const newFiles = [...selectedFiles];
  newFiles.splice(index, 1);
  setSelectedFiles(newFiles);
};

const onFileChange = (event) => {
  setSelectedFiles(Array.from(event.target.files));
};

// Attach event listener to the file input
const fileInput = document.getElementById("file-input");
fileInput.addEventListener("change", onFileChange);

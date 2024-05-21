document.getElementById('upload-circle').addEventListener('click', function () {
    document.getElementById('picture-upload').click();
});

function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function () {
        const output = document.getElementById('image-preview');
        const circle = document.getElementById('upload-circle');
        const uploadSection = document.getElementById('upload-section');

        output.src = reader.result;
        output.style.display = 'block';
        circle.style.display = 'none';
        uploadSection.classList.remove('align-items-baseline');
        uploadSection.classList.add('align-items-center');
    };
    reader.readAsDataURL(event.target.files[0]);
}

document.getElementById('picture-upload').addEventListener('change', previewImage);

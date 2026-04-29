class ImageSlider {
    constructor(imageElementId, imagesArray) {
        this.sliderImage = document.getElementById(imageElementId);
        this.images = imagesArray;
        this.currentIndex = 0;
        this.updateBars();
    }

    changeImage(direction) {
        this.currentIndex += direction;

        // Loop logic
        if (this.currentIndex >= this.images.length) {
            this.currentIndex = 0;
        } else if (this.currentIndex < 0) {
            this.currentIndex = this.images.length - 1;
        }

        // Apply new source
        if (this.sliderImage) {
            this.sliderImage.src = this.images[this.currentIndex];
        }

        this.updateBars();
    }

    updateBars() {
        document.querySelectorAll('.bar').forEach((bar, index) => {
            if (index === this.currentIndex) {
                bar.classList.add('active');
            } else {
                bar.classList.remove('active');
            }
        });
    }
}

// We will initialize this in the Blade file to handle the PHP asset paths
let aboutSlider;

function openRefundModal() {
    document.getElementById('userRefundModal').style.display = 'flex';
}

function closeRefundModal() {
    document.getElementById('userRefundModal').style.display = 'none';
}

// Close if user clicks outside the gold box
window.onclick = function(event) {
    let modal = document.getElementById('userRefundModal');
    if (event.target == modal) {
        closeRefundModal();
    }
}
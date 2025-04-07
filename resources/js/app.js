import '../css/all.min.css';
import 'keen-slider/keen-slider.min.css';
import KeenSlider from 'keen-slider';


document.addEventListener("DOMContentLoaded", function () {
    const animationDuration = 1000;
    const autoplayDelay = 10000;

    const imageSlider = new KeenSlider(
        "#image-slider",
        {
            loop: true,
            defaultAnimation: {
                duration: animationDuration,
            },
            slides: {
                perView: 1,
            },
            renderMode: "performance",
        },
        [
            (slider) => {
                let timeout;
                let mouseOver = false;
                function clearNextTimeout() {
                    clearTimeout(timeout);
                }
                function nextTimeout() {
                    clearTimeout(timeout);
                    if (mouseOver) return;
                    timeout = setTimeout(() => {
                        slider.next();
                    }, autoplayDelay);
                }
                slider.on("created", () => {
                    slider.container.addEventListener("mouseover", () => {
                        mouseOver = true;
                        clearNextTimeout();
                    });
                    slider.container.addEventListener("mouseout", () => {
                        mouseOver = false;
                        nextTimeout();
                    });
                    nextTimeout();
                });
                slider.on("dragStarted", clearNextTimeout);
                slider.on("animationEnded", nextTimeout);
                slider.on("updated", nextTimeout);
            },
        ]
    );

    document.querySelector("#slider-wrapper").addEventListener("click", () => {
        imageSlider.next();
    });
});

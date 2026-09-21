window.addEventListener("scroll", () => {
    if (window.scrollY > 785) {
        document.querySelector(".header").classList.add("header-fixed");
    } else {
        document.querySelector(".header").classList.remove("header-fixed");
    }
});
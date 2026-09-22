window.addEventListener("scroll", () => {
    if (window.scrollY > 205) {
        document.querySelector(".header").classList.add("header-fixed");
    } else {
        document.querySelector(".header").classList.remove("header-fixed");
    }
});
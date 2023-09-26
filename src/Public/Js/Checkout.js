const deliverySelect = document.querySelector(".deliverySelect")
const deliveryPrice = document.querySelector(".deliveryPrice")
const togetherPrice = document.querySelector(".togetherPrice")
const cartSum = document.querySelector(".cartSum")


deliverySelect.addEventListener("change", function () {
    const selectedOption = deliverySelect.options[deliverySelect.selectedIndex];
    const selectedPrice = +selectedOption.getAttribute("data-price");
    if(!selectedPrice) {
        return
    }
    deliveryPrice.textContent = selectedPrice + " $";
    const together = +cartSum.textContent.match(/\d+/)[0] + +selectedPrice
    togetherPrice.textContent = together + " $"
});

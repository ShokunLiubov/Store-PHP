const deliverySelect = document.querySelector(".select_delivery")
const deliveryPrice = document.querySelector(".delivery_price")
const togetherPrice = document.querySelector(".together_price")
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

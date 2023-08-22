const cartIcon = document.querySelector(".cart")
const addToCart = document.querySelectorAll(".addToCart")

cartIcon.addEventListener('click', async () => {
    await CartService.showCart()
})

addToCart.forEach(button => {
    button.addEventListener('click', async function (event) {
        event.preventDefault()
        const productId = event.target.getAttribute('product-id')
        await CartService.addToCart(productId)
    })
})

function cartAttachEventHandlers() {

    const decrement = document.querySelectorAll(".decrement")
    const increment = document.querySelectorAll(".increment")
    const remove = document.querySelectorAll(".remove")
    const hideCarts = document.querySelectorAll(".hideCart");
    const cartModal = document.querySelector(".cartModal");

    cartModal.addEventListener('click', e => e.stopPropagation());

    hideCarts.forEach(hideCart => {
        hideCart.addEventListener('click', async () => {
            await CartService.hideCart()
        });
    });

    decrement.forEach(button => {
        button.addEventListener('click', async function (event) {
            const productId = event.target.getAttribute('product-id');
            await CartService.decrement(productId)
        });
    });

    increment.forEach(button => {
        button.addEventListener('click', async function (event) {
            const productId = event.target.getAttribute('product-id');
            await CartService.increment(productId)
        });
    });


    remove.forEach(button => {
        button.addEventListener('click', async function (event) {
            const productId = event.target.getAttribute('product-id');
            await CartService.remove(productId)
        });
    })

}

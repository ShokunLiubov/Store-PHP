const cartIcon = document.querySelector(".cart")
const addToCart = document.querySelectorAll(".addToCart")

addToCart.forEach(button => {
    button.addEventListener('click', function (event) {
        event.preventDefault()
        const productId = event.target.getAttribute('product-id')
        fetch(`http://localhost/make-up/cart/${productId}`, {
            method: 'GET',
        })
            .then(response => response.text())
            .then(data => {
                document.querySelector('.cart-content').innerHTML = data

                const hideCarts = document.querySelectorAll(".hideCart")
                const cartModal = document.querySelector(".cartModal")

                // stops hiding the cart when clicking on cartModal
                cartModal.addEventListener('click', (e) => {
                    e.stopPropagation()
                })

                hideCarts.forEach(hideCart => {
                    hideCart.addEventListener('click', (e) => {
                        fetch('http://localhost/make-up/cart/hide', {
                            method: 'GET',
                        })
                            .then(response => response.text())
                            .then(() => {
                                document.querySelector('.cart-content').innerHTML = ''
                            })
                            .catch(error => {
                                console.error('An error occurred:', error)
                            })
                    })
                })

            })
            .catch(error => {
                console.error('An error occurred:', error)
            })
    })
})

cartIcon.addEventListener('click', () => {
    fetch('http://localhost/make-up/cart/show', {
        method: 'GET',
    })
        .then(response => response.text())
        .then(data => {
            document.querySelector('.cart-content').innerHTML = data

            const hideCarts = document.querySelectorAll(".hideCart")
            const cartModal = document.querySelector(".cartModal")

            // stops hiding the cart when clicking on cartModal
            cartModal.addEventListener('click', (e) => {
                e.stopPropagation()
            })

            hideCarts.forEach(hideCart => {
                hideCart.addEventListener('click', (e) => {
                    fetch('http://localhost/make-up/cart/hide', {
                        method: 'GET',
                    })
                        .then(response => response.text())
                        .then(() => {
                            document.querySelector('.cart-content').innerHTML = ''
                        })
                        .catch(error => {
                            console.error('An error occurred:', error)
                        })
                })
            })

        })
        .catch(error => {
            console.error('An error occurred:', error)
        })
})



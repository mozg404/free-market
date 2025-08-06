import { computed } from 'vue'
import { usePage, useForm } from '@inertiajs/vue3'

/**
 * Реактивный объект корзины
 * @typedef {Object} Cart
 * @property {Object} amount - Итоговые суммы
 * @property {number} amount.current - Текущая сумма
 * @property {number} amount.base - Базовая сумма без скидок
 * @property {number} amount.discount - Сумма со скидкой
 * @property {number} amount.discountPercent - Процент скидки
 * @property {boolean} amount.isDiscount - Есть ли скидка
 * @property {number} amount.benefit - Выгода в рублях
 * @property {number} count - Количество позиций
 * @property {number} quantity - Общее количество товаров
 * @property {Object.<string, CartItem>} items - Товары в корзине
 */

/**
 * Элемент корзины
 * @typedef {Object} CartItem
 * @property {Object} amount - Сумма по позиции
 * @property {number} amount.current
 * @property {number} amount.base
 * @property {number} amount.discount
 * @property {number} amount.discountPercent
 * @property {boolean} amount.isDiscount
 * @property {number} amount.benefit
 * @property {Object} product - Товар
 * @property {number} product.id
 * @property {string} product.name
 * @property {boolean} product.isAvailable
 * @property {Object} product.price
 * @property {number} product.price.current
 * @property {number} product.price.base
 * @property {number} product.price.discount
 * @property {number} product.price.discountPercent
 * @property {boolean} product.price.isDiscount
 * @property {number} product.price.benefit
 * @property {Object} product.previewImage
 * @property {boolean} product.previewImage.isExists
 * @property {string} product.previewImage.url
 * @property {number} product.stockItemsCount - Остаток на складе
 * @property {number} quantity - Количество данного товара
 */

/**
 * Композиция для работы с корзиной
 * @returns {{
 *   cart: import('vue').ComputedRef<Cart>,
 *   inCart: (id: number) => boolean,
 *   getCartItem: (id: number) => CartItem | null,
 *   getCartItemQuantity: (id: number) => number,
 *   addToCart: (id: number) => Promise<void>,
 *   clearCart: () => Promise<void>,
 *   removeFromCart: (id: number) => Promise<void>,
 *   decreaseQuantity: (id: number) => Promise<void>,
 *   form: import('@inertiajs/vue3').useForm<{}>
 * }}
 */
export function useCart() {
    const page = usePage()
    const form = useForm({})
    const cart = computed(() => page.props.cart)

    /**
     * Проверка наличия товара в корзине
     * @param id
     * @returns {boolean}
     */
    const inCart = (id) => {
        return Object.keys(cart.value.items).some(
            key => cart.value.items[key].product.id === id
        )
    }

    /**
     * Получает объект конкретного товара в корзине
     * @param id
     * @returns {*|null}
     */
    const getCartItem = (id) => {
        const foundKey = Object.keys(cart.value.items).find(
            key => cart.value.items[key].product.id === id
        )
        return foundKey ? cart.value.items[foundKey] : null
    }

    /**
     * Возвращает количество позиций конкретного товара в корзине
     * @param id
     * @returns {number|*|number}
     */
    const getCartItemQuantity = (id) => {
        const item = getCartItem(id)
        return item ? item.quantity : 0
    }

    /**
     * Очищает корзину
     */
    const clearCart = () => {
        return form.delete(route('cart.clean'))
    }

    /**
     * Добавляет товар с ID в корзину
     * Или увеличивает количество на 1
     * @param id
     */
    const addToCart = (id) => {
        return form.post(route('cart.items.store', id))
    }

    /**
     * Уменьшает количество товара по ID
     * Или удаляет, если меньше 1
     * @param id
     */
    const decreaseQuantity = (id) => {
        return form.post(route('cart.items.decrease', id))
    }

    /**
     * Полностью удаляет товар из корзины независимо от количества
     * @param id
     */
    const deleteFromCart = (id) => {
        return form.delete(route('cart.items.destroy', id))
    }

    return {
        cart,
        getCartItem,
        getCartItemQuantity,
        inCart,
        clearCart,
        addToCart,
        deleteFromCart,
        decreaseQuantity,
        form
    }
}
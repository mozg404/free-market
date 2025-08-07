import { computed } from 'vue'
import { usePage, useForm } from '@inertiajs/vue3'

/**
 * Реактивный объект корзины
 * @typedef {Object} User
 * @property {number} id - ID
 * @property {number} balance - Баланс
 * @property {string} name - Имя
 */

/**
 * Композиция для работы с корзиной
 * @returns {{
 *   user: import('vue').ComputedRef<User>,
 *   isAuth: boolean,
 * }}
 */
export function useUser() {
    const page = usePage()
    const isAuth = computed(() => page.props.isAuth)
    const user = computed(() => page.props.user)

    return {
        isAuth,
        user
    }
}
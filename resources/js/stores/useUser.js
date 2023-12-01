import { useLocalStorage, useSessionStorage, StorageSerializers } from '@vueuse/core'
import { clear as clearCart } from './useCart'
import { computed, watch } from 'vue'
import Jwt from '../jwt'

export const token = useLocalStorage('token', '')
const userStorage = useSessionStorage('user', {}, { serializer: StorageSerializers.object })
let isRefreshing = false

export const refresh = async function () {
    if (!token.value) {
        userStorage.value = {}
        return false
    }

    if (isRefreshing) {
        console.debug('Refresh canceled, request already in progress...')
        return
    }

    if (Jwt.isJwt(token.value) && Jwt.decode(token.value)?.isExpired()) {
        Notify(window.config.translations.errors.session_expired, 'error')
        await clear()

        return false
    }

    try {
        isRefreshing = true
        // TODO: Migrate to GraphQL?
        userStorage.value = await window.magentoAPI('GET', 'customers/me', {}, { redirectOnExpiration: false }) || {}
        isRefreshing = false
    } catch (error) {
        if (error instanceof SessionExpired) {
            await clear()
        } else {
            throw error
        }
    }
}

export const clear = async function () {
    token.value = ''
    userStorage.value = {}
    await clearCart()
}

export const user = computed({
    get() {
        if (token.value && !userStorage.value?.id) {
            refresh()
        }

        return userStorage.value
    },
    set(value) {
        userStorage.value = value
    },
})

// If token gets changed or emptied we should update the user.
watch(token, refresh)

export default () => user

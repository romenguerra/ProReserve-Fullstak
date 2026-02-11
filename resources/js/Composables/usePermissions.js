import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

/**
 * Composable para manejar roles y permisos en componentes Vue
 * 
 * @example
 * import { usePermissions } from '@/Composables/usePermissions';
 * 
 * const { hasRole, hasAnyRole, can } = usePermissions();
 * 
 * if (hasRole('admin')) {
 *   // código solo para admin
 * }
 */
export function usePermissions() {
    const page = usePage();
    
    const auth = computed(() => page.props.auth);
    const user = computed(() => auth.value.user);
    const roles = computed(() => user.value?.roles || []);
    const permissions = computed(() => user.value?.permissions || []);

    /**
     * Verifica si el usuario tiene un rol específico
     * @param {string} role - Nombre del rol
     * @returns {boolean}
     */
    const hasRole = (role) => {
        return roles.value.includes(role);
    };

    /**
     * Verifica si el usuario tiene cualquiera de los roles especificados
     * @param {string[]} roleList - Array de nombres de roles
     * @returns {boolean}
     */
    const hasAnyRole = (roleList) => {
        return roleList.some(role => roles.value.includes(role));
    };

    /**
     * Verifica si el usuario tiene todos los roles especificados
     * @param {string[]} roleList - Array de nombres de roles
     * @returns {boolean}
     */
    const hasAllRoles = (roleList) => {
        return roleList.every(role => roles.value.includes(role));
    };

    /**
     * Verifica si el usuario tiene un permiso específico
     * @param {string} permission - Nombre del permiso
     * @returns {boolean}
     */
    const can = (permission) => {
        return permissions.value.some(p => p === permission);
    };

    /**
     * Verifica si el usuario tiene cualquiera de los permisos especificados
     * @param {string[]} permissionList - Array de nombres de permisos
     * @returns {boolean}
     */
    const canAny = (permissionList) => {
        return permissionList.some(permission => 
            permissions.value.includes(permission)
        );
    };

    /**
     * Verifica si el usuario tiene todos los permisos especificados
     * @param {string[]} permissionList - Array de nombres de permisos
     * @returns {boolean}
     */
    const canAll = (permissionList) => {
        return permissionList.every(permission => 
            permissions.value.includes(permission)
        );
    };

    /**
     * Verifica si el usuario es administrador
     * @returns {boolean}
     */
    const isAdmin = () => {
        return hasRole('admin');
    };

    /**
     * Verifica si el usuario es cliente
     * @returns {boolean}
     */
    const isCliente = () => {
        return hasRole('cliente');
    };

    return {
        user,
        roles,
        permissions,
        hasRole,
        hasAnyRole,
        hasAllRoles,
        can,
        canAny,
        canAll,
        isAdmin,
        isCliente,
    };
}

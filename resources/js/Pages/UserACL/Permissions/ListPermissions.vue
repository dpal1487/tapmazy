<script>

import { defineComponent } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';
export default defineComponent({
    props: ['permissions'],
    data() {
        return {
            permissionsToShow: [],
            remainingPermissionCount: 0,
        }
    },
    components: {
        Link
    },
    methods: {
        showMore() {
            this.permissionsToShow = this.permissions;
        },
        showLess() {
            this.permissionsToShow = this.permissions.slice(0, 5);
            this.remainingPermissionCount = this.permissions.length - 5;
        }
    },
    mounted() {
        if (this.permissions?.length > 5) {
            this.permissionsToShow = this.permissions.slice(0, 5);
            this.remainingPermissionCount = this.permissions.length - 5;
        } else {
            this.permissionsToShow = this.permissions;
        }
    }

})
</script>
<template>
    <div class="d-flex flex-column text-gray-600">
        <div class="d-flex align-items-center py-2" v-for="permission in permissionsToShow">
            <span class="bullet bg-primary me-3"></span>
            {{ permission?.description }}
        </div>
        <button @click="permissionsToShow.length > 5 ? showLess() : showMore()" v-if="remainingPermissionCount !== 0" class='d-flex align-items-center py-2'>
            <span class='bullet bg-primary me-3'></span>
            <em v-if="permissionsToShow.length > 5">view less...</em>
            <em v-else>view {{ remainingPermissionCount }} more permissions...</em>
        </button>
    </div>
</template>

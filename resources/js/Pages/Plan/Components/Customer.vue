<script>
import { defineComponent } from 'vue';
import { Link } from "@inertiajs/inertia-vue3";
export default defineComponent({
    props: {
        user: {
            user: Object,
            default: Object,
        }
    },
    emits: ['onhide', 'search'],

    data() {
        return {
        }
    },

    components: {
        Link,
    },
    methods: {
    }
})
</script>

<template>
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <h2 class="fw-bold">Customer</h2>
            </div>
        </div>
        <div class="card-body">

            <div class="d-flex align-items-center p-3 mb-2">
                <div class="symbol symbol-60px symbol-circle me-3">
                    <img alt="Pic" src="/assets/media/avatars/300-5.jpg" />
                </div>
                <div class="d-flex flex-column">
                    <Link :href="`account`" class="fs-4 fw-bold text-gray-900 text-hover-primary me-2">
                        {{ user.first_name + ' ' + user.last_name }}</Link>
                    <span class="fw-semibold text-gray-600">{{user.email }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent, ref } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import "vue-loading-overlay/dist/css/index.css";
export default defineComponent({
    props: ["message", "user"],
    data() {
        return {
            title: "Messaging",
        };
    },
    components: {
        AppLayout,
        Link,
        Head,
    },
    methods: {},
});
</script>
<template>
    <div v-if="$page.props.user.user?.id == user.id" class="d-flex justify-content-start mb-1">
        <div class="d-flex flex-column align-items-end">
            <div class="p-2 w-11 rounded bg-light-info text-gray-900 fw-semibold mw-lg-400px text-end">
                Typing
                <!-- {{ typingUser.name }} is typing... -->
                <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 16 16" height="1em" width="1em"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z">
                    </path>
                </svg>
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent, ref } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import 'vue-loading-overlay/dist/css/index.css';
import SectionLoader from '@/Components/SectionLoader.vue';

export default defineComponent({
    props: ["user", "conversation", "isLoading"],
    data() {
        return {
            title: "Messaging",
        };
    },
    components: {
        AppLayout,
        Link,
        Head,
        SectionLoader
    },
});
</script>
<template>
    <div class="d-flex flex-stack py-4">
        <div class="d-flex align-items-center" v-if="isLoading">
            <SectionLoader :width="40" :height="40" />
        </div>
        <div class="d-flex align-items-center" v-else>
            <div class="symbol  symbol-45px symbol-circle "><span
                    class="symbol-label  bg-light-danger text-danger fs-6 fw-bolder ">
                    {{ user?.first_name.substring(0, 1).toUpperCase() }}
                </span>
            </div>
            <div class="ms-5">
                <Link :href="`/message/${user.id}`"
                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2 text-capitalize">{{
                        user?.first_name }}
                {{ user?.last_name }}</Link>
            </div>
        </div>
    </div>
</template>

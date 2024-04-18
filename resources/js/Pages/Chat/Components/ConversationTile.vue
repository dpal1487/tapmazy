<script>
import { defineComponent, ref } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime"
dayjs.extend(relativeTime)
export default defineComponent({
    props: ["user", "message", "conversation"],

    data() {
        return {
            time_ago: "",
        };
    },
    components: {
        AppLayout,
        Link,
        Head,
    },
    methods: {
        time() {
            const created_at = this.user?.last_message?.time_ago;
            this.time_ago = dayjs().to(created_at);
        },
    },
    mounted() {
        setInterval(this.time, 1000);
    }
});
</script>
<template>
    <div class="d-flex flex-stack py-2">
        <div class="d-flex align-items-center">
            <div class="symbol  symbol-45px symbol-circle "><span
                    class="symbol-label  bg-light-danger text-danger fs-6 fw-bolder ">
                    {{ user?.user?.first_name.substring(0, 1).toUpperCase() }}
                </span>
            </div>
            <div class="ms-5">
                <Link :href="`/message/${user?.user?.id}`"
                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2 text-capitalize">
                {{ user?.user?.first_name }} {{ user?.user?.last_name }}
                </Link>
                <div class="fw-semibold text-muted">{{ user?.last_message?.message }}</div>
            </div>
        </div>
        <div class="d-flex flex-column align-items-end ms-2">
            <span class="text-muted fs-8 mb-1">{{ time_ago }}</span>
            <span class="badge badge-sm badge-circle badge-light-success">{{ user?.unseen_counter }}</span>
        </div>
    </div>
</template>

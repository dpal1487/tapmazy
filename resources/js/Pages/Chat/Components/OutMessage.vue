<script>
import { defineComponent, ref } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime"
dayjs.extend(relativeTime)
export default defineComponent({
    props: ["message"],
    data() {
        return {
            title: "Messaging",
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
            const created_at = this.message.time_ago;
            this.time_ago = dayjs().to(created_at);
        },
    },
    mounted() {
        setInterval(this.time, 1000);
    }


});
</script>
<template>
    <div class="d-flex justify-content-end mb-1 ">
        <div class="d-flex flex-column align-items-end">
            <!-- <div class="d-flex align-items-center mb-2">
                <div class="me-3">
                    <Link href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary ms-1">You</Link>

                </div>
                <div class="symbol  symbol-35px symbol-circle " v-if="message?.user?.avatar">
                    <img alt="Pic" :src="message?.user?.avatar">
                    <img alt="Pic" src="/assets/media/avatars/300-1.jpg">
                </div>
                <div class="symbol symbol-45px symbol-circle me-5" v-else><span
                        class="symbol-label  bg-light-danger text-danger fs-6 fw-bolder ">
                        {{ message?.user?.first_name.substring(0, 1).toUpperCase() }}
                    </span>
                </div>
            </div> -->
            <div class="p-2 mx-12 d-flex rounded bg-light-primary text-gray-800 fw-semibold mw-lg-400px text-start"
                data-kt-element="message-text">
                <div class="flex-root ">{{ message.message }}</div>
                <span class="d-flex justify-content-end text-muted fs-7 align-items-end text-end h-100 ms-5">{{ time_ago
                }}</span>
            </div>
        </div>
    </div>
</template>

<style type="text/css">
.notification:hover {
    background-color: rgb(243, 243, 243);
    cursor: pointer;
    transition: all .3s ease;
    border-radius: 10px;
}
</style>

<script>
import { defineComponent } from "vue"
import { Link } from "@inertiajs/inertia-vue3";
import AppLayout from "@/Layouts/AppLayout.vue"
export default defineComponent({
    props: ['notifications'],
    components: {
        AppLayout,
        Link
    }
});
</script>
<template>
    <app-layout title="Notifications">
        <template #breadcrumb>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">Notifications</li>
        </template>
        <div class="card p-2">
            <div class="card-body notification" v-for="notification in notifications?.data">
                <div class="d-flex align-items-center ">
                    <Link :href="`project/${JSON.parse(notification.data).id}`">
                    <div class="symbol symbol-circle symbol-45px pe-6" data-bs-toggle="tooltip" data-bs-boundary="window"
                        data-bs-placement="top" title="User Profile">
                        <img src="/assets/media/avatars/300-1.jpg" alt="img" />
                    </div>
                    </Link>
                    <div class="">
                        <Link :href="`project/${JSON.parse(notification.data).id}`">
                        <div class="fs-5 fw-semibold mb-2">{{ JSON.parse(notification.data).activity }}
                        </div>
                        </Link>
                        <div class="d-flex align-items-center mt-1 fs-6">
                            <div class="text-muted me-2 fs-7">{{ notification.date }} Sent at {{
                                notification?.time }} by {{ JSON.parse(notification.data).user_name }}</div>
                            <!-- <div v-if="notification.profile" class="symbol symbol-circle symbol-25px"
                                            data-bs-toggle="tooltip" data-bs-boundary="window" data-bs-placement="top"
                                            :title="notification.user">
                                            <img :src="notification.profile" alt="img" />
                                        </div>
                                        <div v-else class="symbol symbol-circle symbol-25px" data-bs-toggle="tooltip"
                                            data-bs-boundary="window" data-bs-placement="top" title="User Profile">
                                            <img src="/assets/media/avatars/300-1.jpg" alt="img" />
                                        </div> -->
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </app-layout>
</template>



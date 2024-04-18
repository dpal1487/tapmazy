<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";

export default defineComponent({
    props: ['service'],
    data() {
        return {
            title: "Service Overview",
        };
    },
    components: {
        AppLayout,
        Link,
        Head,
    },
    methods: {
    },

});
</script>
<template>
    <Head :title="title" />
    <AppLayout :title="title">
        <template #breadcrumb>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item">
                <Link href="/services" class="text-muted text-hover-primary">Services</Link>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
                {{ title }}
            </li>
        </template>

        <div class="app-content flex-column-fluid ">
            <div class="card mb-5 mb-xl-10">
                <div class="card-header cursor-pointer">
                    <div class="card-title m-0">
                        <h3 class="fw-bold m-0"> Service Details</h3>
                    </div>
                </div>
                <div class="card-body p-9">
                    <div class="row">
                        <div class="row mb-7 align-items-center">
                            <label class="col-lg-4  fw-bold fs-5 text-gray-700">Image</label>
                            <div class="col-lg-8">
                                <div v-if="service.data.image">
                                    <div class="symbol symbol-160px me-5">
                                        <img alt="Logo" :src="service.data.image?.url">
                                    </div>
                                </div>
                                <div v-else>
                                    <div class="symbol symbol-60px me-5">
                                        <img alt="Logo" src="/assets/images/comingsoon.png">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-bold fs-5 text-gray-700">Name</label>
                            <div class="col-lg-8">
                                <div class="fw-bold fs-6 text-gray-600 text-capitalize">{{
                                    service.data?.name }} </div>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-bold fs-5 text-gray-700">Page Name</label>
                            <div class="col-lg-8">
                                <div class="fw-bold fs-6 text-gray-500 text-capitalize">{{
                                    service.data?.page }} </div>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-bold fs-5 text-gray-700">Description</label>
                            <div class="col-lg-8">
                                <div class="fw-bold fs-6 text-gray-500 text-capitalize table table-primary"
                                    v-html="service?.data.description">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

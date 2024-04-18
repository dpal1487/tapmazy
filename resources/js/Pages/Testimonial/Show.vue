<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";

export default defineComponent({
    props: ['testimonial'],
    data() {
        return {
            title: "Testimonial Overview",
        };
    },
    components: {
        AppLayout,
        Link,
        Head,
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
                <Link href="/testimonials" class="text-muted text-hover-primary">Testimonials</Link>
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
                        <h3 class="fw-bold m-0"> Testimonial Details</h3>
                    </div>
                </div>
                <div class="card-body p-9">
                    <div class="row">
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-bold fs-5 text-gray-700">Name</label>
                            <div class="col-lg-8">
                                <div class="fw-bold fs-6 text-gray-500 text-capitalize">{{
                                    testimonial.data?.name }} </div>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-bold fs-5 text-gray-700">Page Name</label>
                            <div class="col-lg-8">
                                <div class="fw-bold fs-6 text-capitalize badge badge-light-success rounded-pill"
                                    v-if="this.testimonial?.data?.is_published == 1">
                                    Published</div>
                                <div class="fw-bold fs-6 text-capitalize badge badge-light-success rounded-pill" v-else>
                                    Unpublished</div>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-bold fs-5 text-gray-700">Testimonial</label>
                            <div class="col-lg-8">
                                <div class="fw-bold fs-6 text-gray-500 text-capitalize table table-primary"
                                    v-html="testimonial?.data.testimonial">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";

export default defineComponent({
    props: ['blog'],
    data() {
        return {
            title: "Blog Overview",
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
                <Link href="/blogs" class="text-muted text-hover-primary">Blogs</Link>
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
                <div class="card-body p-9">
                    <div class="row">
                        <div class="row mb-7 align-items-center">
                            <label class="col-lg-4  fw-bold fs-5 text-gray-700">Image</label>
                            <div class="col-lg-8">
                                <div v-if="blog.data.image">
                                    <div class="symbol symbol-160px me-5">
                                        <img alt="Logo" :src="blog.data.image?.url">
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
                            <label class="col-lg-4 fw-bold fs-5 text-gray-700">Title</label>
                            <div class="col-lg-8">
                                <div class="fw-bold fs-6 text-gray-500 text-capitalize">{{ blog.data?.title }} </div>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-bold fs-5 text-gray-700">Status</label>
                            <div class="col-lg-8">
                                <div class="fw-bold fs-6 text-capitalize badge badge-light-success rounded-pill"
                                    v-if="this.blog?.data?.is_published == 1">
                                    Published</div>
                                <div class="fw-bold fs-6 text-capitalize badge badge-light-danger rounded-pill" v-else>
                                    Unpublished</div>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-bold fs-5 text-gray-700">Content</label>
                            <div class="col-lg-8">
                                <div class="fw-bold fs-6 text-gray-500 text-capitalize table table-primary"
                                    v-html="blog?.data.content">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

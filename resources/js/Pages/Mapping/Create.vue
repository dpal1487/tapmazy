<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import MappingForm from "./Components/MappingForm.vue";
import ProjecLinkList from './Components/LinkList.vue'
import Pagination from "../../Jetstream/Pagination.vue";
import { toast } from "vue3-toastify";
import Multiselect from "@vueform/multiselect";
export default defineComponent({
    props: ["countries", "project", 'links'],
    components: {
        AppLayout,
        Link,
        Head,
        MappingForm,
        ProjecLinkList,
        Pagination,
        Multiselect
    },
    data() {
        return {
            title: "Add Link",
            isEdit: false,
            form: {}
        }
    },
    methods: {
        submit(form) {
            this.form = form;
            this.form
                .transform((data) => ({
                    ...data,
                }))
                .post(this.route('mapping.store', this.project?.data?.id), {
                    onSuccess: (data) => {
                        toast.success(this.$page.props.jetstream.flash.message);
                    },
                    onError: (data) => {
                        if (data.message) {
                            toast.error(data.message)
                        }

                    },
                });
        },

    }
});
</script>
<template>
    <Head title="Add New Link" />
    <app-layout :title="title">
        <template #breadcrumb>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
                <Link href="/projects" class="text-muted text-hover-primary">Projects</Link>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
                <Link :href="`/project/${project?.id}`" class="text-muted text-hover-primary">Project Overview</Link>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
                Project Link
            </li>
        </template>
        <template #toolbar>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <!--begin::Primary button-->
                <Link href="/project/create" class="btn btn-sm fw-bold btn-primary">
                <i class="bi bi-plus-circle"></i>Add New Project</Link>
                <!--end::Primary button-->
            </div>
        </template>
        <div class="row mb-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header align-items-center">
                        <div class="card-title">
                            <h2>Add New Link</h2>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 mb-5">
                                <mapping-form @submitted="submit" :countries="countries?.data" :project="project?.data">
                                    <template #action>
                                        <div class="d-flex justify-content-end">
                                            <!--begin::Button-->
                                            <Link type="button" class="btn btn-secondary me-5"
                                                :href="`/project/${project?.id}`">Discard</Link>
                                            <!--begin::Button-->
                                            <button type="submit" class="btn btn-primary"
                                                :class="{ 'text-white-50': form.processing }">
                                                <div v-show="form.processing" class="spinner-border spinner-border-sm">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                Save
                                            </button>
                                        </div>
                                    </template>
                                </mapping-form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-5">
            <div class="card">
                <div class="card-header align-items-center">
                    <div class="card-title">
                        <h2>Project Links</h2>
                    </div>
                </div>
            </div>
        </div>
        <ProjecLinkList :links="links?.data" />
    </app-layout>
</template>

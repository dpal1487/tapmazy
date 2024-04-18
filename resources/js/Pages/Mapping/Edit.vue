<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import MappingForm from "./Components/MappingForm.vue";
import { toast } from "vue3-toastify";

export default defineComponent({
    props: ["countries", "project"],
    components: {
        AppLayout,
        Link,
        Head,
        MappingForm,
    },

    data() {
        return {
            title: "Edit Link",
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
                .post(this.route('mapping.update', this.project.data.id), {
                    onSuccess: (data) => {
                        toast.success(this.$page.props.jetstream.flash.message);
                    },
                    onError: (data) => {

                    },
                });
        }
    }
});
</script>
<template>
    <Head title="Edit Link" />
    <app-layout :title="title">
        <template #breadcrumb>

            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
                <a href="/projects" class="text-muted text-hover-primary">Projects</a>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
                <a :href="`/project/${project.project_id}`" class="text-muted text-hover-primary">Overview</a>
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
                            <h2>Edit Link</h2>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 mb-5">
                                {{ project }}
                                <mapping-form @submitted="submit" :countries="countries.data" :project="project?.data">
                                    <template #action>
                                        <div class="d-flex justify-content-end">
                                            <Link type="button" class="btn btn-secondary me-5"
                                                :href="`/project/${project?.data?.project_id}`">Discard</Link>
                                            <button type="submit" class="btn btn-primary"
                                                :class="{ 'text-white-50': form.processing }">
                                                <div v-show="form.processing" class="spinner-border spinner-border-sm">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                Save Change
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
    </app-layout>
</template>

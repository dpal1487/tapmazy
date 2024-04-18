<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import SamplingForm from "./Components/SamplingForm.vue";
import Pagination from "../../Jetstream/Pagination.vue";
import { toast } from "vue3-toastify";
export default defineComponent({
    props: ["projects", "project", "suppliers"],
    components: {
        AppLayout,
        Link,
        Head,
        SamplingForm,
        Pagination,
    },
    data() {
        return {
            form: {
                processing: false,
                action: "",
            },
        };
    },
    methods: {
        submit(form) {
            this.form = form;
            this.form
                .transform((data) => ({
                    ...data,
                }))
                .post(this.route("sampling.store"), {
                    onSuccess: (data) => {
                        toast.success(this.$page.props.jetstream.flash.message);
                        this.isEdit = false;
                    },
                    onError: (data) => {
                        console.log(data);
                    },
                });
        },
    },
});
</script>
<template>
    <Head title="Add New Supplier" />
    <app-layout title="Add New Supplier">
        <template #breadcrumb>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item">
                <Link href="/projects" class="text-muted text-hover-primary">Projects</Link>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
                <Link class="text-muted text-hover-primary" :href="`/project/${project.project_id}`">Overview</Link>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
                {{ project.project_name }}
            </li>
        </template>
        <div class="row mb-10">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <sampling-form :suppliers="suppliers.data" :project="project" @submitted="submit">
                                    <template #action>
                                        <div class="d-flex justify-content-end mt-4">
                                            <!--begin::Button-->
                                            <Link class="btn btn-secondary me-5" :href="`/project/${project.project_id}`"
                                                role="button">Discard</Link>
                                            <!--begin::Button-->
                                            <button @click="this.form.action = 'save'" :disabled="form.processing"
                                                class="btn btn-primary" :class="{
                                                    'text-white-50':
                                                        form.processing,
                                                }">
                                                <div v-show="form.processing" class="spinner-border spinner-border-sm"
                                                    role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                Save
                                            </button>
                                        </div>
                                    </template>
                                </sampling-form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

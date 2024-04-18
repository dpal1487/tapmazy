<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import SamplingForm from "./Components/SamplingForm.vue";
import Pagination from "../../Jetstream/Pagination.vue";
import SupplierList from "./Components/SupplierList.vue";
import { toast } from "vue3-toastify";

export default defineComponent({
    props: ["projects", "project", "supplier_project", 'suppliers', 'redirect_to'],
    components: {
        AppLayout,
        Link,
        Head,
        SamplingForm,
        Pagination,
        SupplierList
    },
    data() {
        return {
            th: [
                'Supplier Name',
                'Project Name',
                'Country',
                'CPI',
                'Sample Size',
                'Project Link',
                'TClick',
                'Complete',
                'Terminate',
                'S Terminate',
                'Quotafull',
                'Incomplete',
                'IR',
                'STATUS',
                'Action'
            ],
            form: {
                processing: false,
                action: ''
            }
        }
    },
    methods: {
        submit(form) {
            this.form = form;
            this.form.transform((data) => ({
                ...data,
            }))
                .post(this.route("sampling.update", this.project.data.id),
                    {
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
    <Head title="Edit Supplier" />
    <app-layout title="Edit Supplier">
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
                <Link class="text-muted text-hover-primary" :href="`/project/${project.id}/suppliers`">
                Suppliers
                </Link>
            </li>

            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
                {{ project.data.project_name }}
            </li>
        </template>
        <div class="row mb-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <sampling-form :suppliers="suppliers.data" :project="project?.data"
                                    :supplier_project="suppliers?.data" @submitted="submit" :action="redirect_to">
                                    <template #action>
                                        <div class="d-flex justify-content-end mt-4">
                                            <!--begin::Button-->
                                            <Link class="btn btn-secondary me-5"
                                                :href="`/project/${project.data.project_id}/suppliers`" role="button">Discard
                                            </Link>
                                            <!--begin::Button-->
                                            <button @click="this.form.action = 'update'" :disabled="form.processing"
                                                class="btn btn-primary" :class="{ 'text-white-50': form.processing }">
                                                <div v-show="form.processing" class="spinner-border spinner-border-sm"
                                                    role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                Save Changes
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

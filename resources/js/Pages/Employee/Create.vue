<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import EmployeeForm from "./Components/EmployeeForm.vue";
export default defineComponent({
    props: ["departments","roles"],
    components: {
        AppLayout,
        Link,
        Head,
        EmployeeForm,
    },
    data() {
        return {
            title: "Add New Employee",
            form: {},
        }
    },
    methods: {
        submit(form) {
            this.form = form;
            this.form.transform((data) => ({
                ...data,
            }))
                .post(this.route("employee.store"), {
                    onSuccess: (data) => {
                        toast.success(this.$page.props.jetstream.flash.message);
                    },
                    onError: (data) => {
                        if (data.message) {
                            toast.error(data.message);
                        }
                    },
                })
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
                <a href="/employees" class="text-muted text-hover-primary">Employees</a>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
                {{ title }}
            </li>
        </template>
        <div class="row mb-5">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header align-items-center">
                        <div class="card-title">
                            <h2>Add Employee</h2>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-8">
                                <!-- end page title -->
                                <employee-form  @submitted="submit" :departments="departments.data" :roles="roles.data">
                                    <template #action>
                                        <div class="d-flex justify-content-end">
                                            <!--begin::Button-->
                                            <Link type="button" class="btn btn-outline-secondary me-5" href="/menus">Discard
                                            </Link>
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
                                </employee-form>
                                <!-- end row -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

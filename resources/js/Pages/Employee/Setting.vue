<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import Header from "./Components/Header.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import { toast } from 'vue3-toastify';
import UpdateEmail from "./Components/UpdateEmail.vue";
import UpdatePassword from "./Components/UpdatePassword.vue";
import DeactivateAccount from "./Components/DeactivateAccount.vue";
export default defineComponent({
    props: ['employee', 'user', 'departments', 'address'],
    data() {
        return {

            title: "Employee Settings"
        };
    },
    components: {
        AppLayout,
        Header,
        Link,
        Head,
        UpdateEmail,
        UpdatePassword,
        DeactivateAccount
    },
    methods: {

    },

});
</script>
<template>
    <Head :title="title" />
    <app-layout :title="title">
        <template #breadcrumb>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item">
                <Link href="/employees" class="text-muted text-hover-primary">Employee</Link>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
                Overview
            </li>
        </template>
        <!--begin::Navbar-->
        <Header :employee="employee.data" />
        <div class="card mb-5 mb-xl-10">
            <!--begin::Card header-->
            <div class="card-header border-0 cursor-pointer" role="button">
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Sign-in Method</h3>
                </div>
            </div>
            <!--end::Card header-->
            <!--begin::Content-->
            <div class="collapse show">
                <!--begin::Card body-->
                <div class="card-body border-top p-9">
                    <update-email :employee="employee.data" />
                    <div class="separator separator-dashed my-6"></div>
                    <update-password :employee="employee.data" />
                </div>
            </div>
        </div>
        <DeactivateAccount :employee="employee.data" />
        <!--end::details View-->
    </app-layout>
</template>

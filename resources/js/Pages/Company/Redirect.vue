<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import Header from "./Components/Header.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";

export default defineComponent({

    props: ['company', 'redirect',],
    components: {
        AppLayout,
        Header,
        Link,
        Head,

    },
    data() {
        return {
            title: 'Company Redirects'
        };
    },
    methods: {

    },
    created() {
    }
});
</script>
<template>
    <Head :title="title" />
    <app-layout :title="title">
        <template #breadcrumb>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
                Redirects
            </li>
        </template>
        <!--begin::Navbar-->
        <Header :company="company.data" />
        <!--begin::details View-->
        <div class="card">
            <div class="card-header">
                <!--begin::Title-->
                <!--begin::Card title-->
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Redirect Details</h3>
                </div>
            </div>
            <div class="card-body">
                <!--begin::Input group-->
                <div class="row mb-7">
                    <!--begin::Label-->
                    <label class="col-4 fw-bold fs-5 text-gray-800">Complete Url</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="col-8 fv-row">
                        <span class="fw-bold fs-6 text-gray-700">{{ redirect.data.complete_url }}
                        </span>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="row mb-7">
                    <!--begin::Label-->
                    <label class="col-4 fw-bold fs-5 text-gray-800">Terminate Url</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="col-8 fv-row">
                        <span class="fw-bold fs-6 text-gray-700">{{ redirect.data.terminate_url }}
                        </span>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="row mb-7">
                    <!--begin::Label-->
                    <label class="col-4 fw-bold fs-5 text-gray-800">Quotafull Url</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="col-8 fv-row">
                        <span class="fw-bold fs-6 text-gray-700">{{ redirect.data.quotafull_url }}
                        </span>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="row mb-7">
                    <!--begin::Label-->
                    <label class="col-4 fw-bold fs-5 text-gray-800">Security Terminate Url
                    </label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="col-8 fv-row">
                        <span class="fw-bold fs-6 text-gray-700">{{ redirect.data.security_terminate_url }}
                        </span>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
            </div>
            <!--end::Form-->
        </div>
        <!--end::details View-->
    </app-layout>
</template>

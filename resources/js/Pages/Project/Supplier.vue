<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import SupplierList from "../Sampling/Components/SupplierList.vue";
import TopCard from "./Components/TopCard.vue";
import { Inertia } from "@inertiajs/inertia";

import Loading from "vue-loading-overlay";
import 'vue-loading-overlay/dist/css/index.css';
export default defineComponent({
    props: ["project", "suppliers"],
    data() {
        return {
            title: "Manage Suppliers",
            form: {},
            isLoading: false,
            isFullPage: true,

        };
    },
    components: {
        AppLayout,
        Link,
        Head,
        SupplierList,
        TopCard,
        Loading
    },

    methods: {
        search() {
            this.isLoading = true;
            Inertia.get(
                `/project/${this.project.data.id}/suppliers`,
                this.form,
                {
                    onFinish(response) {
                        this.isLoading = false;
                    },
                })
        }
    },
    created() {
        var url = new URL(window.location.href);
        this.form.supplier = url.searchParams.get("supplier");
        this.form.status = url.searchParams.get("status");
    },
});
</script>
<template>
    <app-layout :title="title">
        <loading v-model:active="isLoading" :can-cancel="false" :is-full-page="isFullPage" />

        <Head title="Project Suppliers" />
        <TopCard :project="project?.data"  />
        <template #breadcrumb>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
                <Link class="text-muted text-hover-primary" href="/projects">Projects</Link>
            </li>

            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
                Suppliers
            </li>
        </template>
        <div class="card card-flush mb-5">
            <div class="card-header align-items-center px-5">
                <form @submit.prevent="search" class="card-title">
                    <input v-model="form.q" class="form-control form-control-sm form-control-solid" type="text"
                        placeholder="Search here..." />
                    <select v-model="form.status" class="form-control form-control-sm form-control-solid ms-3">
                        <option value="">Select status</option>
                        <option value="1">Active</option>
                        <option value="0">Inctive</option>
                    </select>
                    <button class="btn btn-primary btn-sm ms-3"><i class="bi bi-search"></i></button>
                </form>
            </div>
        </div>
        <!-- {{ suppliers }} -->
        <SupplierList :projects="suppliers?.data" action="project.supplier" />
    </app-layout>
</template>

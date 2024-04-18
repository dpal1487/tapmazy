<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import SupplierList from "../Sampling/Components/SupplierList.vue";
import TopCard from "./Components/TopCard.vue";
import { Inertia } from "@inertiajs/inertia";

export default defineComponent({
    props: ["project", "suppliers", "countries"],
    data() {
        return {
            title: "Suppliers",
            form: {},
        };
    },
    components: {
        AppLayout,
        Link,
        Head,
        SupplierList,
        TopCard,
    },
    methods: {
        search() {
            Inertia.get(`/mapping/${this.project.id}/suppliers`, this.form, {});
        },
    },
    created() { },
});
</script>
<template>
    <app-layout :title="title">

        <Head :title="title" />
        <TopCard :project="project.data" :countries="countries.data" />
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
                <Link class="text-muted text-hover-primary" :href="`/project/${project.data.project_id}`">Project Overview
                </Link>
            </li>
            <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
                {{ title }}
            </li>
        </template>
        <div class="mb-5">
            <SupplierList :projects="suppliers.data" v-if="suppliers.data.length > 0" />
            <div class="d-flex justify-content-center align-content-center pt-10 pb-10" v-else>
                <div class="text-center py-10">
                    <img src="/assets/images/emptyrespondent.png" style="height: 200px" />
                    <div class="fw-bold fs-2 text-gray-900 mt-5">
                        No Supplier Found!
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

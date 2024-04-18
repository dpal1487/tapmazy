<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import TopCard from "./Components/TopCard.vue";
import ActivityList from "./Components/ActivityList.vue";
export default defineComponent({
    props: ["project", "activities",],
    data() {
        return {
            title: "Manage Activity",
        };
    },
    components: {
        AppLayout,
        Link,
        Head,
        TopCard,
        ActivityList,
    },
});
</script>
<template>
    <app-layout :title="title">

        <Head title="Project Activity" />
        <TopCard :project="project?.data" />
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
                Activity
            </li>
        </template>
        <div class="card">
            <div class="card-header card-header-stretch">
                <div class="card-title d-flex align-items-center">
                    <h3 class="fw-bold m-0 text-gray-800">Project Activity</h3>
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <ActivityList :activities="activities" />
                </div>
            </div>
        </div>
    </app-layout>
</template>

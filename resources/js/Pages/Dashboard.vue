
<script>
import { defineComponent } from "vue"
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, Head } from "@inertiajs/inertia-vue3";

export default defineComponent({
    props: ['projects'],
    components: {
        AppLayout,
        Link,
        Head
    },
});
</script>

<template>
    <app-layout title="Dashboard">
        <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
            <div class="col-md-2">
                <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end w-100"
                    style=" background-color: #298356;background-image:url('/assets/media/patterns/vector-1.png') ; height:100%;">
                    <div class="card-header p-5">
                        <div class="card-title d-flex flex-column">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ projects.live_projects }}</span>
                            <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Live Projects</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end "
                    style="background-color: #7f41f1;background-image:url('/assets/media/patterns/vector-1.png'); height:100%;">
                    <div class="card-header p-5">
                        <div class="card-title d-flex flex-column">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ projects.inactive_projects }}</span>
                            <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Hold Projects</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end"
                    style="background-color: #F1416C;background-image:url('/assets/media/patterns/vector-1.png') ; height:100%; width: 100%;">
                    <div class="card-header p-5">
                        <div class="card-title d-flex flex-column">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ projects.cancelled_projects }}</span>
                            <span class="text-white opacity-75 pt-1 me-3 fw-semibold fs-6">Cancelled Projects</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">

                <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end "
                    style="background-color: #f18741;background-image:url('/assets/media/patterns/vector-1.png');height:100%;">
                    <div class="card-header p-5">
                        <div class="card-title d-flex flex-column">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ projects.closed_projects }}</span>
                            <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Closed Projects</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end "
                    style="background-color: #41cef1;background-image:url('/assets/media/patterns/vector-1.png') ; height:100%;">
                    <div class="card-header p-5">
                        <div class="card-title d-flex flex-column">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ projects.archived_projects }}</span>
                            <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Archived Projects</span>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-md-2">

                <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end "
                    style="background-color: #dc41f1;background-image:url('/assets/media/patterns/vector-1.png') ; height:100%;">
                    <div class="card-header p-5">
                        <div class="card-title d-flex flex-column">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ projects.invoiced_projects }}</span>
                            <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Invoiced Projects</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
            <div>
                <div class="card">
                    <div class="card-header pt-7">
                        <h3 class="card-title align-items-start  flex-column">
                            <span class="card-label fw-bold text-gray-800 align-middle mb-4">Projects Stats</span>
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-row-dashed align-middle">
                                <thead>
                                    <tr class="text-gray-700 fw-bold fs-7 text-uppercase">
                                        <th>PROJECT ID</th>
                                        <th>PROJECT NAME</th>
                                        <th>PROJECT TYPE</th>
                                        <th>CLIENT NAME</th>
                                        <th>STATUS</th>
                                    </tr>
                                </thead>
                                <tbody class="">
                                    <tr v-for="(project, index) in projects.latest_projects?.data" :key="index"
                                        class="text-gray-500  fw-semibold">
                                        <td>{{ project?.project_id }}</td>
                                        <td>{{ project?.project_name }}
                                        </td>
                                        <td>
                                            <span
                                                class="badge text-gray-500 badge-light-secondary mx-1 text-capitalize rounded-pill"
                                                v-if="project.device_type"
                                                v-for="(type, index) in JSON.parse(project.device_type)">{{ type }}</span>
                                        </td>
                                        <td>{{ project?.client?.name }}
                                        </td>
                                        <td>
                                            <span :class="`mx-1 text-capitalize badge badge-light-${project?.status == 'live'
                                                ? 'success'
                                                : 'danger'
                                                }`">{{ project?.status }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

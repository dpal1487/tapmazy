<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import LinkList from "../Mapping/Components/LinkList.vue";
import TopCard from "./Components/TopCard.vue";
import Pagination from "../../Jetstream/Pagination.vue";
import Loading from "vue-loading-overlay";
import 'vue-loading-overlay/dist/css/index.css';
import { Inertia } from "@inertiajs/inertia";
import QuestionForm from "./Components/Modal/QuestionForm.vue";
import QuestionList from "./Components/QuestionList.vue";

export default defineComponent({
    props: ["project", "questions",],
    data() {
        return {
            title: "Project Question Answer",
            isLoading: false,
            isFullPage: true,
            isModalOpen: false,
            activeId: null,
            form: {}
        };
    },
    components: {
        AppLayout,
        Link,
        Head,
        LinkList,
        TopCard,
        Pagination,
        Loading,
        QuestionForm,
        QuestionList,
    },
    methods: {
        search() {
            this.isLoading = true;
            Inertia.get(
                `/project/${this.project.data.id}`,
                this.form,
                {
                    onFinish(response) {
                        this.isLoading = false;
                    },
                })
        },
        showQuestionFormModal(id) {
            this.isModalOpen = true;
            this.activeId = id;
        },
        hideQuestionFormModal() {
            this.isModalOpen = false;
        },
    },
    created() {
        var url = new URL(window.location.href);
        this.form.q = url.searchParams.get("q");
        this.form.status = url.searchParams.get("status");
    },
});
</script>
<template>
    <Head :title="title" />
    <app-layout :title="title">
        <loading v-model:active="isLoading" :can-cancel="false" :is-full-page="isFullPage" />
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
            <li class="breadcrumb-item text-muted">{{ title }}</li>
        </template>
        <template #toolbar>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <Link href="/project/create" class="btn btn-sm fw-bold btn-primary">
                <i class="bi bi-plus-circle"></i>Add New Project</Link>
            </div>
        </template>
        <TopCard :project="project.data" />
        <QuestionForm :show="isModalOpen" @hidemodal="hideQuestionFormModal" :project_id="project?.data?.id" />
        <div class="card">
            <div class="card-header align-items-center px-5">
                <div class="card-title d-flex flex-root align-items-center">
                    <h3 class="fw-bold m-0 text-gray-800">{{ title }}</h3>
                </div>
                <div>
                    <button type="button" v-if="$page.props.user.role.role.slug == 'admin'" class="btn btn-primary btn-sm "
                        @click="showQuestionFormModal(project?.data?.id)"><i class="bi bi-plus-circle"></i>Add Question
                    </button>

                </div>
            </div>
            <QuestionList :questions="questions" />

        </div>
    </app-layout>
</template>

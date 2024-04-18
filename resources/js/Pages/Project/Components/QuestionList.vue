<script>
import { defineComponent } from 'vue';
import Pagination from "@/Jetstream/Pagination.vue";
import NoRecordMessage from '../../../Components/NoRecordMessage.vue';
import QuestionForm from "../Components/Modal/QuestionForm.vue";

import { Head, Link } from '@inertiajs/inertia-vue3';
import utils from '../../../utils';
export default defineComponent({

    props: {
        questions: {
            type: Object,
            default: null
        },
    },
    components: {
        Head,
        Link,
        Pagination,
        NoRecordMessage,
        QuestionForm
    },
    data() {
        return {
            form: {},
            isModalOpen: false,
            activeId: '',
            projectId: '',
            tbody: [
                "Question",
                "Question Type",
                "Answer",
                "Action",
            ],
        }

    },
    methods: {
        async confirmDelete(id, index) {
            console.log("Confirm", id)
            this.isLoading = true;
            await utils.deleteIndexDialog(route('project.question-answer.destroy', id), this.questions.data, index);
            this.isLoading = false;
        },
        showQuestionFormModal(value) {
            console.log(value);
            this.isModalOpen = true;
            this.activeId = value.id;
            this.projectId = value.project_id;
        },
        hideQuestionFormModal() {
            this.isModalOpen = false;
        },
    }

});
</script>

<template>
    <QuestionForm :show="isModalOpen" @hidemodal="hideQuestionFormModal" :id="activeId" :project_id="projectId" />
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-row-dashed fs-6">
                <thead>
                    <tr class="text-gray-400 fw-bold fs-7 min-w-100px text-uppercase">
                        <th class="min-w-100px" v-for="(th, index) in tbody" :key="index">
                            {{ th }}
                        </th>
                    </tr>
                </thead>
                <tbody class="fw-semibold text-gray-600" v-if="questions?.data.length > 0">
                    <tr v-for="(question, index) in questions?.data" :key="index">

                        <td>{{ question?.question }}</td>
                        <td>{{ question?.question_type }}</td>
                        <td><span v-for="(answer) in question.answers">
                                {{ answer?.answer }} <br />
                            </span></td>
                        <td class="min-w-150px">
                            <a href="#" class="btn btn-sm btn-light menu-dropdown" :id="`dropdown-${question.id}`"
                                data-bs-toggle="dropdown">Actions
                                <span class="svg-icon svg-icon-5 m-0">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                                            fill="currentColor"></path>
                                    </svg>
                                </span>
                            </a>
                            <div class="text-left dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                :aria-labelled:by="`dropdown-${question.id}`">
                                <div class="menu-item px-3">
                                    <span type="button"
                                        @click="showQuestionFormModal({ project_id: question?.project_id, id: question.id })"
                                        class="menu-link border-0"><i class="bi bi-pencil me-2"></i>Edit
                                    </span>
                                </div>
                                <div class="menu-item px-3">
                                    <span @click="confirmDelete(question.id, index)" class="menu-link"><i
                                            class="bi bi-trash3 me-2"></i>Delete</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
                <tbody class="fw-semibold text-gray-600" v-else>
                    <tr class="text-gray-600 fw-bold fs-7 align-middle text-uppercase h-100px">
                        <td colspan="4" class="text-center h-full">
                            <NoRecordMessage />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer" v-if="questions?.data?.length > 0">
        <Pagination :links="questions" />
    </div>
</template>
